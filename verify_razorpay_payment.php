<?php
include 'includes/session.php';
include 'includes/razorpay_config.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo json_encode(['success'=>false, 'error'=>'Invalid request']);
    exit;
}

$payment_id = isset($_POST['razorpay_payment_id']) ? $_POST['razorpay_payment_id'] : null;
$order_id = isset($_POST['razorpay_order_id']) ? $_POST['razorpay_order_id'] : null;
$signature = isset($_POST['razorpay_signature']) ? $_POST['razorpay_signature'] : null;

if(!$payment_id || !$order_id || !$signature){
    echo json_encode(['success'=>false, 'error'=>'Missing parameters']);
    exit;
}

$conn = $pdo->open();

// verify signature
$expected = hash_hmac('sha256', $order_id . '|' . $payment_id, RAZOR_KEY_SECRET);
if(!hash_equals($expected, $signature)){
    echo json_encode(['success'=>false, 'error'=>'Invalid signature']);
    $pdo->close();
    exit;
}

try{
    // Ensure sales table has columns for rzp_order_id, status, sales_datetime
    $check = $conn->prepare("SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='sales' AND COLUMN_NAME='rzp_order_id'");
    $check->execute();
    $has = $check->fetch();
    if($has['cnt'] == 0){
        $conn->exec("ALTER TABLE sales ADD COLUMN rzp_order_id VARCHAR(100) NOT NULL DEFAULT ''");
    }

    $check = $conn->prepare("SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='sales' AND COLUMN_NAME='status'");
    $check->execute();
    $has = $check->fetch();
    if($has['cnt'] == 0){
        $conn->exec("ALTER TABLE sales ADD COLUMN status VARCHAR(50) NOT NULL DEFAULT 'paid'");
    }

    $check = $conn->prepare("SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='sales' AND COLUMN_NAME='sales_datetime'");
    $check->execute();
    $has = $check->fetch();
    if($has['cnt'] == 0){
        $conn->exec("ALTER TABLE sales ADD COLUMN sales_datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

    // insert sale (use pay_id to store razorpay payment id)
    $date = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO sales (user_id, pay_id, sales_date, rzp_order_id, status, sales_datetime) VALUES (:user_id, :pay_id, :sales_date, :rzp_order_id, :status, :sales_datetime)");
    $stmt->execute(['user_id'=>$user['id'], 'pay_id'=>$payment_id, 'sales_date'=>$date, 'rzp_order_id'=>$order_id, 'status'=>'paid', 'sales_datetime'=>$datetime]);
    $salesid = $conn->lastInsertId();

    // insert details
    $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
    $stmt->execute(['user_id'=>$user['id']]);
    foreach($stmt as $row){
        $ins = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
        $ins->execute(['sales_id'=>$salesid, 'product_id'=>$row['product_id'], 'quantity'=>$row['quantity']]);
    }

    // clear cart
    $del = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
    $del->execute(['user_id'=>$user['id']]);

    $pdo->close();
    echo json_encode(['success'=>true, 'sales_id'=>$salesid]);
    exit;
}
catch(PDOException $e){
    $pdo->close();
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
    exit;
}

?>
