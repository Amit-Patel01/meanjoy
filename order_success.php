<?php
include 'includes/session.php';
include 'includes/header.php';

$sales_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($sales_id <= 0){
    $_SESSION['error'] = 'Invalid order reference.';
    header('location: index.php');
    exit;
}

$conn = $pdo->open();
$stmt = $conn->prepare("SELECT sales.*, users.firstname, users.lastname FROM sales LEFT JOIN users ON users.id=sales.user_id WHERE sales.id=:id");
$stmt->execute(['id'=>$sales_id]);
$sale = $stmt->fetch();
if(!$sale){
    $pdo->close();
    $_SESSION['error'] = 'Order not found.';
    header('location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT details.*, products.name, products.price FROM details LEFT JOIN products ON products.id=details.product_id WHERE details.sales_id=:id");
$stmt->execute(['id'=>$sales_id]);
$items = $stmt->fetchAll();

$pdo->close();
?>
<?php include 'includes/navbar.php'; ?>
<div class="content-wrapper">
  <div class="container">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <h1 class="page-header" style="text-align:center; margin-bottom:20px;">Order Successful</h1>
          <div class="box box-solid">
            <div class="box-body">
              <h4>Order Summary</h4>
              <table class="table table-bordered">
                <tr>
                  <th>Order ID</th>
                  <td><?php echo $sale['id']; ?></td>
                </tr>
                <tr>
                  <th>Razorpay Order ID</th>
                  <td><?php echo htmlspecialchars($sale['rzp_order_id']); ?></td>
                </tr>
                <tr>
                  <th>Payment ID</th>
                  <td><?php echo htmlspecialchars($sale['pay_id']); ?></td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td><?php echo htmlspecialchars($sale['status']); ?></td>
                </tr>
                <tr>
                  <th>Date & Time</th>
                  <td><?php echo htmlspecialchars($sale['sales_datetime'] ?? $sale['sales_date']); ?></td>
                </tr>
              </table>

              <h4>Items</h4>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach($items as $it){
                    $subtotal = $it['price'] * $it['quantity'];
                    $total += $subtotal;
                    echo "<tr><td>".htmlspecialchars($it['name'])."</td><td>".intval($it['quantity'])."</td><td>".number_format($it['price'],2)."</td><td>".number_format($subtotal,2)."</td></tr>";
                }
                ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3">Total</th>
                    <th><?php echo number_format($total,2); ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include 'includes/footer.php'; include 'includes/scripts.php'; ?>

</body>
</html>
