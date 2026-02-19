<?php
include 'includes/session.php';
include 'includes/razorpay_config.php';

$conn = $pdo->open();

// calculate total from cart
$stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products on products.id=cart.product_id WHERE user_id=:user_id");
$stmt->execute(['user_id'=>$user['id']]);
$total = 0;
foreach($stmt as $row){
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
}

$amount_smallest = round($total * 100); // smallest currency unit (cents/paise)
$receipt = 'rcpt_' . $user['id'] . '_' . time();

$data = [
    'amount' => $amount_smallest,
    'currency' => RAZOR_CURRENCY,
    'receipt' => $receipt,
    'payment_capture' => 1
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_USERPWD, RAZOR_KEY_ID . ':' . RAZOR_KEY_SECRET);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');
if($http_status == 200 || $http_status == 201){
    $order = json_decode($response, true);
    echo json_encode(['success'=>true, 'order'=>$order, 'key'=>RAZOR_KEY_ID, 'amount'=>$amount_smallest, 'currency'=>RAZOR_CURRENCY]);
}
else{
    echo json_encode(['success'=>false, 'error'=>'Failed creating order', 'raw'=>$response]);
}

$pdo->close();

?>
