<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_page.php");
    exit;
}

// Include database connection
require 'db.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update profile information
    if (isset($_POST['update_profile'])) {
        $new_username = htmlspecialchars($_POST['username']);
        $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

        $stmt = $conn->prepare("UPDATE users SET username = ?, password = COALESCE(?, password) WHERE id = ?");
        $stmt->bind_param("ssi", $new_username, $new_password, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username; // Update session variable
            echo "<script>alert('Profile updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating profile. Please try again.');</script>";
        }
        $stmt->close();
    }

    // Update address
    if (isset($_POST['update_address'])) {
        $new_address = htmlspecialchars($_POST['address']);

        $stmt = $conn->prepare("UPDATE users SET address = ? WHERE id = ?");
        $stmt->bind_param("si", $new_address, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo "<script>alert('Address updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating address. Please try again.');</script>";
        }
        $stmt->close();
    }

    // Update payment method
    if (isset($_POST['update_payment'])) {
        $new_payment_method = htmlspecialchars($_POST['payment_method']);

        $stmt = $conn->prepare("UPDATE users SET payment_method = ? WHERE id = ?");
        $stmt->bind_param("si", $new_payment_method, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo "<script>alert('Payment method updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating payment method. Please try again.');</script>";
        }
        $stmt->close();
    }
}

// Retrieve updated user data
$stmt = $conn->prepare("SELECT username, address, payment_method FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($user = $result->fetch_assoc()) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['address'] = $user['address'];
    $_SESSION['payment_method'] = $user['payment_method'];
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Artisan's Touch</title>
    <link rel="stylesheet" href="css/profile.css">
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
    <section class="profile-section">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>

        <!-- Update Profile Information -->
        <form method="POST" class="profile-form">
            <h3>Update Profile</h3>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <!-- Update Address -->
        <form method="POST" class="profile-form">
            <h3>Update Address</h3>
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($_SESSION['address']); ?>" placeholder="Enter your address">
            <button type="submit" name="update_address">Update Address</button>
        </form>

        <!-- Update Payment Method -->
        <form method="POST" class="profile-form">
            <h3>Update Payment Method</h3>
            <label for="payment_method">Payment Method</label>
            <input type="text" id="payment_method" name="payment_method" value="<?php echo htmlspecialchars($_SESSION['payment_method']); ?>" placeholder="e.g., Visa, MasterCard">
            <button type="submit" name="update_payment">Update Payment</button>
        </form>

        <!-- Logout -->
        <a href="logout.php" class="btn-logout">Logout</a>
    </section>
</main>

    <script src="js/nav.js"></script>
</body>
</html>