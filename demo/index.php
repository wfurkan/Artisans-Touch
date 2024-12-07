<?php
session_start();
require 'db.php'; // Include database connection

// Initialize cart session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle "Add to Cart" action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++; // Increase quantity if product is already in the cart
    } else {
        $_SESSION['cart'][$product_id] = 1; // Add new product to the cart
    }

    // Redirect to cart.php after adding the product
    header('Location: cart.php');
    exit;
}

// Fetch featured products from the database
$sql_featured = "SELECT * FROM products LIMIT 3";
$result_featured = $conn->query($sql_featured);
$featured_products = $result_featured->fetch_all(MYSQLI_ASSOC);

// Fetch best sellers from the database
$sql_best_sellers = "SELECT * FROM products ORDER BY id DESC LIMIT 3";
$result_best_sellers = $conn->query($sql_best_sellers);
$best_sellers = $result_best_sellers->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Artisan's Touch | Home</title>
    <meta name="author" content="Furkan Bilgi">
    <meta name="description" content="Artisan's Touch Home Page - Discover Handcrafted Art & Culture and Our Best Sellers">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/nav.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="logo-title">
            <a href="index.php">
                <img src="img/logo.png" alt="Artisan's Touch Logo" style="width:80px; height:auto;">
            </a>
            <h1>Artisan's Touch</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="products1.php">Textiles</a></li>
                <li><a href="products2.php">Ceramics</a></li>
                <li><a href="products3.php">Decor</a></li>
                <li><a href="products4.php">Jewelry</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Discover Handcrafted Art & Culture</h2>
        <p>Explore our unique collection of culturally inspired home décor and accessories from around the world.</p>
        <button class="cta-button" onclick="window.location.href='products1.php'">Shop Now</button>
    </section>

    <!-- Featured Products -->
    <section class="products">
        <h3>Featured Products</h3>
        <div class="product-grid">
            <?php foreach ($featured_products as $product): ?>
            <div class="product">
                <img src="<?php echo $product['img']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:85%">
                <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <form action="index.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="buy-button">Add to Cart</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <section class="products">
        <h3>Our Best Sellers</h3>
        <div class="product-grid">
            <?php foreach ($best_sellers as $product): ?>
            <div class="product">
                <img src="<?php echo $product['img']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:85%">
                <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <form action="index.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="buy-button">Add to Cart</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Artisan's Touch. All rights reserved.</p>
    </footer>

    <script src="js/nav.js"></script>
</body>

</html>

