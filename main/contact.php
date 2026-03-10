<?php
session_start();
include '../includes/db_connect.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contact_messages (name, email, phone, message) 
            VALUES ('$name', '$email', '$phone', '$message')";

    if ($conn->query($sql)) {
        $success = "Thank you! Your message has been sent.";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Cosmetic Mart</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #fff0f5;
    color: #333;
}

/* Navbar */
.navbar {
    background-color: #ff69b4;
    padding: 15px 0;
    text-align: center;
}
.navbar a {
    color: white;
    text-decoration: none;
    margin: 0 25px;
    font-weight: 600;
    font-size: 18px;
}
.navbar a:hover {
    text-decoration: underline;
}

/* Contact Section */
.contact-container {
    max-width: 850px;
    margin: 80px auto;
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.contact-container h1 {
    text-align: center;
    color: #ff1493;
    margin-bottom: 30px;
}
.contact-container p {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
input, textarea {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 16px;
    outline: none;
}
input:focus, textarea:focus {
    border-color: #ff69b4;
}
button {
    background-color: #ff69b4;
    color: white;
    border: none;
    padding: 14px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background-color: #ff1493;
}

/* Contact Info Section */
.contact-info {
    margin-top: 40px;
    text-align: center;
    background-color: #ffe4ec;
    padding: 25px;
    border-radius: 15px;
}
.contact-info h3 {
    color: #ff1493;
    margin-bottom: 10px;
}
.contact-info p {
    margin: 5px 0;
    font-size: 16px;
    color: #444;
}

/* Success / Error Messages */
.message {
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
}
.message.success { color: green; }
.message.error { color: red; }

/* Footer */
footer {
    background-color: #ff69b4;
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: 60px;
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
</div>

<!-- Contact Form -->
<div class="contact-container">
    <h1>Contact Us</h1>
    <p>Have questions or need help? Send us a message below!</p>

    <?php if($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="text" name="phone" placeholder="Your Phone Number" required>
        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
    </form>

    <div class="contact-info">
        <h3>📞 Get in Touch</h3>
        <p><strong>Phone:</strong> +977-9812345678</p>
        <p><strong>Email:</strong> support@cosmeticmart.com</p>
        <p><strong>Address:</strong> Bhaktapur, Nepal</p>
        <p>Our team will get back to you within 24 hours!</p>
    </div>
</div>

<footer>
    © 2025 Cosmetic Mart | All Rights Reserved
</footer>

</body>
</html>
