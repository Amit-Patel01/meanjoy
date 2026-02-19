<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <div class="content-wrapper">
    <div class="container">
      <section class="content">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <h1 class="page-header" style="text-align:center; margin-bottom:20px;">Contact Us</h1>

            <?php
              if(isset($_SESSION['error'])){
                echo "<div class='alert alert-danger'>".htmlspecialchars($_SESSION['error'])."</div>";
                unset($_SESSION['error']);
              }
              if(isset($_SESSION['success'])){
                echo "<div class='alert alert-success'>".htmlspecialchars($_SESSION['success'])."</div>";
                unset($_SESSION['success']);
              }
            ?>

            <div class="box box-solid">
              <div class="box-body">
                <form id="contactForm" method="POST" action="contact_send.php">
                  <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                  </div>
                  <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
              </div>
            </div>

          </div>
        </div>
      </section>
    </div>
  </div>

  <?php include 'includes/footer.php'; include 'includes/scripts.php'; ?>

</div>
</body>
</html>
