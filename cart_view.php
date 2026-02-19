<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-xs-12">
	        		<h1 class="page-header" style="text-align:center; margin-bottom:40px;">YOUR CART</h1>
	        		<div class="box box-solid modern-cart-box">
	        			<div class="box-body">
		        		<table class="table table-bordered modern-cart-table">
		        			<thead>
		        				<tr>
		        					<th style="width:50px;"></th>
		        					<th>Photo</th>
		        					<th>Name</th>
		        					<th>Price</th>
		        					<th width="20%">Quantity</th>
		        					<th>Subtotal</th>
		        				</tr>
		        			</thead>
		        			<tbody id="tbody">
		        			</tbody>
		        		</table>
	        			</div>
	        		</div>
	        		<div class="cart-checkout-section">
						<?php
							if(isset($_SESSION['user'])){
								echo "
									<div style='display:flex; gap:10px; align-items:center;'>
										<div id='paypal-button' class='paypal-button-container'></div>
										<button id='razorpay-button' class='btn btn-success'>Pay with Razorpay</button>
									</div>
								";
							}
							else{
								echo "
									<div class='login-prompt-box'>
										<h4>You need to <a href='login.php' class='login-link-cart'>Login</a> to checkout.</h4>
									</div>
								";
							}
						?>
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
var total = 0;
$(function(){
	$(document).on('click', '.cart_delete', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'cart_delete.php',
			data: {id:id},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.minus', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		if(qty>1){
			qty--;
		}
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.add', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		qty++;
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	getDetails();
	getTotal();

});

function getDetails(){
	$.ajax({
		type: 'POST',
		url: 'cart_details.php',
		dataType: 'json',
		success: function(response){
			$('#tbody').html(response);
			getCart();
		}
	});
}

function getTotal(){
	$.ajax({
		type: 'POST',
		url: 'cart_total.php',
		dataType: 'json',
		success:function(response){
			total = response;
		}
	});
}
</script>
<!-- Paypal Express -->
<script>
paypal.Button.render({
    env: 'sandbox', // change for production if app is live,

	client: {
        sandbox:    'AEfYxns5l1tnCle5stC4-vpS0mg4ABwESySCOSq9CsW7wff3Ehr5LeGA',
        //production: 'AaBHKJFEej4V6yaArjzSx9cuf-UYesQYKqynQVCdBlKuZKawDDzFyuQdidPOBSGEhWaNQnnvfzuFB9SM'
    },

    commit: true, // Show a 'Pay Now' button

    style: {
    	color: 'gold',
    	size: 'small'
    },

    payment: function(data, actions) {
        return actions.payment.create({
            payment: {
                transactions: [
                    {
                    	//total purchase
                        amount: { 
                        	total: total, 
                        	currency: 'USD' 
                        }
                    }
                ]
            }
        });
    },

    onAuthorize: function(data, actions) {
        return actions.payment.execute().then(function(payment) {
			window.location = 'sales.php?pay='+payment.id;
        });
    },

}, '#paypal-button');
</script>
<!-- Razorpay Checkout -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$(function(){
	$('#razorpay-button').click(function(e){
		e.preventDefault();
		// create order on server
		$.ajax({
			url: 'create_razorpay_order.php',
			method: 'POST',
			dataType: 'json',
			success: function(resp){
				if(!resp.success){
					var details = resp.error || 'Unknown';
					if(resp.raw) details += '\n\nServer response: ' + resp.raw;
					alert('Could not create order: ' + details);
					return;
				}

				var order = resp.order;
				var options = {
					"key": resp.key,
					"amount": order.amount,
					"currency": order.currency,
					"name": "My Shop",
					"description": "Order Payment",
					"order_id": order.id,
					"handler": function (razorpay_resp){
						// verify on server and insert order
						$.post('verify_razorpay_payment.php', {
							razorpay_payment_id: razorpay_resp.razorpay_payment_id,
							razorpay_order_id: razorpay_resp.razorpay_order_id,
							razorpay_signature: razorpay_resp.razorpay_signature
						}, function(res){
							if(res.success){
								window.location = 'order_success.php?id=' + res.sales_id;
							} else {
								alert('Payment verification failed: ' + (res.error||'Unknown'));
							}
						}, 'json');
					},
					"prefill": {
						"name": "<?php echo isset($user)?htmlspecialchars($user['firstname'].' '.$user['lastname']):''; ?>",
						"email": "<?php echo isset($user)?htmlspecialchars($user['email']):''; ?>"
					}
				};

				var rzp = new Razorpay(options);
				rzp.open();
			},
			error: function(xhr, status, err){
				alert('Error creating order: ' + err);
			}
		});
	});
});
</script>
</body>
</html>