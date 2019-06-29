<?php
require('class.db.php');
require('session.php');

unset($_SESSION['iot_user_id']);
header('location:'.$db->base_url);
?>
