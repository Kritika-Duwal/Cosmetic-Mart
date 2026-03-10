<?php
session_start();
include '../includes/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Check if product ID is passed
if(!isset($_GET['id'])){
    header("Location: view_products.php");
    exit();
}

$product_id = intval($_GET['id']);
$error = '';
$success = '';

// Fetch product details
$sql = "SELECT * FROM products WHERE id=$product_id";
$result = $conn->query($sql);

if($result->num_rows != 1){
    header("Location: view_products.php");
    exit();
}

$product = $result->fetch_assoc();

// Handle form submission
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);

    // Handle image upload
   // Handle image upload
$image_path = $product['image']; // default: existing image

if(isset($_FILES['image']) && $_FILES['image']['name'] != '') {
    $image_name = basename($_FILES['image']['name']);

    // Filesystem path to save file (go one level up from admin/)
    $target_dir = "../assets/image/"; 
    if(!is_dir($target_dir)){
        mkdir($target_dir, 0777, true); // create folder if not exists
    }
    $target_file = $target_dir . $image_name;

    // Move uploaded file
    if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Web path to store in database
        $image_path = "assets/image/" . $image_name;
    } else {
        $error = "Failed to upload image!";
    }
}


    // Update product in database
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_path, $product_id);

    if($stmt->execute()){
        $success = "Product updated successfully!";
        // Refresh product info
        $sql = "SELECT * FROM products WHERE id=$product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
    } else {
        $error = "Error updating product!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - Admin Dashboard</title>
    <style>
        body { font-family: Arial; text-align:center; margin-top:30px; }
        input, textarea { padding: 5px; margin: 5px 0; width: 300px; }
        input[type=submit] { background:#FF69B4; color:white; border:none; border-radius:5px; cursor:pointer; }
        input[type=submit]:hover { background:#FF1493; }
        .error { color:red; }
        .success { color:green; }
    </style>
</head>
<body>
    <h2>Edit Product</h2>
    <p><a href="view_products.php">Back to Products</a></p>

    <?php if($error != '') echo "<p class='error'>$error</p>"; ?>
    <?php if($success != '') echo "<p class='success'>$success</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Product Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

        <label>Description:</label><br>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br>

        <label>Current Image:</label><br>
        <img src="../<?php echo $product['image']; ?>" width="100"><br>
        <label>Change Image:</label><br>
        <input type="file" name="image"><br><br>

        <input type="submit" name="update" value="Update Product">
    </form>
</body>
</html>
