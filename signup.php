<?php
require('class.db.php');
require('send_email.php');

if(isset($_POST['submit'])){
        if(isset($_POST['email']) && !empty($_POST['email'])){
                if(isset($_POST['password']) && !empty($_POST['password'])){
			if(isset($_POST['name']) && !empty($_POST['name'])){
                        	$sql = "SELECT * FROM users WHERE email='".$db->escape($_POST['email'])."'";
                        	$count = $db->num_rows($sql);
                        	if($count == 0){
                                	if($_POST['cpassword'] == $_POST['password']){
                                        	$sql = "INSERT INTO users (`user_name`,`password`,`email`,`created_at`,`status`) VALUES 
						('".$db->escape($_POST['name'])."','".md5($db->escape($_POST['password']))."',
						'".$db->escape($_POST['email'])."',NOW(),0) ";
						$insert_id = $db->insert($sql);
						if($insert_id>0) {
							$api_key = md5("webservice.lk".$insert_id);
							$client_id = md5($insert_id.time());
							$client_secret = md5("webservice.lk".time());
							
							$sql = "INSERT INTO oauth_tokens (`user_id`,`api_key`,`client_id`,`client_secret`) 
							VALUES ('$insert_id','$api_key','$client_id','$client_secret')";
							$in_id = $db->insert($sql);
							if($in_id>0){
								
								$to = $db->escape($_POST['email']);
								$subject = "WebserviceLK-IoT | Sign Up Verification";
								$body = "Hello ".$db->escape($_POST['name'])."<br/><br/>";
								$body .= "Please click below link to verify your account at WebserviceLK-IoT <br/>";
								$body .= "<a href='https://webservice.lk/iot/login?vcode=".md5($insert_id)."&email=".$to."'>";
								$body .= "https://webservice.lk/iot/login?vcode=".md5($insert_id)."&email=".$to;
								$body .= "</a><br/><br/>";
								$body .= "Thank You";
								smtpmailer($to, $subject, $body);
								
								$msg = "You have successfully signed up with us. Please check your email for the verification link. Thank You.";
							}
							else {
								$sql = "DELETE FROM users WHERE id='$insert_id'";
								$db->query($sql);
								$errmsg = "Error occurred while proccessing request. Please try again later !";
							}
						}
						else $errmsg = "Failed to sign up with your information. ! ".$db->error();
                                	}
                                	else $errmsg = "Password confirmation failed (Password & Re-Type Password is Different) !";
                        	}
                        	else $errmsg = "Email address already exists (Already registered) !";
			}
			else $errmsg = "Your name required !";
                }
                else $errmsg = "Password required !";
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
                                                IoT Sign Up
                                        </span>
					
					<div class="wrap-input100 validate-input m-b-35" data-validate = "Enter Your Name">
                                                <input class="input100" type="text" name="name" required="required">
                                                <span class="focus-input100" data-placeholder="Your Name"></span>
                                        </div>
					
                                        <div class="wrap-input100 validate-input m-b-35" data-validate = "Enter Email Address">
                                                <input class="input100" type="email" name="email" required="required">
                                                <span class="focus-input100" data-placeholder="Email Address"></span>
                                        </div>

                                        <div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
                                                <input class="input100" type="password" name="password" required="required">
                                                <span class="focus-input100" data-placeholder="Password"></span>
                                        </div>

					<div class="wrap-input100 validate-input m-b-50" data-validate="Re-Type password">
                                                <input class="input100" type="password" name="cpassword" required="required">
                                                <span class="focus-input100" data-placeholder="Re-Type Password"></span>
                                        </div>

                                        <div class="container-login100-form-btn">
                                                <button class="login100-form-btn" type="submit" name="submit">
                                                        Sign Up
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
