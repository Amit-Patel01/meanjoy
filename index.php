<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	      	<!-- Hero Section -->
	      	<div class="hero-section">
	      		<div class="row">
	      			<div class="col-md-6 col-xs-12">
	      				<div class="hero-content">
	      					<p style="font-size:16px; color:#6b7280; margin-bottom:15px; font-weight:400;">Welcome to</p>
	      					<h1 class="hero-title" style="color:#1f2937; font-size:64px; font-weight:900; line-height:1.1; margin-bottom:25px;">MeanJoy</h1>
	      					<p class="hero-subtitle" style="font-size:15px; color:#4b5563; line-height:1.8; margin-bottom:40px; max-width:550px;">Discover amazing products at unbeatable prices. Shop the latest trends and find everything you need in one place. Quality products, fast delivery, and excellent customer service.</p>
	      					<div class="hero-buttons">
	      						<a href="category.php" class="btn-hero-primary" style="background:#dc2626; padding:16px 40px; border-radius:30px; font-size:16px; font-weight:600;">Shop Now</a>
	      						<a href="#products" class="btn-hero-secondary" style="background:transparent; color:#dc2626; border:2px solid #dc2626; padding:16px 40px; border-radius:30px; font-size:16px; font-weight:600;">View Products</a>
	      					</div>
	      				</div>
	      			</div>
	      			<div class="col-md-6 col-xs-12">
	      				<div class="hero-image">
	      					<img src="images/banner.png" alt="Hero Image" style="max-height:600px; width:100%; object-fit:contain;">
	      				</div>
	      			</div>
	      		</div>
	      	</div>
	      	
	        <div class="row" id="products">
	        	<div class="col-xs-12">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}
	        		?>
	        		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="margin-bottom:60px;">
		                <ol class="carousel-indicators">
		                  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		                  <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
		                  <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
		                </ol>
		                <div class="carousel-inner">
		                  <div class="item active">
		                    <img src="images/banner.png" alt="First slide">
		                  </div>
		                  <div class="item">
		                    <img src="images/banner.png" alt="Second slide">
		                  </div>
		                  <div class="item">
		                    <img src="images/banner.png" alt="Third slide">
		                  </div>
		                </div>
		                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		                  <span class="fa fa-angle-left"></span>
		                </a>
		                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		                  <span class="fa fa-angle-right"></span>
		                </a>
		            </div>
		            <h2 style="margin-top: 40px;"><i class="fa fa-star"></i> Monthly Top Sellers</h2>
		       		<?php
		       			$month = date('m');
		       			$conn = $pdo->open();

		       			try{
		       			 	$inc = 3;	
						    $stmt = $conn->prepare("SELECT *, SUM(quantity) AS total_qty FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE MONTH(sales_date) = '$month' GROUP BY details.product_id ORDER BY total_qty DESC LIMIT 6");
						    $stmt->execute();
						    foreach ($stmt as $row) {
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
						    	$inc = ($inc == 3) ? 1 : $inc + 1;
	       						if($inc == 1) echo "<div class='row'>";
	       						echo "
	       							<div class='col-sm-4 col-xs-6'>
	       								<div class='box box-solid product-card-modern'>
		       								<a href='product.php?product=".$row['slug']."' style='text-decoration:none; display:block;'>
			       								<div class='box-body prod-body'>
			       									<img src='".$image."' width='100%' height='230px' class='thumbnail product-image'>
			       								</div>
			       								<div class='product-name-section'>
			       									<h5 class='product-name'>".$row['name']."</h5>
			       								</div>
			       								<div class='box-footer'>
			       									<b>₹ ".number_format($row['price'], 2)."</b>
			       								</div>
		       								</a>
	       								</div>
	       							</div>
	       						";
	       						if($inc == 3) echo "</div>";
						    }
						    if($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>"; 
							if($inc == 2) echo "<div class='col-sm-4'></div></div>";
						}
						catch(PDOException $e){
							echo "There is some problem in connection: " . $e->getMessage();
						}

						$pdo->close();

		       		?> 
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>