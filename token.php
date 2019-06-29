<?php
header('Content-Type: application/json');
require('class.db.php');

if(isset($_POST['client_secret'])) $client_secret = $_POST['client_secret'];
if(isset($_POST['client_id'])) $client_id = $_POST['client_id'];
if(isset($_POST['grant_type'])) $grant_type = $_POST['grant_type'];
if(isset($_POST['code'])) $code = $_POST['code'];
if(isset($_POST['refresh_token'])) $refresh_token = $_POST['refresh_token'];

$myfile = fopen("token_request.txt", "a");
fwrite($myfile, json_encode($_POST)."\n");
fclose($myfile);

if(isset($client_secret) && isset($client_id) && isset($grant_type) && (isset($code) || isset($refresh_token)) ){

	if($grant_type=="authorization_code"){
		$sql = "SELECT * FROM oauth_tokens WHERE client_id='$client_id' AND client_secret='$client_secret' AND auth_code='$code' ";
		$count = $db->num_rows($sql);
		if($count>0){
			$result = $db->select($sql);
			$arr = array(
				"token_type"=>"Bearer",
				"access_token"=>$result[0]['access_token'],
				"refresh_token"=>$result[0]['refresh_token'],
				"expires_in"=>3600
			);

			echo json_encode($arr);

			$myfile = fopen("token_response.txt", "a");
			fwrite($myfile, json_encode($arr)."\n");
			fclose($myfile);
		}
		else {
			echo json_encode(array("error"=>"invalid_grant"));

			$myfile = fopen("token_response.txt", "a");
                        fwrite($myfile, json_encode(array("error"=>"invalid_grant"))."\n");
                        fclose($myfile);
		}
	}
	else if($grant_type=="refresh_token"){
		$sql = "SELECT * FROM oauth_tokens WHERE client_id='$client_id' AND client_secret='$client_secret' AND refresh_token='$refresh_token' ";
                $count = $db->num_rows($sql);
                if($count>0){
			$access_token = md5(time());
			$sql = "UPDATE oauth_tokens SET access_token='$access_token' WHERE client_id='$client_id' AND client_secret='$client_secret' AND 
			refresh_token='$refresh_token'";
                        $db->query($sql);
                        $arr = array(
                                "token_type"=>"Bearer",
                                "access_token"=>$access_token,
                                "expires_in"=>3600         
                        );

                        echo json_encode($arr);

			$myfile = fopen("token_response.txt", "a");
                        fwrite($myfile, json_encode($arr)."\n");
                        fclose($myfile);
                }
                else {
                        echo json_encode(array("error"=>"invalid_grant"));
			
			$myfile = fopen("token_response.txt", "a");
                        fwrite($myfile, json_encode(array("error"=>"invalid_grant"))."\n");
                        fclose($myfile);

                }
	}
}
?>
