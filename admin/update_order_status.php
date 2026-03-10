<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}

// Check if order_id is provided
if(!isset($_GET['order_id'])){
    header("Location: view_orders.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch the current order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "Order not found!";
    exit();
}

$order = $result->fetch_assoc();

// Handle form submission
if(isset($_POST['update'])){
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    header("Location: view_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Order Status - Admin Dashboard</title>
    <style>
        body { font-family: Arial; text-align:center; margin-top:50px; }
        select, input[type=submit] { padding:5px; margin:5px 0; }
        input[type=submit] { background:#4CAF50; color:white; border:none; border-radius:5px; cursor:pointer; }
        input[type=submit]:hover { background:#45a049; }
    </style>
</head>
<body>
    <h2>Update Status for Order ID: <?php echo $order['id']; ?></h2>
    <form method="post">
        <label>Current Status: <?php echo $order['status']; ?></label><br><br>
        <label>New Status:</label><br>
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Completed" <?php if($order['status']=='Completed') echo 'selected'; ?>>Completed</option>
            <option value="Cancelled" <?php if($order['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select><br><br>
        <input type="submit" name="update" value="Update Status">
    </form>
    <br>
    <a href="view_orders.php">Back to Orders</a>
</body>
</html>

