<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Cosmetic Mart</title>
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background: #ffe4ec;
        margin: 0;
        padding: 20px;
    }

    h1 {
        color: #FF1493;
        text-align: center;
        margin-bottom: 20px;
    }

    h2 {
        text-align: center;
        margin-top: 40px;
        color: #FF69B4;
    }

    /* Admin buttons/navbar */
    .navbar {
        text-align: center;
        margin-bottom: 30px;
    }

    .navbar a {
        text-decoration: none;
        padding: 12px 20px;
        background: #FF69B4;
        color: white;
        border-radius: 25px;
        margin: 5px;
        display: inline-block;
        font-weight: bold;
        font-size: 16px;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(255,105,180,0.2);
    }

    .navbar a:hover {
        background: #FF1493;
        transform: scale(1.05);
    }

    /* Table styling */
    table {
        width: 90%;
        margin: 20px auto 50px auto;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(255,105,180,0.2);
        background: #fff0f5;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ffb6c1;
        text-align: center;
    }

    th {
        background: #FF69B4;
        color: white;
        font-size: 16px;
    }

    tr:nth-child(even) {
        background: #ffe0f0;
    }

    tr:hover {
        background: #ffb6d9;
    }

    /* Add spacing and consistent look for action buttons in table */
    .btn-action {
        padding: 8px 15px;
        background: #FF69B4;
        color: white;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
        display: inline-block;
    }

    .btn-action:hover {
        background: #FF1493;
        transform: scale(1.05);
    }

    @media (max-width: 600px) {
        .navbar a, .btn-action {
            display: block;
            margin: 8px auto;
        }

        table, th, td {
            font-size: 14px;
        }
    }
</style>

</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['admin_username']; ?>!</h1>
    <div style="text-align:center;" class='navbar'>
        <a href="add_product.php">Add Product</a>
        <a href="view_products.php">View Products</a>
        <a href="view_orders.php">View Orders</a>
        <a href="view_messages.php">View Messages</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <!-- Recent Orders -->
    <h2>Recent Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>
        <?php
        $sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 5";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "<tr>
                        <td>".$row['id']."</td>
                        <td>".$row['customer_name']."</td>
                        <td>Rs ".$row['total']."</td>
                        <td>".$row['status']."</td>
                        <td>".$row['order_date']."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No recent orders.</td></tr>";
        }
        ?>
    </table>

</body>
</html>
