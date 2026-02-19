<?php include 'includes/session.php'; ?>
<?php
	if(!isset($_SESSION['user'])){
		header('location: index.php');
	}
?>
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
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger modern-alert'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}

	        			if(isset($_SESSION['success'])){
	        				echo "
	        					<div class='alert alert-success modern-alert'>
	        						".$_SESSION['success']."
	        					</div>
	        				";
	        				unset($_SESSION['success']);
	        			}
	        		?>
	        		<div class="box box-solid modern-profile-card">
	        			<div class="box-body modern-profile-body">
	        				<div class="row">
	        					<div class="col-sm-4 col-xs-12 text-center">
	        						<div class="profile-image-container">
	        							<img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" class="profile-image">
	        						</div>
	        					</div>
	        					<div class="col-sm-8 col-xs-12">
	        						<div class="profile-info">
	        							<div class="profile-header">
	        								<h2 class="profile-name"><?php echo $user['firstname'].' '.$user['lastname']; ?></h2>
	        								<a href="#edit" class="btn btn-success btn-edit-profile" data-toggle="modal"><i class="fa fa-edit"></i> Edit Profile</a>
	        							</div>
	        							<div class="profile-details">
	        								<div class="detail-item">
	        									<label><i class="fa fa-envelope"></i> Email:</label>
	        									<span><?php echo $user['email']; ?></span>
	        								</div>
	        								<div class="detail-item">
	        									<label><i class="fa fa-phone"></i> Contact Info:</label>
	        									<span><?php echo (!empty($user['contact_info'])) ? $user['contact_info'] : 'N/A'; ?></span>
	        								</div>
	        								<div class="detail-item">
	        									<label><i class="fa fa-map-marker"></i> Address:</label>
	        									<span><?php echo (!empty($user['address'])) ? $user['address'] : 'N/A'; ?></span>
	        								</div>
	        								<div class="detail-item">
	        									<label><i class="fa fa-calendar"></i> Member Since:</label>
	        									<span><?php echo date('M d, Y', strtotime($user['created_on'])); ?></span>
	        								</div>
	        							</div>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="box box-solid modern-transaction-card">
	        			<div class="box-header modern-transaction-header">
	        				<h4 class="box-title"><i class="fa fa-calendar"></i> Transaction History</h4>
	        			</div>
	        			<div class="box-body">
	        				<table class="table table-bordered modern-transaction-table" id="example1">
	        					<thead>
	        						<tr>
	        							<th class="hidden"></th>
	        							<th>Date</th>
	        							<th>Transaction#</th>
	        							<th>Amount</th>
	        							<th>Full Details</th>
	        						</tr>
	        					</thead>
	        					<tbody>
	        					<?php
	        						$conn = $pdo->open();

	        						try{
	        							$stmt = $conn->prepare("SELECT * FROM sales WHERE user_id=:user_id ORDER BY sales_date DESC");
	        							$stmt->execute(['user_id'=>$user['id']]);
	        							foreach($stmt as $row){
	        								$stmt2 = $conn->prepare("SELECT * FROM details LEFT JOIN products ON products.id=details.product_id WHERE sales_id=:id");
	        								$stmt2->execute(['id'=>$row['id']]);
	        								$total = 0;
	        								foreach($stmt2 as $row2){
	        									$subtotal = $row2['price']*$row2['quantity'];
	        									$total += $subtotal;
	        								}
	        								echo "
	        									<tr>
	        										<td class='hidden'></td>
	        										<td>".date('M d, Y', strtotime($row['sales_date']))."</td>
	        										<td>".$row['pay_id']."</td>
	        										<td>₹ ".number_format($total, 2)."</td>
	        										<td><button class='btn btn-sm btn-flat btn-info transact modern-view-btn' data-id='".$row['id']."'><i class='fa fa-search'></i> View</button></td>
	        									</tr>
	        								";
	        							}

	        						}
        							catch(PDOException $e){
										echo "There is some problem in connection: " . $e->getMessage();
									}

	        						$pdo->close();
	        					?>
	        					</tbody>
	        				</table>
	        			</div>
	        		</div>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/profile_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$(document).on('click', '.transact', function(e){
		e.preventDefault();
		$('#transaction').modal('show');
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'transaction.php',
			data: {id:id},
			dataType: 'json',
			success:function(response){
				$('#date').html(response.date);
				$('#transid').html(response.transaction);
				$('#detail').prepend(response.list);
				$('#total').html(response.total);
			}
		});
	});

	$("#transaction").on("hidden.bs.modal", function () {
	    $('.prepend_items').remove();
	});
});
</script>
</body>
</html>