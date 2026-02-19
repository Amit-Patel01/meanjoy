<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page modern-login-page">
<div class="login-box modern-login-container">
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
  	<div class="login-box-body modern-login-card">
    	<div class="modern-login-header">
    		<h2>Welcome Back!</h2>
    	<p class="login-box-msg">Sign in to start your session</p>
    	</div>

    	<form action="verify.php" method="POST" class="modern-login-form">
      		<div class="form-group has-feedback modern-form-group">
        		<input type="email" class="form-control modern-input" name="email" placeholder="Email" required>
        		<span class="glyphicon glyphicon-envelope form-control-feedback modern-icon"></span>
      		</div>
          <div class="form-group has-feedback modern-form-group">
            <input id="login_password" type="password" class="form-control modern-input" name="password" placeholder="Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback modern-icon"></span>
            <div style="margin-top:6px">
              <label style="font-weight:400"><input type="checkbox" class="toggle-pass" data-target="#login_password"> Show password</label>
            </div>
          </div>
      		<div class="row">
    			<div class="col-xs-12">
          			<button type="submit" class="btn btn-primary btn-block btn-flat modern-login-btn" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
        		</div>
      		</div>
    	</form>
      <div class="modern-login-links">
      	<a href="password_forgot.php" class="modern-link">I forgot my password</a><br>
      	<a href="signup.php" class="text-center modern-link">Register a new membership</a><br>
      	<a href="index.php" class="modern-link"><i class="fa fa-home"></i> Home</a>
      </div>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>