<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Colombo');
class dataValues {}

// database class
class db {
        // The database connection
        protected static $connection;

	// ****************************
	// Database Configurations
	// ****************************
	var $hostname = 'localhost';
	var $username = 'webserv9_iot';
	var $password = 'iotwebservice';
	var $database = 'webserv9_iot';
	var $base_url = "https://webservice.lk/iot/";
	var $physical_directory_path = "/home/webserv9/public_html/iot/";
	var $project_id = "smarthome-91f8a";	

	// ****************************
	// SMS Configurations
	// ****************************
	var $sms_login = "serendib@globemw.net";
	var $sms_password = "ffa4174e7fc92f1b459a66049f9a1ff1";
	var $sms_sender_id = "Serendib"; // Max 11 charactors

	public function __construct() {
        
	
    }

        public function connect() {    
            if(!isset(self::$connection)) {               
                self::$connection = new mysqli($this->hostname,$this->username,$this->password,$this->database);
            }

            // If connection was not successful, handle the error
            if(self::$connection === false) {
                // Handle error - notify administrator, log to a file, show an error screen, etc.
                return false;
            }
            return self::$connection;
        }


        public function query($query) {
            // Connect to the database
            $connection = $this -> connect();

            // Query the database
            $result = $connection -> query($query);

            return $result;
        }

        public function multi_query($query){
        	// Connect to the database
            $connection = $this -> connect();

        	// Query the database
            $result = $connection -> multi_query($query);

            return $result;
        }

		public function insert($query) {
            // Connect to the database
            $connection = $this -> connect();

            // Query the database
            $connection -> query($query);
	    // Get inserted id
	    $insertid = $connection -> insert_id;

            return $insertid;
        }

    	public function select($query) {
            $rows = array();
            $result = $this -> query($query);
            if($result === false) {
                return false;
            }
            while ($row = $result -> fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
    }
	
	public function num_rows($query) {
            $result = $this -> query($query);
	    
            if($result === false) {
                $count = 0;
            }
	    else $count = $result->num_rows;
            
            return $count;
        }

        /**
         * Fetch the last error from the database
         * 
         * @return string Database error message
         */
        public function error() {
            $connection = $this -> connect();
            return $connection -> error;
        }

        /**
         * Quote and escape value for use in a database query
         *
         * @param string $value The value to be quoted and escaped
         * @return string The quoted and escaped string
         */
        public function escape($value) {
            $connection = $this -> connect();
            return $connection -> real_escape_string(trim($value));
        }
        
    public function get_users($data){
        $sql = "SELECT * FROM users WHERE 1";
        
        if(isset($data->id) && $data->id>0) $sql .= " AND id='".$data->id."'";
        
        $results = $this->select($sql);
        
        return $results;
    }
    
    public function oauth_tokens($data){
        $sql = "SELECT * FROM oauth_tokens WHERE 1";
        
        if(isset($data->id) && $data->id>0) $sql .= " AND id='".$data->id."'";
        if(isset($data->user_id) && $data->user_id>0) $sql .= " AND user_id='".$data->user_id."'";
        if(isset($data->api_key) && !empty($data->api_key)) $sql .= " AND api_key='".$data->api_key."'";
        
        $results = $this->select($sql);
        
        return $results;
    }
    
    public function get_device_types($data){
         $sql = "SELECT * FROM device_types WHERE 1";
        
        if(isset($data->id) && $data->id>0) $sql .= " AND id='".$data->id."'";
        if(isset($data->type) && !empty($data->type)) $sql .= " AND type='".$data->type."'";
        
        $results = $this->select($sql);
        
        return $results;
    }


    public function httpPost($url, $data)
    {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
    }

    public function __destruct() {    
        mysqli_close($this -> connect());
    }
}

// info class
class info {
	public function get_client_ip() {
		  $ipaddress = '';
		  if (getenv('HTTP_CLIENT_IP'))
			  $ipaddress = getenv('HTTP_CLIENT_IP');
		  else if(getenv('HTTP_X_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		  else if(getenv('HTTP_X_FORWARDED'))
			  $ipaddress = getenv('HTTP_X_FORWARDED');
		  else if(getenv('HTTP_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_FORWARDED_FOR');
		  else if(getenv('HTTP_FORWARDED'))
			  $ipaddress = getenv('HTTP_FORWARDED');
		  else if(getenv('REMOTE_ADDR'))
			  $ipaddress = getenv('REMOTE_ADDR');
		  else $ipaddress = 'UNKNOWN';
	
		  return $ipaddress;
	 }
}

$db = new db();
$info = new info();
?>
