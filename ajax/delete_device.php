<?php
require('../class.db.php');
require('../session.php');

if(isset($_POST['id']) && $_POST['id']>0){
	$sql = "UPDATE devices SET status=0 WHERE id='".$_POST['id']."' ";
	$results = $db->query($sql);
	if($results){
		echo "success^Device successfully deleted !";
	}
	else echo "unsuccess^Device delete failed ! ".$db->error();
}
?>
