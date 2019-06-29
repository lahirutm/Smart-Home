<?php
require('/home/webserv9/public_html/iot/class.db.php');

$sql = "UPDATE devices SET is_online=0 WHERE last_online<date_sub(now(),interval 10 minute)";
$db->query($sql);
exit();
?>
