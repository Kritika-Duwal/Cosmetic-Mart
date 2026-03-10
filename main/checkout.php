<?php
session_start();
include '../includes/db_connect.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
if(empty($cart)){
    header("Location: cart.php");
    exit();
}

$step = isset($_GET['step']) ? intval($_GET['step']) : 1;
$error = '';

// Step 1: Shipping info
if($step == 1 && isset($_POST['shipping_next'])){
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    if(empty($name) || empty($address) || empty($phone)){
        $error = "All fields are required!";
    } else {
        $_SESSION['checkout_info'] = [
            'name' => $name,
            'address' => $address,
            'phone' => $phone
        ];
        header("Location: checkout.php?step=2");
        exit();
    }
}

// Step 2: Review order
if($step == 2 && isset($_POST['next_payment'])){
    header("Location: checkout.php?step=3");
    exit();
}

// Step 3: Payment
if($step == 3 && isset($_POST['place_order'])){
    $payment_method = $_POST['payment_method'];
    $info = $_SESSION['checkout_info'];

    // Calculate total
    $total = 0;
    foreach($cart as $product_id => $qty){
        $sql = "SELECT * FROM products WHERE id=".intval($product_id);
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $total += $product['price'] * $qty;
    }

    if($payment_method == 'eSewa'){
        header("Location: esewa_payment.php?amount=$total");
        exit();
    } else {
        // COD: insert order immediately
        $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_address, customer_phone, total, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdss", $_SESSION['user_id'], $info['name'], $info['address'], $info['phone'], $total, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id;

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
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Cosmetic Mart</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #ffe4ec; margin: 0; padding: 0; text-align: center; }
        h2, h3 { color: #ff1493; }
        .container { width: 90%; max-width: 700px; margin: 30px auto; background: #fff0f5; padding: 30px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        input, textarea, select { padding: 10px; width: 90%; max-width: 400px; margin: 10px 0; border-radius: 10px; border: 1px solid #ff69b4; font-size: 16px; }
        input[type=submit] { background: #FF69B4; color: white; border: none; padding: 12px 25px; border-radius: 50px; cursor: pointer; font-size: 16px; transition: 0.3s; }
        input[type=submit]:hover { background: #FF1493; transform: scale(1.05); }
        .error { color: red; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; border-radius: 10px; overflow: hidden; }
        th, td { border: 1px solid #FFB6C1; padding: 12px; text-align: center; }
        th { background: #FF69B4; color: white; font-size: 16px; }
        tr:nth-child(even) { background: #ffe0f0; }
        tr:hover { background: #ffb6d9; }
        .btn { padding: 10px 20px; background: #FF69B4; color: white; border-radius: 50px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .btn:hover { background: #FF1493; transform: scale(1.05); }
        .step { margin-bottom: 20px; font-size: 18px; font-weight: bold; color: #ff1493; }
        .payment img { margin-top: 10px; border-radius: 10px; }
        .payment {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 10px; /* controls distance between button and label */
    font-size: 16px;
}

.payment-option input[type="radio"] {
    accent-color: #ff1493; /* makes the radio pink */
    transform: scale(1.2);
    cursor: pointer;
}

.payment-option img {
    border-radius: 10px;
}
        @media(max-width: 480px){
            input, textarea, select { width: 95%; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Checkout</h2>
    <?php if($error != '') echo "<p class='error'>$error</p>"; ?>

    <?php if($step == 1): ?>
        <div class="step">Step 1: Shipping Information</div>
        <form method="post" action="checkout.php?step=1">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <textarea name="address" placeholder="Address" required></textarea><br>
            <input type="text" name="phone" placeholder="Phone Number" required><br>
            <input type="submit" name="shipping_next" value="Next">
        </form>

    <?php elseif($step == 2): ?>
        <div class="step">Step 2: Review Your Order</div>
        <?php $info = $_SESSION['checkout_info']; ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($info['name']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($info['address']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($info['phone']); ?></p>

        <h3>Products:</h3>
        <table>
            <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
            <?php
            $total = 0;
            foreach($cart as $product_id => $qty){
                $sql = "SELECT * FROM products WHERE id=".intval($product_id);
                $result = $conn->query($sql);
                $product = $result->fetch_assoc();
                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
                echo "<tr>
                        <td>{$product['name']}</td>
                        <td>Rs {$product['price']}</td>
                        <td>$qty</td>
                        <td>Rs $subtotal</td>
                      </tr>";
            }
            ?>
            <tr><td colspan="3"><strong>Total</strong></td><td>Rs <?php echo $total; ?></td></tr>
        </table>
        <form method="post" action="checkout.php?step=2">
            <input type="submit" class="btn" name="next_payment" value="Next: Payment">
        </form>

    <?php elseif($step == 3): ?>
        <div class="step">Step 3: Payment</div>
        <form method="post" class="payment">
    <label class="payment-option">
        <input type="radio" name="payment_method" value="COD" checked>
        <span>Cash on Delivery</span>
    </label>

    <label class="payment-option">
        <input type="radio" name="payment_method" value="eSewa">
        <img src="../assets/image/esewa.jpeg" alt="eSewa Logo" width="100">
    </label>

    <br>
    <input type="submit" name="place_order" value="Place Order">
</form>

    <?php endif; ?>
</div>

</body>
</html>

