<?php
include '../includes/db_connect.php';
session_start();

$message = '';

if(isset($_POST['reset'])){
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        // Generate a reset token
        $token = bin2hex(random_bytes(50));

        // Save token in database (create a column 'reset_token' in users table)
        $sql_token = "UPDATE users SET reset_token=? WHERE email=?";
        $stmt_token = $conn->prepare($sql_token);
        $stmt_token->bind_param("ss", $token, $email);
        $stmt_token->execute();

        // Normally, send the reset link via email
        // For testing, just show the link
        $message = "Reset link: <a href='reset_password.php?token=$token'>Click here to reset password</a>";
    } else {
        $message = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - Cosmetic Mart</title>
</head>
<body>
<h2>Forgot Password</h2>
<?php if($message != '') echo "<p>$message</p>"; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required><br><br>
    <button name="reset">Send Reset Link</button>
</form>
<a href="login.php">Back to Login</a>
</body>
</html>
