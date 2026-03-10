<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Handle update quantity
if(isset($_POST['update_cart'])){
    foreach($_POST['quantities'] as $product_id => $quantity){
        $quantity = intval($quantity);
        if($quantity <= 0){
            unset($_SESSION['cart'][$product_id]); // Remove if quantity is 0
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle remove item
if(isset($_GET['remove_id'])){
    $remove_id = intval($_GET['remove_id']);
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}

// Get cart items
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - Cosmetic Mart</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #ffe4ec; margin: 0; padding: 0; text-align: center; }
        h1 { color: #ff1493; margin-top: 20px; }
        a { text-decoration: none; }

        .top-links { margin: 20px 0; }
        .top-links a {
            display: inline-block;
            padding: 15px 30px;
            margin: 0 10px;
            background: #FF69B4;
            color: white;
            border-radius: 50px;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
        }
        .top-links a:hover { background: #FF1493; transform: scale(1.1); }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff0f5;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px;
            text-align: center;
            color: #333;
        }
        th {
            background: #FF69B4;
            color: white;
            font-size: 16px;
        }
        tr:nth-child(even) { background: #ffe0f0; }
        tr:hover { background: #ffb6d9; }

        img { width: 80px; height: 80px; border-radius: 10px; }

        input[type=number] {
            width: 60px;
            padding: 5px;
            border-radius: 10px;
            border: 1px solid #ff69b4;
            text-align: center;
        }

        .btn {
            padding: 8px 15px;
            background: #FF69B4;
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn:hover { background: #FF1493; transform: scale(1.05); }

        .cart-actions { margin-top: 15px; }
        .cart-actions input[type=submit] { cursor: pointer; }

        @media(max-width: 768px){
            table { width: 95%; }
            input[type=number] { width: 50px; }
        }
    </style>
</head>
<body>

<h1>Your Cart</h1>

<!-- Big Home and Products Buttons -->
<div class="top-links">
    <a href="index.php">Home</a>
    <a href="products.php">Continue Shopping</a>
</div>

<?php if(!empty($cart)): ?>
<form method="post">
<table>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>
    <?php
    $total = 0;
    foreach($cart as $product_id => $quantity):
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 0) continue;
        $product = $result->fetch_assoc();
        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;
    ?>
    <tr>
        <td><?php echo $product['name']; ?></td>
        <td>Rs <?php echo $product['price']; ?></td>
        <td><input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="0"></td>
        <td>Rs <?php echo $subtotal; ?></td>
        <td><a class="btn" href="cart.php?remove_id=<?php echo $product_id; ?>" onclick="return confirm('Remove this item?')">Remove</a></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td colspan="2"><strong>Rs <?php echo $total; ?></strong></td>
    </tr>
</table>

<div class="cart-actions">
    <input class="btn" type="submit" name="update_cart" value="Update Cart">
    <a class="btn" href="checkout.php">Proceed to Checkout</a>
</div>
</form>
<?php else: ?>
<p style="margin-top:20px; font-size:18px; color:#FF1493;">Your cart is empty!</p>
<?php endif; ?>

</body>
</html>
