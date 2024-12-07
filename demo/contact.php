<?php
// Handle the contact form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firstname'])) {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $country = htmlspecialchars($_POST['country']);
    $subject = htmlspecialchars($_POST['subject']);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "artisans_touch");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into the contact_form table
    $sql = "INSERT INTO contact_form (firstname, lastname, email, phone, country, subject) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $phone, $country, $subject);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for contacting us. We will get back to you soon!');</script>";
    } else {
        echo "<script>alert('Failed to submit your request. Please try again later.');</script>";
    }

    $stmt->close();
}

// Handle newsletter subscription
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subscribe_email'])) {
    $subscribe_email = htmlspecialchars($_POST['subscribe_email']);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "artisans_touch");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into the newsletter table
    $sql = "INSERT INTO newsletter (email) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subscribe_email);

    if ($stmt->execute()) {
        echo "<script>alert('You have successfully subscribed to our newsletter!');</script>";
    } else {
        echo "<script>alert('This email is already subscribed or something went wrong.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Artisan's Touch | Contact</title>
    <meta name="author" content="Furkan Bilgi">
    <meta name="description" content="Artisan's Touch Contact Page - Get in touch with us for inquiries, feedback, or customer support.">

    <link rel="stylesheet" href="css/contact.css">
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
                <li><a href="products2.php">Ceramics</a></li>
                <li><a href="products3.php">Decor</a></li>
                <li><a href="products4.php">Jewelry</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </header>
    <section class="intro">
        <div class="intro-content">
            <h2>We're Here for You</h2>
            <p>Whether you have questions, feedback, or just want to know more about us, feel free to reach out. We love connecting with our customers.</p>
            <img src="img/store.jpg" alt="Artisan's Touch Store" style="width:30%" class="intro-img">
        </div>
    </section>
    <section class="contact-section">
        <div style="text-align:center">
            <h2>Get in Touch</h2>
            <p>We'd love to hear from you. Send us a message below.</p>
        </div>
        <div class="row">
            <div class="column">
                <img src="img/store_location.jpg" alt="Our Store Location" style="width:80%">
            </div>
            <div class="column">
                <form action="contact.php" method="post">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="firstname" placeholder="Your first name.." required>
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lastname" placeholder="Your last name.." required>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Your email address.." required>
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Your phone number..">
                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="usa">USA</option>
                        <option value="canada">Canada</option>
                        <option value="australia">Australia</option>
                        <option value="other">Other</option>
                    </select>
                    <label for="subject">Subject</label>
                    <textarea id="subject" name="subject" placeholder="Write your message here..." style="height:200px"></textarea>
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
    </section>
    <section class="info-section">
        <div class="info-box">
            <h3>Our Location</h3>
            <p>123 Artisan Street, New York, NY, USA</p>
            <h3>Email</h3>
            <p>support@artisanstouch.com</p>
            <h3>Phone</h3>
            <p>+1 555-123-4567</p>
            <h3>Working Hours</h3>
            <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
        </div>
    </section>
    <footer>
        <div class="footer-content">
            <div class="social-media">
                <h4>Follow Us</h4>
                <a href="https://www.facebook.com/"><img src="png/facebook_icon.png" alt="Facebook"></a>
                <a href="https://www.x.com/"><img src="png/twitter_icon.png" alt="Twitter"></a>
                <a href="https://www.instagram.com/"><img src="png/instagram_icon.png" alt="Instagram"></a>
            </div>
            <div class="newsletter">
                <h4>Join Our Newsletter</h4>
                <p>Stay updated with the latest news, deals, and handcrafted collections.</p>
                <form method="post" action="contact.php">
                    <input type="email" name="subscribe_email" placeholder="Your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <p>&copy; 2024 Artisan's Touch. All rights reserved.</p>
    </footer>
    <script src="js/nav.js"></script>
</body>
</html>

