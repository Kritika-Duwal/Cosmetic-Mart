
<?php
session_start();
require_once '../includes/db_connect.php'; // your DB connection (must create $conn)

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Fetch & sanitize inputs
    $first_name  = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $dob         = trim($_POST['dob'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $email       = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone       = trim($_POST['phone'] ?? '');
    $password    = $_POST['password'] ?? '';
    $confirm     = $_POST['confirm_password'] ?? '';
    $gender      = $_POST['gender'] ?? '';

    // Server-side validation
    if (
        $first_name === '' ||
        $last_name === '' ||
        $dob === '' ||
        $address === '' ||
        $email === '' ||
        $phone === '' ||
        $password === '' ||
        $confirm === '' ||
        $gender === ''
    ) {
        $error = "All required fields (except Middle Name) must be filled.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        // basic phone format: digits, +, -, spaces, parentheses
        $error = "Please enter a valid phone number (only digits, +, -, spaces allowed).";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Password complexity: length 12-16, at least one upper, one lower, one digit, one special
        $len = strlen($password);
        $complexity_ok = preg_match('/[A-Z]/', $password) &&
                         preg_match('/[a-z]/', $password) &&
                         preg_match('/[0-9]/', $password) &&
                         preg_match('/[\W_]/', $password) && // special char or underscore
                         ($len >= 12 && $len <= 16);

        if (!$complexity_ok) {
            $error = "Password must be 12–16 characters and include at least one uppercase letter, one lowercase letter, one number and one symbol.";
        } else {
            // Check duplicate email
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "This email is already registered.";
                $stmt->close();
            } else {
                $stmt->close();
                // Insert user (Passwords are never stored in plain text.)
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                // Note: adjust column names in DB as needed. This code expects:
                // users (id, first_name, middle_name, last_name, dob, address, email, phone, password, gender, created_at)
                $stmt2 = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, dob, address, email, phone, password, gender, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt2->bind_param("sssssssss", $first_name, $middle_name, $last_name, $dob, $address, $email, $phone, $hashed, $gender);

                if ($stmt2->execute()) {
                    $success = "Registration successful! You may now <a href='login.php'>log in</a>.";
                } else {
                    $error = "Registration failed: " . htmlspecialchars($conn->error);
                }
                $stmt2->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register - Cosmetic Mart</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f6f6f8; color:#222; }
        .container { max-width:700px; margin:40px auto; background:#fff; padding:22px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
        h1 { color:#ff6fbf; text-align:center; margin-bottom:10px; }
        form { display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; }
        label { font-size:13px; margin-bottom:4px; display:block; }
        input[type="text"], input[type="email"], input[type="date"], input[type="tel"], input[type="password"], textarea, select {
            width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; box-sizing:border-box;
        }
        textarea { resize:vertical; min-height:70px; grid-column: span 2; }
        .full { grid-column: span 2; }
        .note { font-size:13px; color:#666; margin-bottom:8px; }
        .actions { grid-column: span 2; text-align:center; margin-top:6px; }
        button { background:#ff69b4; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer; font-size:16px; }
        button:hover { background:#ff4a9a; }
        .error { background:#ffe6e9; color:#a00; padding:10px; border-radius:6px; margin-bottom:12px; }
        .success { background:#e6ffef; color:#086; padding:10px; border-radius:6px; margin-bottom:12px; }
        .pw-hint { font-size:13px; color:#444; margin-top:6px; grid-column: span 2; }
        .small { font-size:12px; color:#666; }
    </style>
</head>
<body>
<div class="container">
    <h1>Create Your Account</h1>

    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form id="regForm" method="post" novalidate>
        <!-- 1: Names -->
        <div>
            <label for="first_name">First Name *</label>
            <input id="first_name" name="first_name" type="text" required value="<?php echo isset($_POST['first_name'])?htmlspecialchars($_POST['first_name']):''; ?>">
        </div>
        <div>
            <label for="middle_name">Middle Name</label>
            <input id="middle_name" name="middle_name" type="text" value="<?php echo isset($_POST['middle_name'])?htmlspecialchars($_POST['middle_name']):''; ?>">
        </div>
        <div>
            <label for="last_name">Last Name *</label>
            <input id="last_name" name="last_name" type="text" required value="<?php echo isset($_POST['last_name'])?htmlspecialchars($_POST['last_name']):''; ?>">
        </div>

        <!-- 2: DOB -->
        <div class="full">
            <label for="dob">Date of Birth *</label>
            <input id="dob" name="dob" type="date" required value="<?php echo isset($_POST['dob'])?htmlspecialchars($_POST['dob']):''; ?>">
        </div>

        <!-- 3: Address -->
        <div class="full">
            <label for="address">Address *</label>
            <textarea id="address" name="address" required><?php echo isset($_POST['address'])?htmlspecialchars($_POST['address']):''; ?></textarea>
        </div>

        <!-- 4: Email -->
        <div>
            <label for="email">Email *</label>
            <input id="email" name="email" type="email" required value="<?php echo isset($_POST['email'])?htmlspecialchars($_POST['email']):''; ?>">
        </div>

        <!-- 5: Phone -->
        <div>
            <label for="phone">Phone Number *</label>
            <input id="phone" name="phone" type="tel" required value="<?php echo isset($_POST['phone'])?htmlspecialchars($_POST['phone']):''; ?>">
        </div>

        <!-- 6: Password -->
        <div>
            <label for="password">Password *</label>
            <input id="password" name="password" type="password" required>
        </div>

        <!-- 7: Confirm -->
        <div>
            <label for="confirm_password">Confirm Password *</label>
            <input id="confirm_password" name="confirm_password" type="password" required>
        </div>

        <div class="pw-hint">
            <strong>Password rules:</strong> 12–16 chars, at least one uppercase, one lowercase, one number and one symbol.
        </div>

        <!-- 8: Gender -->
        <div>
            <label for="gender">Gender *</label>
            <select id="gender" name="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="Male" <?php if(isset($_POST['gender']) && $_POST['gender']=='Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if(isset($_POST['gender']) && $_POST['gender']=='Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if(isset($_POST['gender']) && $_POST['gender']=='Other') echo 'selected'; ?>>Other</option>
                <option value="Prefer Not" <?php if(isset($_POST['gender']) && $_POST['gender']=='Prefer Not') echo 'selected'; ?>>Prefer not to say</option>
            </select>
        </div>

        <div class="actions">
            <button type="submit" name="register">Create Account</button>
            <p class="small">Already have account? <a href="login.php">Login</a></p>
        </div>
    </form>
</div>

<script>
// Client-side validation: password rules + confirm match + basic phone/email checks
document.getElementById('regForm').addEventListener('submit', function(e) {
    var pw = document.getElementById('password').value;
    var confirm = document.getElementById('confirm_password').value;
    var email = document.getElementById('email').value;
    var phone = document.getElementById('phone').value;

    var messages = [];

    // Email basic check
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) messages.push('Please enter a valid email.');

    // Phone basic
    var phonePattern = /^[0-9+\-\s()]{7,20}$/;
    if (!phonePattern.test(phone)) messages.push('Please enter a valid phone number.');

    // Password complexity
    var len = pw.length;
    if (len < 12 || len > 16) messages.push('Password must be 12–16 characters long.');
    if (!(/[A-Z]/.test(pw))) messages.push('Password must contain at least one uppercase letter.');
    if (!(/[a-z]/.test(pw))) messages.push('Password must contain at least one lowercase letter.');
    if (!(/[0-9]/.test(pw))) messages.push('Password must contain at least one number.');
    if (!(/[\W_]/.test(pw))) messages.push('Password must contain at least one symbol.');

    if (pw !== confirm) messages.push('Passwords do not match.');

    if (messages.length) {
        e.preventDefault();
        alert(messages.join("\\n"));
        return false;
    }

    return true;
});
</script>
</body>
</html>