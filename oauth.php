<?php
require('class.db.php');
session_start();

if(isset($_GET['response_type'])) $response_type = $_GET['response_type'];
if(isset($_GET['client_id'])) $client_id = $_GET['client_id'];
if(isset($_GET['redirect_uri'])) $redirect_uri = $_GET['redirect_uri'];
if(isset($_GET['scope'])) $scope = $_GET['scope'];
if(isset($_GET['state'])) $state = $_GET['state'];

if(isset($response_type) && isset($client_id) && isset($redirect_uri) && isset($scope) && isset($state)){
        $_SESSION['response_type'] = $response_type;
        $_SESSION['client_id'] = $client_id;
        $_SESSION['redirect_uri'] = $redirect_uri;
        $_SESSION['scope'] = $scope;
        $_SESSION['state'] = $state;
}

if(isset($_POST['submit'])){
        if(isset($_POST['email']) && !empty($_POST['email'])){
                if(isset($_POST['password']) && !empty($_POST['password'])){
                        $sql = "SELECT * FROM users WHERE email='".$db->escape($_POST['email'])."' AND status=1";
                        $results = $db->select($sql);
                        if($results){
                                if($results[0]['password'] == md5($db->escape($_POST['password']))){
                                        $_SESSION['user_id'] = $results[0]['id'];
                                }
                                else $errmsg = "Invalid Email or Password !";
                        }
                        else $errmsg = "Failed to authenticate ! ".$db->error();
                }
                else $errmsg = "Password required !";
        }
        else $errmsg = "Email address required !";
}


if(isset($_SESSION['user_id']) && isset($_SESSION['response_type']) && isset($_SESSION['client_id']) 
&& isset($_SESSION['redirect_uri']) && isset($_SESSION['scope']) && isset($_SESSION['state']) ){

        $response_type = $_SESSION['response_type'];
        $client_id = $_SESSION['client_id'];
        $redirect_uri = $_SESSION['redirect_uri'];
        $scope = $_SESSION['scope'];
        $state = $_SESSION['state'];

        if($redirect_uri=="https://oauth-redirect.googleusercontent.com/r/".$db->project_id){
                $sql = "SELECT * FROM oauth_tokens WHERE client_id='$client_id' ";
                $count = $db->num_rows($sql);
                if($count>0){
                        $auth_code = md5(time());
                        $sql = "UPDATE oauth_tokens SET auth_code='$auth_code' WHERE client_id='$client_id'";
                        if($db->query($sql)){

                                $myfile = fopen("oauth_request.txt", "a");
                                fwrite($myfile, json_encode($_GET)."\n");
                                fclose($myfile);

                                $url = "https://oauth-redirect.googleusercontent.com/r/".$db->project_id."?code=".$auth_code."&state=".$state;

                                $myfile = fopen("oauth_redirect.txt", "a");
                                fwrite($myfile, $url."\n");
                                fclose($myfile);

                                header('location:'.$url);
                        }
                }
        }
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
					<?php if(isset($errmsg) && !empty($errmsg)) { ?>
						<div style="color:red; font-size:10px;"><?php echo $errmsg;?></div>
					<?php } ?>
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
                                </form>
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
