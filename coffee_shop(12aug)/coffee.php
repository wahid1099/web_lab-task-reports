<?php
session_start();
$conn = new mysqli("localhost", "root", "", "labtask");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$user_email = $user["email"] ?? "Guest";
// $user_id = $user["id"];
$user_serial = $user["serial"] ?? ""; // assuming serial is stored in session

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_name"])) {
    $product_name = $_POST["product_name"];
    $price = $_POST["price"];

    $stmt = $conn->prepare("INSERT INTO orders (serial, item, amount, date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isd", $user_serial, $product_name, $price);
    if ($stmt->execute()) {
        $message = "✅ Order placed for {$product_name}!";
    } else {
        $message = "❌ Failed to place order.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          crossorigin="anonymous" />
    <link href="style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet" />
    <title>Shop With Me</title>
</head>
<body>
<nav class="navbar">
    <div class="nav-brand">
        <h1>Shop With Me</h1>
    </div>
    <div class="nav-menu">
        <ul class="menu-items">
            <li class="dropdown">
                <a href="#">Categories <i class="fas fa-chevron-down"></i></a>
                <ul class="dropdown-content">
                    <li><a href="#">Electronics</a></li>
                    <li><a href="#">Fashion</a></li>
                    <li><a href="#">Home & Living</a></li>
                    <li><a href="#">Books</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#">Deals <i class="fas fa-chevron-down"></i></a>
                <ul class="dropdown-content">
                    <li><a href="#">Today's Deals</a></li>
                    <li><a href="#">Clearance</a></li>
                    <li><a href="#">Bundle Offers</a></li>
                </ul>
            </li>
          
            <li><a href="feedback.html">Feedback</a></li>
        </ul>
    </div>
    <div class="nav-right">
        <div class="search-container">
            <input type="text" placeholder="Search products..." class="search-input" />
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>
        <div class="nav-icons">
            <!-- <div class="icon-item">
                <i class="far fa-heart"></i>
                <span class="icon-label">Wishlist</span>
            </div> -->
            <div class="icon-item cart-icon">
                <a href="order_history.php" style="color:black;">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="cart-count">0</span>
                    <span class="icon-label">Cart</span>
                </a>
            </div>
        </div>
        <div class="user-info" style="margin-left:20px; color:black;">
            <i class="fas fa-user"></i> welcome <?= htmlspecialchars($user_serial) ?>
            | <a href="logout.php" style="color:black;">Logout</a>
        </div>
    </div>
</nav>

<div class="banner">
    <div class="banner-slider">
        <img src="banner.jpg" alt="Banner Image" />
        <div class="banner-text">
            <h2>Welcome to Our Shop</h2>
            <p>Discover Amazing Deals Today!</p>
            <button class="cta-button">Shop Now</button>
        </div>
    </div>
    <div class="banner-features">
        <div class="feature">
            <i class="fas fa-truck"></i>
            <span>Free Shipping</span>
        </div>
        <div class="feature">
            <i class="fas fa-undo"></i>
            <span>Easy Returns</span>
        </div>
        <div class="feature">
            <i class="fas fa-shield-alt"></i>
            <span>Secure Payment</span>
        </div>
    </div>
</div>

<?php if ($message): ?>
    <div style="background:#dff0d8;color:#3c763d;padding:10px;text-align:center;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="product-container" id="product-container">
    <?php
    $products = [
        ["name" => "Espresso", "price" => 5, "image" => "images.jpeg"],
        ["name" => "Latte", "price" => 6, "image" => "latte.jpg"],
        ["name" => "Cappuccino", "price" => 6, "image" => "Cappuccino.jpg"],
        ["name" => "Mocha", "price" => 7, "image" => "Mocha.jpeg"]
    ];
    foreach ($products as $p): ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($p["image"]) ?>" alt="<?= htmlspecialchars($p["name"]) ?>" class="product-img">
            <h3><?= htmlspecialchars($p["name"]) ?></h3>
            <p>$<?= $p["price"] ?></p>
            <form method="post" style="margin-top:10px;">
                <input type="hidden" name="product_name" value="<?= htmlspecialchars($p["name"]) ?>">
                <input type="hidden" name="price" value="<?= $p["price"] ?>">
                <button type="submit" class="order-btn" style="
                    background: linear-gradient(90deg, #ff9800 0%, #ff5722 100%);
                    color: #fff;
                    border: none;
                    border-radius: 25px;
                    padding: 10px 28px;
                    font-size: 1rem;
                    font-weight: 500;
                    box-shadow: 0 4px 12px rgba(255,87,34,0.15);
                    cursor: pointer;
                    transition: background 0.3s, transform 0.2s;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                " onmouseover="this.style.background='linear-gradient(90deg,#ff5722 0%,#ff9800 100%)';this.style.transform='scale(1.05)';" onmouseout="this.style.background='linear-gradient(90deg,#ff9800 0%,#ff5722 100%)';this.style.transform='scale(1)';">
                    <i class="fas fa-mug-hot"></i> Order
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About Us</h3>
            <p>Your trusted online shopping destination</p>
        </div>
        <div class="footer-section">
            <h3>Contact</h3>
            <p>Email: support@shopwithme.com</p>
            <p>Phone: (123) 456-7890</p>
        </div>
        <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Shop With Me. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
