<?php
session_start();
include '../includes/db_connect.php';

$error = '';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $admin = $result->fetch_assoc();
        if(password_verify($password, $admin['password'])){
            // Login successful
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: ../admin/admin_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Cosmetic Mart</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #ffe4ec, #fff0f5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(255,105,180,0.3);
            text-align: center;
            width: 90%;
            max-width: 380px;
            transition: 0.3s;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255,105,180,0.4);
        }

        h2 {
            color: #ff1493;
            margin-bottom: 25px;
            font-size: 28px;
        }

        input[type="text"],
        input[type="password"] {
            width: 85%;
            padding: 12px 15px;
            margin: 12px 0;
            border-radius: 50px;
            border: 1px solid #ff69b4;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #ff1493;
            box-shadow: 0 0 10px rgba(255,20,147,0.3);
        }

        input[type="submit"] {
            width: 90%;
            padding: 12px;
            background: #FF69B4;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background: #FF1493;
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        footer {
            margin-top: 25px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if($error != '') echo "<p class='error'>$error</p>"; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" name="login" value="Login">
        </form>

        <footer>© 2025 Cosmetic Mart | Admin Panel</footer>
    </div>
</body>
</html>
