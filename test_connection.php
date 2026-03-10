<?php
include 'includes/db_connect.php'; // make sure this path matches your folder

if(isset($conn)){
    echo "Database connection is working!";
} else {
    echo "Database connection failed!";
}
?>

