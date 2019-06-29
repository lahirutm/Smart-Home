<?php
require('send_email.php');
ini_set('display_errors', '1');
error_reporting(E_ALL);

$to = "lahirutm@gmail.com";
$from = "webservicelk.iot@gmail.com";
$from_name = "WebserviceLK - IoT";
$subject = "Sign Up - Verification";
$body = "Verification Code:".time();
if(smtpmailer($to, $subject, $body)) {
	echo "Email Sent !";
}
else echo "Email Not sent !";
?>
