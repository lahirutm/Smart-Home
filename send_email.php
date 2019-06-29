<?php
require('PHPMailer/PHPMailerAutoload.php');

function smtpmailer($to, $subject, $body) { 
 global $error;
 $mail = new PHPMailer();  // create a new object
 $mail->IsSMTP(); // enable SMTP
 $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
 $mail->SMTPAuth = true;  // authentication enabled
 $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
 $mail->Host = 'mail.webservice.lk';
 $mail->Port = 465; 
 $mail->Username = "iot@webservice.lk";  
 $mail->Password = "1234567890";           
 $mail->SetFrom('iot@webservice.lk', 'WebserviceLK-IoT');
 $mail->Subject = $subject;
 $mail->AltBody = "Use html compatible email client to view this email !";
 $mail->msgHTML($body);
 $mail->AddAddress($to);
 $mail->AddCC('webservicelk.iot@gmail.com');
 if(!$mail->Send()) {
 	$error = 'Mail error: '.$mail->ErrorInfo; 
 	return false;
 } else {
 	$error = 'Message sent!';
 	return true;
 }
}
?>
