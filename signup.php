<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }

  if(isset($_SESSION['captcha'])){
    $now = time();
    if($now >= $_SESSION['captcha']){
      unset($_SESSION['captcha']);
    }
  }

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition register-page modern-login-page">
<div class="register-box modern-login-container">
  	<?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger text-center modern-alert'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }

      if(isset($_SESSION['success'])){
        echo "
          <div class='callout callout-success text-center modern-alert'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
  	<div class="register-box-body modern-login-card">
    	<div class="modern-login-header">
    		<h2>Create Account</h2>
    	<p class="login-box-msg">Register a new membership</p>
    	</div>

    	<form action="register.php" method="POST" class="modern-login-form">
          <div class="form-group has-feedback modern-form-group">
            <input type="text" class="form-control modern-input" name="firstname" placeholder="Firstname" value="<?php echo (isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : '' ?>" required>
            <span class="glyphicon glyphicon-user form-control-feedback modern-icon"></span>
          </div>
          <div class="form-group has-feedback modern-form-group">
            <input type="text" class="form-control modern-input" name="lastname" placeholder="Lastname" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>"  required>
            <span class="glyphicon glyphicon-user form-control-feedback modern-icon"></span>
          </div>
      		<div class="form-group has-feedback modern-form-group">
        		<input type="email" class="form-control modern-input" name="email" placeholder="Email" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
        		<span class="glyphicon glyphicon-envelope form-control-feedback modern-icon"></span>
      		</div>
          <div class="form-group has-feedback modern-form-group">
            <input id="signup_password" type="password" class="form-control modern-input" name="password" placeholder="Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback modern-icon"></span>
            <div style="margin-top:6px">
              <label style="font-weight:400"><input type="checkbox" class="toggle-pass" data-target="#signup_password"> Show password</label>
            </div>
          </div>
          <div class="form-group has-feedback modern-form-group">
            <input id="signup_repassword" type="password" class="form-control modern-input" name="repassword" placeholder="Retype password" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback modern-icon"></span>
            <div style="margin-top:6px">
              <label style="font-weight:400"><input type="checkbox" class="toggle-pass" data-target="#signup_repassword"> Show password</label>
            </div>
          </div>
          <?php // reCAPTCHA removed: signup proceeds without client-side captcha ?>
      		<div class="row">
    			<div class="col-xs-12">
          			<button type="submit" class="btn btn-primary btn-block btn-flat modern-login-btn" name="signup"><i class="fa fa-pencil"></i> Sign Up</button>
        		</div>
      		</div>
    	</form>
      <div class="modern-login-links">
      	<a href="login.php" class="modern-link">I already have a membership</a><br>
      	<a href="index.php" class="modern-link"><i class="fa fa-home"></i> Home</a>
      </div>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>