<header class="main-header modern-navbar">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <a href="index.php" class="navbar-brand modern-brand">
        <img src="images/logo.jpeg" alt="Logo" class="brand-logo">
          <span class="brand-text"><b></b></span>
        </a>
        <button type="button" class="navbar-toggle collapsed modern-toggle" data-toggle="collapse" data-target="#navbar-collapse">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <ul class="nav navbar-nav modern-nav">
          <li><a href="index.php" class="nav-link-modern btn-nav-link">HOME</a></li>
          <li><a href="" class="nav-link-modern btn-nav-link">ABOUT US</a></li>
          <li><a href="" class="nav-link-modern btn-nav-link">CONTACT US</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle nav-link-modern btn-nav-link" data-toggle="dropdown">CATEGORY <span class="caret"></span></a>
            <ul class="dropdown-menu modern-dropdown" role="menu">
              <?php
             
                $conn = $pdo->open();
                try{
                  $stmt = $conn->prepare("SELECT * FROM category");
                  $stmt->execute();
                  foreach($stmt as $row){
                    echo "
                      <li><a href='category.php?category=".$row['cat_slug']."'>".$row['name']."</a></li>
                    ";                  
                  }
                }
                catch(PDOException $e){
                  echo "There is some problem in connection: " . $e->getMessage();
                }

                $pdo->close();

              ?>
            </ul>
          </li>
          <li>
            <!-- Cart link - opens slider -->
            <a href="#" class="nav-link-modern btn-nav-link" id="cartSliderBtn">
              CART
            </a>
          </li>
          <?php
            if(isset($_SESSION['user'])){
              echo "
                <li><a href='profile.php' class='nav-link-modern btn-nav-link'>PROFILE</a></li>
                <li><a href='logout.php' class='nav-link-modern btn-nav-link'>LOGOUT</a></li>
              ";
            }
            else{
              echo "
                <li><a href='login.php' class='nav-link-modern btn-nav-link'>LOGIN</a></li>
                <li><a href='signup.php' class='nav-link-modern btn-nav-link'>SIGNUP</a></li>
              ";
            }
          ?>
        </ul>
        <form method="POST" class="navbar-form navbar-left modern-search" action="search.php">
          <div class="input-group modern-input-group">
              <span class="input-group-btn modern-search-icon-btn" id="searchIconBtn">
                  <button type="button" class="btn modern-search-icon-only"><i class="fa fa-search"></i></button>
              </span>
              <input type="text" class="form-control modern-search-input" id="navbar-search-input" name="keyword" placeholder="Search products..." required style="display:none;">
              <span class="input-group-btn" id="searchBtn" style="display:none;">
                  <button type="submit" class="btn modern-search-btn"><i class="fa fa-search"></i> </button>
              </span>
          </div>
        </form>
      </div>
      <!-- /.navbar-collapse -->
    </div>
  </nav>
</header>