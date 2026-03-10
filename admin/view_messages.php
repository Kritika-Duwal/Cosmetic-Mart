<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - User Messages</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h1 { color: #FF69B4; text-align: center; }
        a { text-decoration: none; padding: 10px 15px; background: #FF69B4; color: white; border-radius: 5px; margin: 5px; display: inline-block; }
        a:hover { background: #FF1493; }
        table { width: 90%; margin: auto; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #ffe4ec; color: #ff1493; }
        td { background-color: #fff0f5; }
    </style>
</head>
<body>

<h1>User Messages</h1>
<div style="text-align:center;">
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <a href="admin_logout.php">Logout</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Message</th>
        <th>Sent At</th>
    </tr>
    <?php
    $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['name']."</td>
                    <td>".$row['email']."</td>
                    <td>".$row['phone']."</td>
                    <td>".htmlspecialchars($row['message'])."</td>
                    <td>".$row['created_at']."</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No messages received.</td></tr>";
    }
    ?>
</table>

</body>
</html>
