<?php
session_start();
include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home - Cosmetic Mart</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<style>
* { margin:0; padding:0; box-sizing: border-box; }

body, html {
    font-family: 'Roboto', sans-serif;
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

/* Hero Section */
.hero {
    height: 100vh;
    background: url('../assets/image/cosmetic mart.jpg') center/cover no-repeat;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    text-align: center;
}
.hero::before {
    content: '';
    position: absolute;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(255,255,255,0.6);
}
.hero-content {
    position: relative;
    z-index: 1;
    max-width: 700px;
}
.hero-content h1 {
    font-size: 60px;
    color: #cb287fff;
    margin-bottom: 20px;
    line-height: 1.2;
}
.hero-content p {
    font-size: 22px;
    color: #333;
    margin-bottom: 30px;
}
.hero-content a {
    background: #ff69b4;
    color: white;
    padding: 18px 40px;
    text-decoration: none;
    border-radius: 50px;
    font-weight: bold;
    font-size: 18px;
    transition: 0.3s;
}
.hero-content a:hover {
    background: #ff1493;
    transform: scale(1.05);
}

/* About Section */
.about {
    padding: 80px 20px;
    background: #fff0f5;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 40px;
}
.about img {
    max-width: 400px;
    border-radius: 20px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
.about-text {
    max-width: 500px;
}
.about-text h2 {
    color: #ff1493;
    font-size: 32px;
    margin-bottom: 20px;
}
.about-text p {
    font-size: 17px;
    color: #555;
    line-height: 1.6;
}

/* Features Section */
.features {
    display: flex;
    justify-content: center;
    gap: 40px;
    padding: 80px 20px;
    flex-wrap: wrap;
}
.feature-box {
    background: white;
    border-radius: 20px;
    padding: 35px 25px;
    width: 280px;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.feature-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.feature-box h3 {
    color: #ff1493;
    margin-bottom: 15px;
    font-size: 20px;
}
.feature-box p {
    font-size: 15px;
    color: #555;
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
    .hero-content h1 { font-size: 45px; }
    .hero-content p { font-size: 18px; }
    .features { gap: 20px; }
    .about { gap: 20px; flex-direction: column-reverse; }
}
@media (max-width: 480px) {
    .navbar a { margin: 0 10px; font-size: 16px; }
    .hero-content h1 { font-size: 35px; }
    .hero-content p { font-size: 16px; }
}
</style>
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="products.php">View Products</a>
    <a href="cart.php">View Cart</a>
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact</a>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="profile.php">Profile</a>
        <a href="../auth/logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</div>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <?php if(isset($_SESSION['user_name'])): ?>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 🌸</h1>
        <?php else: ?>
            <h1>Welcome to Cosmetic Mart 🌸</h1>
        <?php endif; ?>
        <p>Discover premium cosmetic products at Cosmetic Mart! Skincare, makeup, and beauty essentials delivered to your door.</p>
        <a href="products.php">Shop Now</a>
    </div>
</div>

<!-- About Us Section -->
<div class="about">
    <img src="../assets/image/cosmetic mart.jpg" alt="Cosmetics">
    <div class="about-text">
        <h2>About Cosmetic Mart</h2>
        <p>At Cosmetic Mart, we believe in providing high-quality, authentic cosmetic products for everyone. Our mission is to make beauty shopping simple, convenient, and enjoyable. From skincare to makeup, we ensure only the best brands and products reach your hands.</p>
        <p>Experience a seamless online shopping journey with fast delivery, safe checkout, and a wide range of products to suit your beauty needs.</p>
    </div>
</div>

<!-- Features Section -->
<div class="features">
    <div class="feature-box">
        <h3>Wide Selection</h3>
        <p>Explore a variety of products from trusted cosmetic brands.</p>
    </div>
    <div class="feature-box">
        <h3>Easy Shopping</h3>
        <p>Add products to your cart and checkout in just a few clicks.</p>
    </div>
    <div class="feature-box">
        <h3>Fast Delivery</h3>
        <p>Get your favorite products delivered safely to your doorstep.</p>
    </div>
    <div class="feature-box">
        <h3>Quality Guarantee</h3>
        <p>All products are authentic and carefully inspected for quality.</p>
    </div>
</div>

<!-- Footer -->
<footer>
    © 2025 Cosmetic Mart | All Rights Reserved
</footer>

</body>
</html>