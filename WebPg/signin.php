<?php require_once('../functions/config.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rest-Save</title>
    <link type="text/css" rel="stylesheet" href="../CSS/styles.css">
    
  </head>

<body>
  <header style="height: 0;">
  <!--
    <video autoplay muted loop style="position: fixed; right: 0; bottom: 0;
    min-width: 100%; min-height: 100%; z-index:-1;">
        <source  src="../RestSaveImages/PagesImg/lake.mp4">     
    </video>
-->
        <nav class="navBar" id="navbar">
                <a class="navLink navLogo" href="."><img src="../RestSaveImages/FrontImages/logo.png" alt="Rest-Save Logo"
                            title="Rest-Save"></img></a>
                            <a class="navLink navLinkText" href="WebPg/Posting.html">Posting</a>
                <a class="navLink navLinkText" href="WebPg/LocationMap.html">Location</a>
                <a class="navLink navLinkText" href="WebPg/signin.php">Sign In</a>
                <a href="#contactUs"><button class="contactUsButton"><p>Contact Us</p></button></a>
                
            </nav>
         

    </header>
    <?php  display_message(); ?>
    <div id="signin" class="container">
      <form class="modal-content" method="post">
        
          <?php login_validation();
                display_message(); ?>
          <h1><br><br>Restsave Member Sign in<br><br></h1>
          <!-- <hr> -->
          <label for="useremail"><b>User email</b></label>
          <input type="text" placeholder="Enter user email" name="Uemail" required>
    
          <label for="password"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="Upass" required>
          <p>Don't have a account? <a href="signup.php" style="text-decoration:underline;">Sign up</a> first!</a></p>

          <div class="clearfix">
            <button type="button"  class="cancelbtn">Cancel</button>
            <button type="submit" class="signupbtn"><b>Sign in</b></button>
          </div>
          <!-- <hr> -->
          <input type="checkbox" name="remember"><span> Remember Me</span>
          <a href="recover.php" class="floatright"><u>Forget Password?</u></a>
        
      </form>
      </div>

<?php require_once('../includes/footer.php') ?>