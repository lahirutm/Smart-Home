const {JWT} = require('google-auth-library');
const keys = require('./service-account.json');
const WebSocket = require('ws');
const http = require('http');
const mysql = require('mysql');
const StringDecoder = require('string_decoder').StringDecoder;

const ws_port = 8080;
const http_port = 8000;

var mysql_con = mysql.createConnection({
    host : 'localhost',
    user : '<mysql_database_user_name>',
    password : '<mysql_database_password>',
    database : '<mysql_database_name>'
});

mysql_con.connect();

const wss = new WebSocket.Server({ port: ws_port });

// Handle mysql events
function heartbeat_update_to_mysql(value){
	mysql_con.query("UPDATE `devices` SET devices.is_online=1,last_online=NOW() WHERE MD5(devices.id)='" + value + "' ");
}

function status_disconnected_update_to_mysql(value){
	mysql_con.query("UPDATE `devices` SET devices.is_online=0,last_online=NOW() WHERE MD5(devices.id)='" + value + "' ");
}

var ws_array = [];
// Broadcast to all.
wss.broadcast = function broadcast(data) {

  console.log("\nData received for broadcating: %s",JSON.stringify(data));

  if(ws_array[data.deviceId]){
    client = ws_array[data.deviceId];
   
    if (client.readyState === WebSocket.OPEN) {
      client.send(JSON.stringify(data));
      console.log("Data send via WSS to device %s DATA % \n",data.deviceId,JSON.stringify(data));
    }
    else {
        delete ws_array[data.deviceId];
        status_disconnected_update_to_mysql(data.deviceId);
        console.log("device offline %s\n",data.deviceId);
    }
  }
  else {
      status_disconnected_update_to_mysql(data.deviceId);
      console.log("\nWS Connection not found for device: %s",data.deviceId);
  }
};

wss.on('connection', function connection(ws,req) {
   ws.isAlive = true;
   ws.on('pong', heartbeat);
  
   const ip = req.socket.remoteAddress;

   if (!req.headers.authorization || req.headers.authorization.indexOf('Basic ') === -1) {
      console.log("Missing Authorization Header in the connection ! \n %", req.headers);
   }
   else {
    var tmp = req.headers['authorization'].split(' ');   // Split on a space, the original auth looks like  "Basic Y2hhcmxlczoxMjM0NQ=="                
    console.log("Request headers %s \n",req.headers['authorization']);

    if(tmp[1]){
      var buf = new Buffer(tmp[1], 'base64'); // create a buffer and tell it the data coming in is base64
      var plain_auth = buf.toString();        // read it back out as a string
      var bhead = plain_auth.split(':');	// split on a ':'
      var key = bhead[0];
      var value = bhead[1];
      
      ws.device_id = value;
      ws_array[value] = ws;
      heartbeat_update_to_mysql(value);
      console.log('Device %s connected', value);
      
      ws.on('message', function incoming(message) {
          ws_array[value] = ws;
          heartbeat_update_to_mysql(value);
          console.log('Data received from device:%s at %s\nMessage:%s', value, new Date(), message);
      });

      ws.on('close', function () {
        delete ws_array[value];
        status_disconnected_update_to_mysql(value);
        console.log("Device '%s' disconnected !", value);
      });

      ws.on('pong', function () {
        heartbeat_update_to_mysql(value);
        console.log("PONG received from %s \n", value);
      });
    }
  }

  console.log('WS Client: %s connected from IP: %s', tmp, ip);
});

function heartbeat() {
  this.isAlive = true;
}

const interval = setInterval(function ping() {
  wss.clients.forEach(function each(ws) {
    if (ws.isAlive === false) {
        console.log("A Device %s disconnected ! \n",ws.device_id);
        delete ws_array[ws.device_id];
        status_disconnected_update_to_mysql(ws.device_id);
        return ws.terminate();
    }
    ws.isAlive = false;
    ws.ping();
    console.log('Sent PING to: %s \n',ws.device_id);
  });
}, 30000);


// Instantiate the HTTP server.
const httpServer = http.createServer((req, res) => {
    var jsonString = '';
    var buffers = [];

    req.on('data', function (chunk) {
        if (req.method == 'POST') {
          try {
            buffers.push(chunk);
          }
          catch(e) {
            console.log(e);
          }
        }
        else if (req.method == 'GET') {
          console.log("Unauthorized GET request !");
        }
      });

      req.on('error', (err) => {
            // This prints the error message and stack trace to `stderr`.
            console.log("Error: %s \n" + err.stack);
      });

      req.on('end', function () {
        if(buffers.length >0){
          try {
            jsonString = JSON.parse(Buffer.concat(buffers).toString())
            wss.broadcast(jsonString);
            console.log('New event broadcasted. \n ' + JSON.stringify(jsonString));
          }
          catch(e) {
            console.log(e);
          }
        }
        else {
          console.log("Request ended with no valid data !");
        }
      });
    res.end();
});


httpServer.listen(http_port, () => {
  console.log(`Web server is listening on port ${http_port}`);
});




async function main() {
  const client = new JWT({
    email: keys.client_email,
    key: keys.private_key,
    scopes: ['https://www.googleapis.com/auth/homegraph'],
  });

  const url = `https://www.googleapis.com/auth/homegraph`;
  const res = await client.request({url});

  if(res.config.headers.Authorization){
    var string = res.config.headers.Authorization;
    string = string.split(" ");
    console.log(string[1]);
    mysql_con.query("UPDATE `oauth_tokens` SET `oauth2_access_token`='"+ string[1] +"' WHERE `user_id`=1 ");

  }
}

main().catch(console.error);

setInterval(function(){
    main().catch(console.error);
},1800000);

