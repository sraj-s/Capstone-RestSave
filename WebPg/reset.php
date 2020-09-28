<?php require_once('../includes/header.php')?>
<body>
  <div id="recover" class="container" mt-5 >
    <form method="POST">
          <h1>Reset Password</h1>
          <!-- <hr> -->
          <?php reset_password(); 
                display_message(); ?>
          <label for="psw"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="password" required>
      
          <label for="psw-repeat"><b>Repeat Password</b></label>
          <input type="password" placeholder="Repeat Password" name="confirm-password" required>

          <input type="hidden" name="token" value="<?php echo Token_Generator();?>">

          <div class="clearfix">
            <button type="button"  class="cancelbtn">Cancel</button>
            <button type="submit" class="signupbtn">Send Password</button>
          </div>
      </form>
    </div>
    <?php require_once('../includes/footer.php') ?>
