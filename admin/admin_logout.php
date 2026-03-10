<?php
session_start();

// Clear all admin session variables
session_unset();
session_destroy();

// Redirect to admin login page
header("Location: ../auth/admin_login.php");
exit();
?>

