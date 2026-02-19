<?php include 'includes/session.php'; ?>
<?php
	$conn = $pdo->open();

	$slug = $_GET['product'];

	try{
		 		
	    $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid FROM products LEFT JOIN category ON category.id=products.category_id WHERE slug = :slug");
	    $stmt->execute(['slug' => $slug]);
	    $product = $stmt->fetch();
		
	}
	catch(PDOException $e){
		echo "There is some problem in connection: " . $e->getMessage();
	}

	//page view
	$now = date('Y-m-d');
	if($product['date_view'] == $now){
		$stmt = $conn->prepare("UPDATE products SET counter=counter+1 WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid']]);
	}
	else{
		$stmt = $conn->prepare("UPDATE products SET counter=1, date_view=:now WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid'], 'now'=>$now]);
	}

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition layout-top-nav">
<script>
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-xs-12">
	        		<div class="alert" id="callout" style="display:none">
	        			<button type="button" class="close" style="color:#fff; opacity:1;"><span aria-hidden="true">&times;</span></button>
	        			<span class="message"></span>
	        		</div>
		            <div class="product-detail-container">
		            	<div class="product-image-section">
		            		<div class="product-image-wrapper">
		            			<img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg'; ?>" class="product-main-image zoom" data-magnify-src="images/large-<?php echo $product['photo']; ?>">
		            		</div>
		            	</div>
		            	<div class="product-info-section">
		            		<h1 class="product-detail-title"><?php echo $product['prodname']; ?></h1>
		            		<div class="product-detail-price">
		            			<span class="price-currency">₹</span>
		            			<span class="price-amount"><?php echo number_format($product['price'], 2); ?></span>
		            		</div>
		            		<div class="product-meta">
		            			<div class="meta-item">
		            				<span class="meta-label"><i class="fa fa-tag"></i> Category:</span>
		            				<a href="category.php?category=<?php echo $product['cat_slug']; ?>" class="meta-value"><?php echo $product['catname']; ?></a>
		            			</div>
		            		</div>
		            		<div class="product-description-section">
		            			<h3 class="description-title">Description</h3>
		            			<p class="product-description-text"><?php echo $product['description']; ?></p>
		            		</div>
		            		<form class="product-cart-form" id="productForm">
		            			<div class="quantity-section">
		            				<label class="quantity-label">Quantity:</label>
		            				<div class="quantity-controls">
		            					<button type="button" id="minus" class="quantity-btn quantity-minus">
		            						<i class="fa fa-minus"></i>
		            					</button>
		            					<input type="text" name="quantity" id="quantity" class="quantity-input" value="1" readonly>
		            					<button type="button" id="add" class="quantity-btn quantity-plus">
		            						<i class="fa fa-plus"></i>
		            					</button>
		            				</div>
		            			</div>
		            			<input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
		            			<button type="submit" class="btn-add-to-cart">
		            				<i class="fa fa-shopping-cart"></i>
		            				<span>Add to Cart</span>
		            			</button>
		            		</form>
		            	</div>
		            </div>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  	<?php $pdo->close(); ?>
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('#add').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		quantity++;
		$('#quantity').val(quantity);
	});
	$('#minus').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		if(quantity > 1){
			quantity--;
		}
		$('#quantity').val(quantity);
	});

});
</script>
</body>
</html>