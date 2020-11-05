<?php require_once('../includes/header.php'); ?>
<body>
  <div id="recover" class="container" mt-5>
    <form method="POST"  >
          <h1 style="text-align: center;">Recover Password</h1>
          <!-- <hr> -->
          <?php recover_password();
                display_message(); ?>
          <label for="useremail"><b>User email</b></label>
          <input type="text" placeholder="Enter user email" name="UserEmail" required>
          <input type="hidden" name="token" value="<?php echo Token_Generator(); ?>">

          <div class="clearfix">
            <button type="button"  class="cancelbtn">Cancel</button>
            <button type="submit" class="signupbtn">Send Password</button>
          </div>
      </form>
</div>
<!---more things to be added here----- !>
<?php require_once('../includes/footer.php') ?>