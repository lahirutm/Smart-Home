<?php
require('../class.db.php');
require('../session.php');


 
        if(isset($_POST['status']) && $_POST['status']!=='' && $_POST['status']>=0){
            if(isset($_POST['device_id']) && $_POST['device_id']>0){

		$_POST['status'] == 1 ? $new_status = 0 : $new_status = 1;

            	$sql = "UPDATE devices SET `is_on`='$new_status' WHERE id='".$db->escape($_POST['device_id'])."' ";
            	if($db->query($sql)===true){
			// Send to NodeJS Broadcaster
			$sqlk = "SELECT api_key FROM oauth_tokens WHERE user_id='$iot_user_id' ";
			$resultsk = $db->select($sqlk);
			if(isset($resultsk[0]['api_key'])) {
				$PowerState = $new_status ==1 ? "ON" : "OFF";
				$arr = array(
					"api_key" => $resultsk[0]['api_key'],
					"deviceId" => md5($_POST['device_id']),
					"action" => "setPowerState",
					"value" => $PowerState
				);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://localhost');
				curl_setopt($ch, CURLOPT_PORT, 8000);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
				curl_exec($ch);
				curl_close($ch);
			}

            		echo "success^Device status successfully updated !";
            	}
            	else echo "unsuccess^Device status update failed ! ".$db->error();
            }
	    else echo "unsuccess^Device id required !";
        }
        else echo "unsuccess^Device status required !";

?>
