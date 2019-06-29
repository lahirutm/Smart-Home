<?php
require('class.db.php');
session_start();

if(isset($_POST['submit'])){
        if(isset($_POST['email']) && !empty($_POST['email'])){
                if(isset($_POST['password']) && !empty($_POST['password'])){
                        $sql = "SELECT * FROM users WHERE email='".$db->escape($_POST['email'])."' AND status=1";
                        $results = $db->select($sql);
                        if($results){
                                if($results[0]['password'] == md5($db->escape($_POST['password']))){
                                        $_SESSION['iot_user_id'] = $results[0]['id'];
					header('location:index');
                                }
                                else $errmsg = "Invalid Email or Password !";
                        }
                        else $errmsg = "Failed to authenticate ! ".$db->error();
                }
                else $errmsg = "Password required !";
        }
        else $errmsg = "Email address required !";
}

if(isset($_GET['vcode']) && isset($_GET['email'])){
	$sql = "SELECT * FROM users WHERE MD5(`id`)='".$db->escape($_GET['vcode'])."' AND email='".$db->escape($_GET['email'])."'";
	$results = $db->select($sql);
	if($results && isset($results[0])){
		if($results[0]['status']==1){
			$msg = "You have already verified your account, You can login now !";
		}
		else {
			$sql = "UPDATE users SET status=1 WHERE MD5(`id`)='".$db->escape($_GET['vcode'])."'";
			if($db->query($sql)){
				$msg = "You have successfully verified your account, You can login now.";
			}
			else $errmsg = "Failed to verify your account ! ".$db->error();
		}
	}
	else $errmsg = "Invalid verification code, Please follow the url link we sent to your email !";
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
                                                IoT Login
                                        </span>

                                        <span class="login100-form-avatar">
                                                <img src="images/avatar-01.png" alt="AVATAR">
                                        </span>

                                        <div class="wrap-input100 validate-input m-b-35" data-validate = "Enter Email Address">
                                                <input class="input100" type="email" name="email" required="required">
                                                <span class="focus-input100" data-placeholder="Email Address"></span>
                                        </div>

                                        <div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
                                                <input class="input100" type="password" name="password" required="required">
                                                <span class="focus-input100" data-placeholder="Password"></span>
                                        </div>

                                        <div class="container-login100-form-btn">
                                                <button class="login100-form-btn" type="submit" name="submit">
                                                        Login
                                                </button>
                                        </div>
					<br/>
					<div><a href="forgot">Forgot Password ?</a></div>
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
