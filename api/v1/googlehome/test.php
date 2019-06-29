<?php
require('../../../class.db.php');
header('Content-Type: application/json');


$auth_code = "83e2ec0541fc809180970e2a38c0ae68";
$user_id = 1;
$request_id = "11231312312";

$json = '{
                        "requestId": "'.$request_id.'",
                        "payload": {
                        "agentUserId": "'.$user_id.'",
                        "devices": [';

                        $sql = "SELECT devices.id, devices.name, devices.nickname, device_types.type, device_types.traits FROM devices JOIN
                        oauth_tokens ON oauth_tokens.user_id=devices.user_id JOIN device_types ON device_types.id=devices.device_type_id
                        WHERE oauth_tokens.auth_code='$auth_code' AND devices.status=1 ";
                        $results = $db->select($sql);
                        if($results){
                                $i=0;
                                foreach ($results as $result) {
                                        if($i>0) $json .= ',';
                                        $json .= '{
                                                "id": "'.md5($result['id']).'",
                                                "type": "action.devices.types.'.$result['type'].'",
                                                "traits": [
                                                        '.$result['traits'].'
                                                ],
                                                "name": {
                                                        "defaultNames": ["POWER OUTLET"],
                                                        "name": "'.$result['name'].'",
                                                        "nicknames": ["'.$result['nickname'].'"]
                                                },
                                                "willReportState": true
                                        }';
                                $i++;
                                }
                        }

                        $json .= ']}
                        }';

echo $json;
?>
