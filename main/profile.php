<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
$success = '';
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql_update = "UPDATE users SET name=?, email=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $name, $email, $user_id);
    if($stmt_update->execute()){
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $success = "Profile updated successfully!";
    } else {
        $success = "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Profile - Cosmetic Mart</title>
    <style>
        .navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(90deg, #ff69b4, #ff1493);
    padding: 15px 0;
    z-index: 1000;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.navbar a {
    color: #fff;
    text-decoration: none;
    margin: 0 20px;
    font-weight: 600;
    font-size: 18px;
    transition: all 0.3s ease;
}

.navbar a:hover {
    color: #ffe4ec;
    transform: scale(1.1);
}

body {
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 0;
    padding-top: 90px; /* Prevents navbar from covering content */
    background-color: #fff0f5;
}

form {
    display: inline-block;
    text-align: left;
    padding: 25px 30px;
    border: 1px solid #ffc0cb;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 5px 15px rgba(255,105,180,0.2);
}

input[type=text],
input[type=email] {
    padding: 10px;
    width: 300px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ff69b4;
    outline: none;
    transition: 0.3s;
}

input[type=text]:focus,
input[type=email]:focus {
    border-color: #ff1493;
    box-shadow: 0 0 8px rgba(255,20,147,0.3);
}

input[type=submit] {
    padding: 10px 25px;
    background: #ff69b4;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

input[type=submit]:hover {
    background: #ff1493;
    transform: scale(1.05);
}

.success {
    color: green;
    margin-bottom: 15px;
    font-weight: bold;
}
    </style>
</head>
<body>

<!-- Navigation -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="products.php">View Products</a>
    <a href="cart.php">View Cart</a>
    <a href="profile.php">Profile</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<h2>Your Profile</h2>
<?php if($success != '') echo "<p class='success'>$success</p>"; ?>

<form method="POST">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

    <input type="submit" name="update" value="Update Profile">
</form>

</body>
</html>









