<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffe4ec; /* light pink background */
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #FF1493;
            margin-bottom: 20px;
        }

        .top-links {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-links a {
            margin: 0 10px;
            text-decoration: none;
            background-color: #FF69B4;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }

        .top-links a:hover {
            background-color: #FF1493;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #FFB6C1; /* column lines */
            text-align: center;
        }

        th {
            background-color: #FF69B4;
            color: white;
        }

        tr:hover {
            background-color: #ffe0f0;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .btn {
            padding: 5px 10px;
            background: #FF69B4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #FF1493;
        }

        @media screen and (max-width: 600px) {
            table, th, td {
                font-size: 12px;
            }

            img {
                width: 50px;
                height: 50px;
            }

            .top-links a {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <h2>View Products</h2>
    <div class="top-links">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <?php
    if($result->num_rows > 0){
        echo '<table>
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>';

        $sn = 1; // Initialize serial number
        while($row = $result->fetch_assoc()){
            echo '<tr>
                <td>'.$sn.'</td>
                <td>'.$row['name'].'</td>
                <td style="max-width:250px; white-space:normal;">'.$row['description'].'</td>
                <td>Rs '.$row['price'].'</td>
                <td><img src="../'.$row['image'].'" alt="'.$row['name'].'"></td>
                <td>
                    <a class="btn" href="edit_product.php?id='.$row['id'].'">Edit</a>
                    <a class="btn" href="delete_product.php?id='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                </td>
            </tr>';
            $sn++; // Increment serial number
        }
        echo '</table>';
    } else {
        echo "<p style='text-align:center; color:#FF1493; font-weight:bold;'>No products available!</p>";
    }
    ?>
</body>
</html>
