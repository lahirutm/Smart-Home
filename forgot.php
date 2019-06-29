<?php
require('class.db.php');
require('send_email.php');

if(isset($_POST['submit'])){
        if(isset($_POST['email']) && !empty($_POST['email'])){
        	$sql = "SELECT * FROM users WHERE email='".$db->escape($_POST['email'])."'";
		$count = $db->num_rows($sql);
		if($count==1){
			$new_password = substr(md5(time()),0,8);
			$sql = "UPDATE users SET password='".md5($new_password)."' WHERE email='".$db->escape($_POST['email'])."' ";
			if($db->query($sql)){
				$to = $db->escape($_POST['email']);
              			$subject = "WebserviceLK-IoT | Password Reset";
              			$body = "Hello <br/><br/>";
             			$body .= "Please use below new password according to your password reset request at WebserviceLK-IoT. <br/>";
              			$body .= "<h4>".$new_password."</h4>";
              			$body .= "<a href='https://webservice.lk/iot/'>";
              			$body .= "<h4>Login to WebserviceLK-IoT</h4>";
              			$body .= "</a><br/><br/>";
              			$body .= "Thank You";
              			smtpmailer($to, $subject, $body);				
				$msg = "Password has been reset and sent to ".$_POST['email'].". Please check your email !";
			}
			else $errmsg = "Failed to reset password ! ".$db->error();
		}
		else $errmsg = "No users associated with this email, Please sign up ! ";
        }
        else $errmsg = "Email address required !";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>IoT | webservice.lk</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
        <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->  
        <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
<?php include('top_navigation.php');?>        
<div class="container">
        <div class="row">
                <div class="col-md-12">
                <?php if(isset($errmsg) && !empty($errmsg)) { ?>
                       <div class="alert alert-danger"><?php echo $errmsg;?></div>
                <?php } ?>

                <?php if(isset($msg) && !empty($msg)) { ?>
                      <div class="alert alert-success"><?php echo $msg;?></div>
                <?php } ?>
                </div>
        </div>

        <div class="limiter">
                <div class="container-login100">
                        <div class="wrap-login100 p-b-20">
                                <form class="login100-form validate-form" method="post">
                                        <span class="login100-form-title p-b-70">
                                                Password Reset
                                        </span>

                                        <div class="wrap-input100 validate-input m-b-35" data-validate = "Enter Email Address">
                                                <input class="input100" type="email" name="email" required="required">
                                                <span class="focus-input100" data-placeholder="Email Address"></span>
                                        </div>

                                        <div class="container-login100-form-btn">
                                                <button class="login100-form-btn" type="submit" name="submit">
                                                        Reset Password
                                                </button>
                                        </div>
                                </form>
                        </div>
                </div>
        </div>
</div>        
<!--===============================================================================================-->
        <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
        <script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
        <script src="vendor/bootstrap/js/popper.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
        <script src="js/main.js"></script>

</body>
</html>
