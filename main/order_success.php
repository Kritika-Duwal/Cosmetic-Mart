<?php
session_start();
include '../includes/db_connect.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['order_id'])){
    header("Location: index.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order
$sql_order = "SELECT * FROM orders WHERE id=$order_id AND user_id=".$_SESSION['user_id'];
$result_order = $conn->query($sql_order);
if($result_order->num_rows == 0){
    echo "Order not found!";
    exit();
}
$order = $result_order->fetch_assoc();

// Fetch order items ordered by id ASC
$sql_items = "SELECT * FROM order_items WHERE order_id=$order_id ORDER BY id ASC";
$result_items = $conn->query($sql_items);

unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success - Cosmetic Mart</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 50px; }
        h1 { color: #FF69B4; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid #FFB6C1; padding: 10px; text-align: center; }
        th { background: #FF69B4; color: white; }
        tr:nth-child(even) { background: #ffe0f0; }
        tr:hover { background: #ffb6d9; }
        a { padding: 10px 20px; background: #FF69B4; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
        a:hover { background: #FF1493; }
    </style>
</head>
<body>

<h1>Thank You, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
<p>Your order has been placed successfully.</p>
<p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
<p><strong>Total Amount:</strong> Rs <?php echo $order['total']; ?></p>
<p><strong>Order Status:</strong> <?php echo $order['status']; ?></p>

<h2>Order Items</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price (Rs)</th>
        <th>Subtotal (Rs)</th>
    </tr>
    <?php
    $grand_total = 0;
    while($item = $result_items->fetch_assoc()){
        $subtotal = $item['price'] * $item['quantity'];
        $grand_total += $subtotal;
        echo "<tr>
                <td>{$item['id']}</td>
                <td>{$item['product_name']}</td>
                <td>{$item['quantity']}</td>
                <td>{$item['price']}</td>
                <td>$subtotal</td>
              </tr>";
    }
    ?>
    <tr>
        <th colspan="4">Grand Total</th>
        <th><?php echo $grand_total; ?></th>
    </tr>
</table>

<a href="index.php">Continue Shopping</a>
<a href="products.php">View Products</a>

</body>
</html>
