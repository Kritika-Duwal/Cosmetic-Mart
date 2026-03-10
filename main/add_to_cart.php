<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get product ID from URL
if(isset($_GET['product_id'])){
    $product_id = intval($_GET['product_id']); // ✅ only numeric

    // Initialize cart if not set
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }

    // Add product to cart
    if(isset($_SESSION['cart'][$product_id])){
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}

// Redirect back to products page
header("Location: products.php");
exit();
?>



