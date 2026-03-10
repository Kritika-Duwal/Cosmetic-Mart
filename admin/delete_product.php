<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}

// Check if product ID is provided
if(isset($_GET['id'])){
    $product_id = intval($_GET['id']);

    // First, get the image path to delete the file
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $product = $result->fetch_assoc();
        if(file_exists($product['image'])){
            unlink($product['image']); // delete image file
        }
    }

    // Delete product from database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

// Redirect back to products page
header("Location: view_products.php");
exit();
?>
