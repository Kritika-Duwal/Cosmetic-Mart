<?php
include 'includes/db_connect.php';
session_start();

$message = '';

if(!isset($_GET['token'])){
    die("Invalid or missing token!");
}

$token = $_GET['token'];

if(isset($_POST['update'])){
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        $message = "Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password=?, reset_token=NULL WHERE reset_token=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed, $token);
        if($stmt->execute()){
            $message = "Password updated successfully! <a href='login.php'>Login now</a>";
        } else {
            $message = "Error updating password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Cosmetic Mart</title>
</head>
<body>
<h2>Reset Password</h2>
<?php if($message != '') echo "<p>$message</p>"; ?>

<form method="POST">
    <input type="password" name="password" placeholder="New Password" required><br><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
    <button name="update">Update Password</button>
</form>
</body>
</html>
