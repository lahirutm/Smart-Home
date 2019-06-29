<?php
session_start();
if(isset($_SESSION['iot_user_id']) && !empty($_SESSION['iot_user_id'])){
	$iot_user_id = $_SESSION['iot_user_id'];
}
else {
	header('location:'.$db->base_url.'login');
	exit();
}
?>
