<?php
require('../class.db.php');
require('../session.php');

if(isset($_POST['id']) && $_POST['id']>0){
	$sql = "SELECT * FROM devices WHERE id='".$_POST['id']."' ";
	$results = $db->select($sql);
	if($results){
		echo "success^".$results[0]['name']."^".$results[0]['nickname']."^".$results[0]['device_type_id'];
	}
}
?>
