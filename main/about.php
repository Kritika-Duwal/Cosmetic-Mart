<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Cosmetic Mart</title>

<style>
* { margin:0; padding:0; box-sizing: border-box; }

body, html {
    font-family: Arial, sans-serif;
    scroll-behavior: smooth;
    background: #fff;
    color: #333;
}

/* Navbar */
.navbar {
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    background: rgba(255,105,180,0.95);
    padding: 20px 0;
    z-index: 1000;
    text-align: center;
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}
.navbar a {
    color: #fff;
    text-decoration: none;
    margin: 0 25px;
    font-weight: 600;
    font-size: 18px;
    transition: 0.3s;
}
.navbar a:hover {
    color: #ff1493;
    transform: scale(1.1);
}

/* About Section */
.about {
    padding: 120px 20px 80px 20px;
    background: #fff0f5;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 40px;
    text-align: center;
}
.about h2 {
    color: #ff1493;
    font-size: 36px;
    margin-bottom: 20px;
}
.about p {
    font-size: 18px;
    color: #555;
    line-height: 1.6;
    max-width: 800px;
}

/* About Images */
.about-images {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
}
.about-images img {
    width: 250px;
    height: 200px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

/* Footer */
footer {
    background: #ff69b4;
    color: white;
    text-align: center;
    padding: 25px;
    margin-top: 50px;
}

/* Responsive */
@media (max-width: 768px) {
    .about-images img { width: 45%; height: 180px; }
}
@media (max-width: 480px) {
    .navbar a { margin: 0 10px; font-size: 16px; }
    .about-images img { width: 100%; height: 150px; }
}
</style>
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="products.php">View Products</a>
    <a href="cart.php">View Cart</a>
    <a href="profile.php">Profile</a>
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact</a>
</div>

<!-- About Section -->
<div class="about">
    <h2>About Cosmetic Mart</h2>
    <p>Cosmetic Mart is more than a store — it’s a destination for beauty enthusiasts. We provide authentic and premium cosmetic products that cater to every skin type, every style, and every preference. Our mission is to make your online shopping journey seamless, safe, and delightful.</p>
    <p>With fast delivery, easy returns, and expert guidance, we ensure your beauty experience is enjoyable from start to finish. Join thousands of happy customers who trust Cosmetic Mart for their beauty needs.</p>

    <!-- About Images -->
    <div class="about-images">
        <img src="../assets/image/about1.jpg" alt="Cosmetic Brand 1">
        <img src="../assets/image/about2.jpeg" alt="Cosmetic Brand 2">
        <img src="../assets/image/about3.jpg" alt="Cosmetic Brand 3">
        <img src="../assets/image/about4.jpg" alt="Cosmetic Brand 4">
    </div>
</div>

<!-- Footer -->
<footer>
    © 2025 Cosmetic Mart | All Rights Reserved
</footer>

</body>
</html>
