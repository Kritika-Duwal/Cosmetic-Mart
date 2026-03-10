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
    <title>View Orders - Admin Dashboard</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        a { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: white; }
        .update { background: #4CAF50; }
        .update:hover { background: #45a049; }
        .delete { background: #f44336; }
        .delete:hover { background: #da190b; }
        table { width: 95%; border-collapse: collapse; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        .product-list { text-align: left; }
        .product-list li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">View Orders</h2>
    <p style="text-align:center;"><a href="admin_dashboard.php" style="background:#FF69B4;">Back to Dashboard</a></p>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Products Ordered</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM orders ORDER BY order_date DESC";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                // Fetch products for this order
                $order_id = $row['id'];
                $sql_items = "SELECT oi.quantity, p.name, p.price 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = $order_id";
                $items_result = $conn->query($sql_items);

                // Build product list HTML
                $product_list = "<ul class='product-list'>";
                if($items_result->num_rows > 0){
                    while($item = $items_result->fetch_assoc()){
                        $product_list .= "<li>".$item['name']." x ".$item['quantity']." (Rs ".$item['price'].")</li>";
                    }
                } else {
                    $product_list .= "<li>No products found</li>";
                }
                $product_list .= "</ul>";

                echo "<tr>
                        <td>".$row['id']."</td>
                        <td>".$row['customer_name']."</td>
                        <td>".$row['customer_address']."</td>
                        <td>".$row['customer_phone']."</td>
                        <td>".$product_list."</td>
                        <td>Rs ".$row['total']."</td>
                        <td>".$row['status']."</td>
                        <td>".$row['order_date']."</td>
                        <td>";
                if($row['status'] == 'Pending'){
                    echo "<a class='update' href='update_order_status.php?order_id=".$row['id']."'>Update Status</a>";
                } else {
                    echo "<a class='delete' href='delete_order.php?order_id=".$row['id']."' onclick='return confirm(\"Are you sure you want to delete this order?\")'>Delete</a>";
                }
                echo "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No orders found!</td></tr>";
        }
        ?>
    </table>
</body>
</html>
