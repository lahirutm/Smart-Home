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
	<h1>How To</h1>
	<p>
	WebserviceLK-IoT helps you to integrate your existing devices (NodeMCU-ESP8226, Arduino, ...) with Google Home for FREE !
	<hr/>
	</p>
	</div>	
    </div>
    <div class="row">
        <div class="col-md-12">
	<p>
	<img src="images/IoT-integration.jpg" height="500" />
	</p>
        
     	<br/>
	<h4>Step1</h4>
	Create a webservice.lk account if you do not have one yet by simply clicking on <a href="signup" style="color:green;">Sign Up</a>.
	<br/><br/>
	<h4>Step2</h4>
    	Go to <a href="https://console.actions.google.com/" target="_blank">https://console.actions.google.com/</a> Click Add.
	<br/><br/>
        <h4>Step3</h4>
	Name the project. Eg:- WebserviceLK. It Takes few seconds to create the project.
	<br/><br/>
        <h4>Step4</h4>
	Now you are on the home screen. Click Home Control. Click Name your Smart Home Action.
	<br/><br/>
        <h4>Step5</h4>
	Name it WebserviceLK. Click Save (in the upper right corner).
	<br/><br/>
        <h4>Step6</h4>
	Click "Account Linking". Then Select No.
	<br/><br/>
        <h4>Step7</h4>
	Select OAuth, then Select Autherization Code.
	<br/><br/>
        <h4>Step8</h4>
	Now Copy Client ID and Client Secret from WebserviceLK website you have already created.
	<br/><br/>
        <h4>Step9</h4>
	Paste it accordingly and click next.
	<br/><br/>
        <h4>Step10</h4>
	Authorization URL: <span style="color:green;">https://webservice.lk/iot/oauth</span><br/>
	Token URL: <span style="color:green;">https://webservice.lk/iot/token</span>
	<br/><br/>
        <h4>Step11</h4>
	Now Enter user (Just "user"). Click next.
	<br/><br/>
        <h4>Step12</h4>
	Enter test. Click Save.
	<br/><br/>
        <h4>Step13</h4>
	Now, Select Actions under Build from left side menu. Click Add your first action.
	<br/><br/>
        <h4>Step14</h4>
	Enter: <span style="color:green;">https://webservice.lk/iot/api/v1/googlehome/event</span> and Click Done. Then Click Test. This will deploy the thing and now you are ready to start testing.
	<br/><br/>
        <h4>Step15</h4>
	Now you need to get control of your devices through Google Asistance by talking to it. So, add few devices in to your webservice.lk account by visiting it.
	<br/><br/>
        <h4>Step16</h4>
	Open your Google Home mobile app in your android phone. (If you do not have it you must install it.)
	<br/><br/>
        <h4>Step17</h4>
	Go to Home. (Or you can go to Settings)
	<br/><br/>
        <h4>Step18</h4>
	Find Home Control in Left side panel.
	<br/><br/>
        <h4>Step19</h4>
	Now you must see WebserviceLK (Or whatever name you have given, when creating it). Click on it. Now it will open a login page to login. Use your webservice.lk credentials to log in. Now you will see a message with link successful.
	<br/><br/>
        <h4>Step20</h4>
	Now you will see your devices you have created in your webservice.lk account.
	<br/>
	</br>
	<h3>You have successfully done the application setup.</h3>
        </div>
	<div class="col-md-12">
	<hr/>
	Now you can programme your NodeMCU or Arduino (Or any development board can connect to Internet) to connect with webservice.lk and then you can controll it through Google Home Assitance with voice commands.
	<img src="images/webservicelk_arduino.png" height="500" />
	<br/>
	<img src="images/webservicelk-nodemcu.png" height="500" />
	<br/>
	<br/>
	Download sample source code from Github: <a style="color:green;" href="https://github.com/lahirutm/WebserviceLK-IoT" target="_blank">https://github.com/lahirutm/WebserviceLK-IoT</a>
	<br/>
	<br/>
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
