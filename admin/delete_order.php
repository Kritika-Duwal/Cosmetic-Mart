<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}

// Check if order_id is provided
if(isset($_GET['order_id'])){
    $order_id = intval($_GET['order_id']);

    // Delete order items first
    $stmt_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();

    // Delete order
    $stmt_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_order->bind_param("i", $order_id);
    $stmt_order->execute();
}

// Redirect back to view orders page
// header("Location: view_orders.php");
exit();
?>
