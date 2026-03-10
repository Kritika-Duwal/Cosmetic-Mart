<?php
session_start();
include '../includes/db_connect.php';

$amount = $_GET['amount'] ?? 0;
$paid = isset($_GET['paid']) ? true : false;

if($paid){
    $info = $_SESSION['checkout_info'];
    $cart = $_SESSION['cart'];
    $payment_method = 'eSewa';

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_address, customer_phone, total, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $_SESSION['user_id'], $info['name'], $info['address'], $info['phone'], $amount, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items with product_name
    foreach($cart as $product_id => $qty){
        $sql = "SELECT * FROM products WHERE id=".intval($product_id);
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $price = $product['price'];
        $name = $product['name']; // <- Save product name
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $stmt_item->bind_param("iisid", $order_id, $product_id, $name, $qty, $price);
        $stmt_item->execute();
    }

    unset($_SESSION['cart']);
    header("Location: order_success.php?order_id=$order_id");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pay with eSewa</title>
    <style>
        body { font-family: Arial; text-align:center; margin-top:40px; }
        .logo { width:120px; margin-bottom:20px; }
        .btn { background:#60bb46; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-size:16px; }
        .btn:hover { background:#4CAF50; }
        a { display:block; margin-top:20px; text-decoration:none; color:#FF1493; font-weight:bold; }
    </style>
</head>
<body>

<img class="logo" src="../assets/image/eSewa.jpeg" alt="eSewa Logo">
<h2>Pay with eSewa</h2>
<p>Amount to Pay: Rs <strong><?php echo $amount; ?></strong></p>

<form action="esewa_payment.php" method="GET">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="paid" value="1">
    <button class="btn" type="submit">Pay Now</button>
</form>

<a href="checkout.php?step=3">Cancel Payment</a>

</body>
</html>

