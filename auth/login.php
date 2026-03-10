<?php
session_start();
include '../includes/db_connect.php'; // connect to database

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in both email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("SELECT id, first_name, last_name, password FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $first_name, $last_name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = trim($first_name . ' ' . $last_name);
                header("Location: ../main/index.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login - Cosmetic Mart</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* body {
    font-family: 'Roboto', sans-serif;
    background: #ffe4ec;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.login-container {
    background: #fff0f5;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: center;
    width: 90%;
    max-width: 400px;
}
h2 {
    color: #ff1493;
    margin-bottom: 25px;
    font-size: 28px;
}
.password-input {
    width: 90%;
    border-radius: 50px;
    border: 1px solid #ff69b4;
    font-size: 16px;
    outline: none;
    transition: 0.3s;
}
.password-input:focus {
    border-color: #ff1493;
    box-shadow: 0 0 10px rgba(255,20,147,0.3);
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #ff69b4;
    font-size: 18px;
    user-select: none;
    transition: 0.3s;
}
.toggle-password:hover {
    color: #ff1493;
}
input {
    width: 90%;
    padding: 12px;
    margin: 12px 0;
    border-radius: 50px;
    border: 1px solid #ff69b4;
    font-size: 16px;
    outline: none;
    transition: 0.3s;
}
input:focus {
    border-color: #ff1493;
    box-shadow: 0 0 10px rgba(255,20,147,0.3);
}
button {
    width: 95%;
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
button:hover {
    background: #FF1493;
    transform: scale(1.05);
}
.error {
    color: red;
    margin-bottom: 15px;
    font-weight: bold;
}
a {
    color: #FF1493;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    text-decoration: underline;
}
p {
    margin: 10px 0;
}

.input-group {
    position: relative;
    width: 90%;
    margin: 12px auto;
} */

    body {
    font-family: 'Roboto', sans-serif;
    background: #ffe4ec;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-container {
    background: #fff0f5;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: center;
    width: 90%;
    max-width: 400px;
}

h2 {
    color: #ff1493;
    margin-bottom: 25px;
    font-size: 28px;
}

/* Input fields (email & password) */
input[type="email"],
.password-input {
    width: 100%;
    padding: 12px 45px 12px 15px; /* space for eye icon */
    border-radius: 50px;
    border: 1px solid #ff69b4;
    font-size: 16px;
    outline: none;
    transition: 0.3s;
    box-sizing: border-box;
    margin: 12px 0;
}

input:focus,
.password-input:focus {
    border-color: #ff1493;
    box-shadow: 0 0 10px rgba(255,20,147,0.3);
}

/* Make both fields same width by keeping same container width */
.input-group {
    position: relative;
    width: 100%; /* was 90%, changed to 100% for equal width */
    margin: 12px 0;
}

/* Eye icon */
.toggle-password {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #ff69b4;
    font-size: 18px;
    user-select: none;
    transition: 0.3s;
}

.toggle-password:hover {
    color: #ff1493;
}

/* Button */
button {
    width: 100%;
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

button:hover {
    background: #FF1493;
    transform: scale(1.05);
}

/* Error text */
.error {
    color: red;
    margin-bottom: 15px;
    font-weight: bold;
}

/* Links */
a {
    color: #FF1493;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

p {
    margin: 10px 0;
}


</style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php if($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

     <form method="POST" novalidate>
        <input type="email" name="email" placeholder="Email" required><br>

        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required class='password-input'>
            <span class="toggle-password" onclick="togglePassword()">
    <i id="toggleIcon" class="fa-solid fa-eye"></i>
</span>
        </div>

        <button name="login" type="submit">Login</button>
    </form>

    <p><a href="forgot_password.php">Forgot your password?</a></p>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {x
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}
</script>


</body>
</html>
