<?php include 'includes/session.php'; ?>
<?php
  if(!isset($_GET['code']) OR !isset($_GET['user'])){
    header('location: index.php');
    exit(); 
  }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  	<?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger text-center'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }
    ?>
  	<div class="login-box-body">
    	<p class="login-box-msg">Enter new password</p>

    	<form action="password_new.php?code=<?php echo $_GET['code']; ?>&user=<?php echo $_GET['user']; ?>" method="POST">
          <div class="form-group has-feedback">
            	<input id="reset_password" type="password" class="form-control" name="password" placeholder="New password" required>
            	<span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <div style="margin-top:6px">
                  <label style="font-weight:400"><input type="checkbox" class="toggle-pass" data-target="#reset_password"> Show password</label>
                </div>
          </div>
              <div class="form-group has-feedback">
                <input id="reset_repassword" type="password" class="form-control" name="repassword" placeholder="Re-type password" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                <div style="margin-top:6px">
                  <label style="font-weight:400"><input type="checkbox" class="toggle-pass" data-target="#reset_repassword"> Show password</label>
                </div>
              </div>
      		<div class="row">
    			<div class="col-xs-4">
          			<button type="submit" class="btn btn-primary btn-block btn-flat" name="reset"><i class="fa fa-check-square-o"></i> Reset</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>