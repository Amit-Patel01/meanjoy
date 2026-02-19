<?php
	include 'includes/session.php';
	$conn = $pdo->open();

	$html = '';
	$total = 0;

	if(isset($_SESSION['user'])){
		if(isset($_SESSION['cart'])){
			foreach($_SESSION['cart'] as $row){
				$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user_id AND product_id=:product_id");
				$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$row['productid']]);
				$crow = $stmt->fetch();
				if($crow['numrows'] < 1){
					$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
					$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$row['productid'], 'quantity'=>$row['quantity']]);
				}
				else{
					$stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE user_id=:user_id AND product_id=:product_id");
					$stmt->execute(['quantity'=>$row['quantity'], 'user_id'=>$user['id'], 'product_id'=>$row['productid']]);
				}
			}
			unset($_SESSION['cart']);
		}

		try{
			$stmt = $conn->prepare("SELECT *, cart.id AS cartid FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user");
			$stmt->execute(['user'=>$user['id']]);
			$count = 0;
			foreach($stmt as $row){
				$count++;
				$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
				$subtotal = $row['price']*$row['quantity'];
				$total += $subtotal;
				$html .= '
					<div class="cart-slider-item">
						<div class="cart-item-image">
							<img src="'.$image.'" alt="'.$row['name'].'">
						</div>
						<div class="cart-item-details">
							<h4>'.$row['name'].'</h4>
							<p class="cart-item-price">₹ '.number_format($row['price'], 2).'</p>
							<div class="cart-item-quantity">
								<button class="cart-slider-minus" data-id="'.$row['cartid'].'"><i class="fa fa-minus"></i></button>
								<span id="slider_qty_'.$row['cartid'].'">'.$row['quantity'].'</span>
								<button class="cart-slider-plus" data-id="'.$row['cartid'].'"><i class="fa fa-plus"></i></button>
							</div>
							<p class="cart-item-subtotal">Subtotal: ₹ '.number_format($subtotal, 2).'</p>
						</div>
						<button class="cart-slider-delete" data-id="'.$row['cartid'].'">
							<i class="fa fa-trash"></i>
						</button>
					</div>
				';
			}
			
			if($count == 0){
				$html = '<div class="cart-empty">
					<i class="fa fa-shopping-cart"></i>
					<p>Your cart is empty</p>
					<a href="index.php" class="btn btn-primary">Continue Shopping</a>
				</div>';
			}
		}
		catch(PDOException $e){
			$html = '<div class="cart-error">Error loading cart</div>';
		}
	}
	else{
		if(isset($_SESSION['cart']) && count($_SESSION['cart']) != 0){
			foreach($_SESSION['cart'] as $row){
				$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
				$stmt->execute(['id'=>$row['productid']]);
				$product = $stmt->fetch();
				$image = (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg';
				$subtotal = $product['price']*$row['quantity'];
				$total += $subtotal;
				$html .= '
					<div class="cart-slider-item">
						<div class="cart-item-image">
							<img src="'.$image.'" alt="'.$product['name'].'">
						</div>
						<div class="cart-item-details">
							<h4>'.$product['name'].'</h4>
							<p class="cart-item-price">$'.number_format($product['price'], 2).'</p>
							<div class="cart-item-quantity">
								<button class="cart-slider-minus" data-id="'.$row['productid'].'"><i class="fa fa-minus"></i></button>
								<span id="slider_qty_'.$row['productid'].'">'.$row['quantity'].'</span>
								<button class="cart-slider-plus" data-id="'.$row['productid'].'"><i class="fa fa-plus"></i></button>
							</div>
							<p class="cart-item-subtotal">Subtotal: ₹ '.number_format($subtotal, 2).'</p>
						</div>
						<button class="cart-slider-delete" data-id="'.$row['productid'].'">
							<i class="fa fa-trash"></i>
						</button>
					</div>
				';
			}
		}
		else{
			$html = '<div class="cart-empty">
				<i class="fa fa-shopping-cart"></i>
				<p>Your cart is empty</p>
				<a href="index.php" class="btn btn-primary">Continue Shopping</a>
			</div>';
		}
	}

	$pdo->close();
	echo json_encode(['html' => $html, 'total' => $total]);
?>

