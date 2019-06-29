<?php
require('../../../class.db.php');
header('Content-Type: application/json');

$headers = apache_request_headers();
if(isset($headers['Authorization'])) {
	$authorization_header = explode(" ",$headers['Authorization']);
	$access_token = $authorization_header[1];
}

$request = json_decode(file_get_contents( 'php://input' ),true);
if(isset($request['inputs'][0]['intent'])) $intent = $request['inputs'][0]['intent'];
if(isset($request['requestId'])) $request_id = $request['requestId'];

if(isset($access_token) && isset($request_id) && isset($intent)) {
	
	$sql = "SELECT * FROM oauth_tokens WHERE access_token='$access_token' ";
        $count = $db->num_rows($sql);
	if($count == 1) {

		$results = $db->select($sql);
		$user_id = $results[0]['user_id'];
		
		if($intent == "action.devices.SYNC"){
			$response = '{
			"requestId": "'.$request_id.'",
			"payload": {
			"agentUserId": "'.$user_id.'",
			"devices": [';
		
			$sql = "SELECT devices.id, devices.name, devices.nickname, device_types.type, device_types.traits FROM devices JOIN 
			oauth_tokens ON oauth_tokens.user_id=devices.user_id JOIN device_types ON device_types.id=devices.device_type_id 
			WHERE oauth_tokens.access_token='$access_token' AND devices.status=1 ";
			$results = $db->select($sql);
			if($results){
				$i=0;
				foreach ($results as $result) {
					if($i>0) $response .= ',';
					$response .= '{
						"id": "'.md5($result['id']).'",
						"type": "action.devices.types.'.$result['type'].'",
						"traits": [
							'.$result['traits'].'
						],
						"name": {
							"defaultNames": ["POWER '.$result['id'].'"],
							"name": "'.$result['name'].'",
							"nicknames": ["'.$result['nickname'].'"]
						},
						"willReportState": true
					}';
				$i++;
				}
			}		

			$response .= ']
				}
			}';

			echo $response;
		}
		elseif($intent == "action.devices.QUERY") {

			$response = '{
				"requestId": "'.$request_id.'",
				"payload": {
					"devices": { ';
			
					$devices = $request['inputs'][0]['payload']['devices'];
					$i=0;
					foreach ($devices as $device) {
						$sql = "SELECT * FROM `devices` WHERE MD5(`id`)='".$device['id']."' AND `user_id`='$user_id' ";
						$count = $db->num_rows($sql);
						if($count == 1){
							$results = $db->select($sql);
							$on = $results[0]['is_on']==1 ? 'true' : 'false';
							$online = $results[0]['is_online']==1 ? 'true' : 'false';
							if($i>0) $response .= ',';
							$response .= '"'.$device['id'].'": {
									"on": '.$on.',
									"online": '.$online.'
								}'; 
						}

						$i++;
					}

			$response .= '}
				}
			}';

			echo $response;

		}
		elseif($intent == "action.devices.EXECUTE"){

$myfile = fopen("request.txt", "a");
fwrite($myfile, json_encode($request)."\n");
fclose($myfile);
			$response = '{
				  "requestId": "'.$request_id.'",
				  "payload": {
				    "commands": [';
							
				$devices = $request['inputs'][0]['payload']['commands'][0]['devices'];
				$execution = $request['inputs'][0]['payload']['commands'][0]['execution'];

				$i=0;
				foreach ($devices as $device) {
					$sql = "SELECT * FROM `devices` WHERE MD5(`id`)='".$device['id']."' AND `user_id`='$user_id' ";
					$count = $db->num_rows($sql);
					if($count == 1){

						$change_status = $execution[0]['params']['on']==true ? 1 : 0;
						$sql_c = "UPDATE devices SET is_on='$change_status' WHERE MD5(`id`)='".$device['id']."' AND `user_id`='$user_id' ";
						$db->query($sql_c);

						$results = $db->select($sql);
						$on = $results[0]['is_on']==1 ? 'true' : 'false';
						$online = $results[0]['is_online']==1 ? 'true' : 'false';
						if($i>0) $response .= ',';
						$response .= '{
						      "ids": ["'.$device['id'].'"],
						      "status": "SUCCESS",
						      "states": {
						        "on": '.$on.',
						        "online": '.$online.'
						      }
					    	}';

						// Send to NodeJS Broadcaster
						$sqlk = "SELECT api_key FROM oauth_tokens WHERE user_id='$user_id' ";
						$resultsk = $db->select($sqlk);
						if($resultsk && isset($resultsk[0]['api_key'])) {
							$PowerState = $results[0]['is_on']==1 ? "ON" : "OFF";
							$arr = array(
								"api_key" => $resultsk[0]['api_key'],
								"deviceId" => $device['id'], 
								"action" => "setPowerState", 
								"value" => $PowerState
							);

							$ch = curl_init(); 
							curl_setopt($ch, CURLOPT_URL, 'http://localhost');
							curl_setopt($ch, CURLOPT_PORT, 8000);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
							curl_exec($ch);
						}
					}

					$i++;
				}

				$response .= ']
				  }
				}';

			echo $response;		
			
		}
	}
	else {
		$response = '{
			"requestId": "'.$request_id.'",
			"payload": {
				"errorCode": "401",
				"debugString": "Invalid access token (Authorization Code)"
			}
		}';

		echo $response;
	}
}



$myfile = fopen("request.txt", "a");
fwrite($myfile, json_encode($request)."\n");
fclose($myfile);


$myfile = fopen("response.txt", "a");
fwrite($myfile, $response."\n");
fclose($myfile);

/*
echo '{
  "requestId": "ff36a3cc-ec34-11e6-b1a0-64510650abcf",
  "payload": {
    "agentUserId": "1836.15267389",
    "devices": [{
      "id": "123",
      "type": "action.devices.types.OUTLET",
      "traits": [
        "action.devices.traits.OnOff"
      ],
      "name": {
        "defaultNames": ["My Outlet 1234"],
        "name": "Night light",
        "nicknames": ["wall plug"]
      },
      "willReportState": false,
      "roomHint": "kitchen",
      "deviceInfo": {
        "manufacturer": "lights-out-inc",
        "model": "hs1234",
        "hwVersion": "3.2",
        "swVersion": "11.4"
      },
      "customData": {
        "fooValue": 74,
        "barValue": true,
        "bazValue": "foo"
      }
    },{
      "id": "456",
      "type": "action.devices.types.LIGHT",
        "traits": [
          "action.devices.traits.OnOff", "action.devices.traits.Brightness",
          "action.devices.traits.ColorTemperature",
          "action.devices.traits.ColorSpectrum"
        ],
        "name": {
          "defaultNames": ["lights out inc. bulb A19 color hyperglow"],
          "name": "lamp1",
          "nicknames": ["reading lamp"]
        },
        "willReportState": false,
        "roomHint": "office",
        "attributes": {
          "temperatureMinK": 2000,
          "temperatureMaxK": 6500
        },
        "deviceInfo": {
          "manufacturer": "lights out inc.",
          "model": "hg11",
          "hwVersion": "1.2",
          "swVersion": "5.4"
        },
        "customData": {
          "fooValue": 12,
          "barValue": false,
          "bazValue": "bar"
        }
      }]
  }
}';
*/
?>
