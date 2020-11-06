<?php require_once('../includes/header.php')?>



<?php  display_message(); ?>
<div id="signup" class="container">
  <form method="post">
          <?php user_validation();
                display_message(); ?>
          <h1>RestSave Member Sign Up</h1>
          <!-- <hr> -->
          
          <label for="firstname"><b>First Name</b></label>
          <input type="text" placeholder="Enter first name" name="FirstName" required>
          <label for="lastname"><b>Last Name</b></label>
          <input type="text" placeholder="Enter last name" name="LastName" required>

          <label for="username"><b>User Name</b></label>
          <input type="text" placeholder="Enter username" name="UserName" required>

          <label for="email"><b>Email</b></label>
          <input type="email" placeholder="Enter email" name="Email" required>
      
          <label for="password"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="Password" required>
      
          <label for="confirm-password"><b>Repeat Password</b></label>
          <input type="password" placeholder="Repeat Password" name="CPassword" required>
      
      
          <p>By creating an account you agree to our Terms & Privacy.</p>
      
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" class="signupbtn"><b>Sign Up</b></button>
          </div>
        
      </form>
</div>    
          
<?php require_once('../includes/footer.php') ?>
