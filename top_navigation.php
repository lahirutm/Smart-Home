<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
  <a class="navbar-brand text-success" href="index">Webservice.LK - IoT</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!-- <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li> -->
    </ul>

    <a class="nav-link" href="about"><button class="btn">About</button></a>
    <a class="nav-link" href="how_to"><button class="btn">How To</button></a>
<?php if(!isset($_SESSION)) session_start();?>
<?php if(isset($_SESSION['iot_user_id'])){?>
  <a href="logout.php">
         <button class="btn btn-outline-danger my-2 my-sm-0" type="button">Logout</button>
  </a>
<?php } ?>

<?php if(!isset($_SESSION['iot_user_id']) && basename($_SERVER['PHP_SELF'])!="signup.php"){?>
  <a href="signup">
         <button class="btn btn-outline-success my-2 my-sm-0" type="button">Sign Up</button>
  </a>
<?php } ?>

<?php if(basename($_SERVER['PHP_SELF'])=="signup.php" || basename($_SERVER['PHP_SELF'])=="forgot.php"){?>
  <a href="login">
         <button class="btn btn-outline-success my-2 my-sm-0" type="button">Login</button>
  </a>
<?php } ?>
  </div>
</nav>
