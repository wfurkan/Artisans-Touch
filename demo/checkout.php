<?php
session_start();

// Redirect if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Calculate grand total
require 'db.php';
$grand_total = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($sql);

    while ($product = $result->fetch_assoc()) {
        $grand_total += $product['price'] * $_SESSION['cart'][$product['id']];
    }
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $payment_method = htmlspecialchars($_POST['payment_method']);
    $card_number = htmlspecialchars($_POST['card_number']);
    $card_expiry = htmlspecialchars($_POST['card_expiry']);
    $card_cvv = htmlspecialchars($_POST['card_cvv']);

    // Check if the user is logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Save order details in the database
    $sql = "INSERT INTO orders (user_id, name, email, address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("issssd", $user_id, $name, $email, $address, $payment_method, $grand_total);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get the last inserted order ID
        $stmt->close();

        // Save order items
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("iii", $order_id, $product_id, $quantity);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect to confirmation page
        header("Location: confirmation.php");
        exit;
    } else {
        echo "<script>alert('Error processing your order. Please try again.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
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
                <li><a href="products2.php">Ceramics </a></li>
                <li><a href="products3.php">Decor</a></li>
                <li><a href="products4.php">Jewelry </a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Checkout</h1>
        <h3>Total Amount: $<?php echo number_format($grand_total, 2); ?></h3> <!-- Display Grand Total -->
        <form action="checkout.php" method="POST" class="checkout-form">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="address">Shipping Address</label>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" pattern="\d{16}" required>
            </div>
            <div class="form-group">
                <label for="card_expiry">Card Expiry</label>
                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" pattern="\d{2}/\d{2}" required>
            </div>
            <div class="form-group">
                <label for="card_cvv">Card CVV</label>
                <input type="text" id="card_cvv" name="card_cvv" pattern="\d{3}" required>
            </div>
            <button type="submit" class="btn-checkout">Place Order</button>
        </form>
    </main>

    <script src="js/nav.js"></script>
</body>
</html>
