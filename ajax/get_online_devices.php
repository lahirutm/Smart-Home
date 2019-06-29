<?php
require('../class.db.php');
require('../session.php');

$sql = "SELECT * FROM devices WHERE user_id='$iot_user_id' AND status=1 AND is_online=1 ";
$count = $db->num_rows($sql);
echo $count;
?>
