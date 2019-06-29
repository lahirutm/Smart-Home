<?php
require('../class.db.php');
require('../session.php');

if(isset($_POST['name']) && !empty($_POST['name'])){
    if(isset($_POST['nickname']) && !empty($_POST['nickname'])){
        if(isset($_POST['type']) && $_POST['type']>0){
            if(isset($_POST['device_id']) && $_POST['device_id']>0){
            	$sql = "UPDATE devices SET `name`='".$db->escape($_POST['name'])."',`nickname`='".$db->escape($_POST['nickname'])."',
            	`device_type_id`='".$db->escape($_POST['type'])."' WHERE id='".$db->escape($_POST['device_id'])."' ";
                $results = $db->query($sql);
            	if($results===true){
            		echo "success^Device successfully saved !";
            	}
            	else echo "unsuccess^Device save failed ! ".$db->error();
            }
            else {
                $sql = "INSERT INTO devices (`name`,`nickname`,`device_type_id`) VALUES ('".$db->escape($_POST['name'])."',
                '".$db->escape($_POST['nickname'])."','".$db->escape($_POST['type'])."') ";
            	$insert_id = $db->insert($sql);
            	if($insert_id>0){
            		echo "success^Device successfully created !";
            	}
            	else echo "unsuccess^Device create failed ! ".$db->error();
            }
        }
        else echo "unsuccess^Type required !";
    }
    else  echo "unsuccess^Nickname required !";
}
else  echo "unsuccess^Name required !";
?>
