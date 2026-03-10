<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

// Handle search query
$search_query = '';
if(isset($_GET['search'])){
    $search_query = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $like = "%$search_query%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all products
    $result = $conn->query("SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products - Cosmetic Mart</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #ffe4ec; margin: 0; padding: 0; text-align: center; }
        h1 { color: #ff1493; margin-top: 20px; }

        /* Big Home and Cart Buttons */
        .top-links {
            margin: 20px 0;
        }
        .top-links a {
            display: inline-block;
            padding: 15px 30px;
            margin: 0 10px;
            background: #FF69B4;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
        }
        .top-links a:hover {
            background: #FF1493;
            transform: scale(1.1);
        }

        /* Search Bar */
        .search-box { margin: 20px 0; }
        .search-box input[type=text] {
            padding: 10px 15px;
            width: 250px;
            border-radius: 50px;
            border: 2px solid #ff69b4;
            outline: none;
            font-size: 16px;
            transition: 0.3s;
        }
        .search-box input[type=text]:focus {
            border-color: #ff1493;
            box-shadow: 0 0 10px rgba(255,20,147,0.5);
        }
        .search-box input[type=submit] {
            padding: 10px 20px;
            background:#FF69B4;
            color:white;
            border:none;
            border-radius:50px;
            cursor:pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .search-box input[type=submit]:hover {
            background:#FF1493;
            transform: scale(1.05);
        }

        /* Product Container */
        .products-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px; }

        .product {
            background: #fff0f5;
            border-radius: 15px;
            padding: 15px;
            width: 220px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .product img { width: 180px; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 10px; }
        .product h3 { color: #ff1493; margin-bottom: 5px; font-size: 18px; }
        .product p { color: #555; font-size: 14px; margin: 5px 0; }
        .add-cart { padding: 8px 15px; background: #FF69B4; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px; font-weight: bold; }
        .add-cart:hover { background: #FF1493; }

        @media(max-width: 600px){
            .product { width: 90%; }
            .product img { width: 100%; height: auto; }
            .top-links a { padding: 12px 25px; font-size: 16px; margin: 5px; }
            .search-box input[type=text] { width: 70%; }
        }
    </style>
</head>
<body>

<h1>Our Products</h1>

<!-- Big Home and Cart Buttons -->
<div class="top-links">
    <a href="index.php">Home</a>
    <a href="cart.php">View Cart</a>
</div>

<!-- Search Form -->
<div class="search-box">
    <form method="get">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
        <input type="submit" value="Search">
    </form>
</div>

<div class="products-container">
<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        ?>
        <div class="product">
            <img src="../<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <p><strong>Rs <?php echo $row['price']; ?></strong></p>
            <a class="add-cart" href="add_to_cart.php?product_id=<?php echo $row['id']; ?>">Add to Cart</a>
        </div>
        <?php
    }
} else {
    echo "<p>No products found!</p>";
}
?>
</div>

</body>
</html>
