<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}

$error = '';
$success = '';

// Handle form submission
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);

    // Handle image upload
   // Handle image upload
$image_path = '';
if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
    $image_name = basename($_FILES['image']['name']);
    
    // Filesystem path to save the file
    $target_dir = "../assets/image/"; // admin/ is one level deep
    if(!is_dir($target_dir)){
        mkdir($target_dir, 0777, true); // create folder if it doesn't exist
    }
    $target_file = $target_dir . $image_name;

    if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)){
        // Web path to store in DB
        $image_path = "assets/image/" . $image_name;
    } else {
        $error = "Failed to upload image!";
    }
} else {
    $error = "Please upload an image!";
}


    if($error == ''){
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image_path);

        if($stmt->execute()){
            $success = "Product added successfully!";
        } else {
            $error = "Error adding product!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #ffe4ec;
            margin: 0;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
        }

        h2 {
            color: #FF1493;
            margin-bottom: 20px;
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: white;
            background: #FF69B4;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            transition: 0.3s;
        }

        a.back-link:hover {
            background: #FF1493;
            transform: scale(1.05);
        }

        form {
            background: #fff0f5;
            padding: 30px;
            border-radius: 20px;
            display: inline-block;
            text-align: left;
            box-shadow: 0 5px 15px rgba(255,105,180,0.2);
            width: 90%;
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #FF1493;
        }

        input[type=text],
        input[type=number],
        input[type=file],
        textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 10px;
            border: 1px solid #ff69b4;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }

        input[type=text]:focus,
        input[type=number]:focus,
        textarea:focus,
        input[type=file]:focus {
            border-color: #ff1493;
            box-shadow: 0 0 10px rgba(255,20,147,0.2);
        }

        textarea {
            resize: vertical;
        }

        input[type=submit] {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #FF69B4;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type=submit]:hover {
            background: #FF1493;
            transform: scale(1.05);
        }

        .error, .success {
            font-weight: bold;
            margin: 10px 0;
        }

        .error { color: red; }
        .success { color: green; }

        @media(max-width: 480px){
            form { width: 95%; }
        }
    </style>
</head>
<body>
    <h2>Add New Product</h2>
    <a href="view_products.php" class="back-link">Back to Products</a>

    <?php if($error != '') echo "<p class='error'>$error</p>"; ?>
    <?php if($success != '') echo "<p class='success'>$success</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Image:</label>
        <input type="file" name="image" required>

        <input type="submit" name="add" value="Add Product">
    </form>
</body>
</html>
