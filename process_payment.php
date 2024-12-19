<?php
session_start();

// Set timezone to WIB (Asia/Jakarta)
date_default_timezone_set('Asia/Jakarta');

// Validate if form was submitted
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: cart.php");
    exit();
}

// Get form data
$payment_method = $_POST['payment_method'] ?? '';
$name = $_POST['name'] ?? '';  
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$pickup_method = $_POST['pickup_method'] ?? '';

// Calculate total from cart
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Generate order ID
$order_id = 'STS' . date('YmdHis') . rand(100, 999);

// Get current timestamp
$order_date = date('Y-m-d H:i:s');

/// Database connection
$servername = "localhost";
$username = "root";
$password = "rahasia";
$dbname = "stos";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert order log
$sql = "INSERT INTO order_logs (order_id, order_date, payment_method, pickup_method, total, customer_name, phone)
        VALUES ('$order_id', '$order_date', '$payment_method', '$pickup_method', $total, '$name', '$phone')";

if ($conn->query($sql) === TRUE) {
    echo "Order logged successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Surplus to Solution</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .success-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            text-align: center;
            background: #FFF5F5;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            width: 150px;  
            height: 150px; 
            margin: 2rem auto 2rem; 
            margin-top: 4rem; 
        }

        .success-title {
            color: #FF5B22;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .success-message {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .order-details {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: left;
        }

        .order-id {
            color: #FFA41B;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .detail-group {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            font-weight: bold;
            color: #FF5B22;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .action-button {
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .primary-button {
            background: #FFA41B;
            color: white;
            border: none;
        }

        .primary-button:hover {
            background: #FF5B22;
        }

        .secondary-button {
            background: white;
            color: #FF5B22;
            border: 2px solid #FF5B22;
        }

        .secondary-button:hover {
            background: #FFF5F5;
        }

        .divider {
            border-top: 2px dashed #FFD93D;
            margin: 2rem 0;
        }

        .payment-instructions {
            background: #FFFBF2;
            padding: 1.5rem;
            border-radius: 15px;
            margin-top: 2rem;
            text-align: left;
        }

        .instruction-title {
            color: #FF5B22;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .instruction-steps {
            list-style-type: none;
            padding: 0;
        }

        .instruction-steps li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: flex-start;
        }

        .step-number {
            background: #FFA41B;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar Section -->
    <section class="navbar">
        <div class="navbar clearfix">
            <div class="logo">
                <a href="index.html">
                    <img src="images/StoS-logo.png" alt="Logo" class="img-responsive">
                </a>
            </div>
            <div class="menu">
                <ul>
                    <p><a href="#">Home</a></p>
                    <p><a href="#">Offers And Promo</a></p>
                    <p><a href="#">Orders</a></p>
                    <p><a href="#">Privacy Policy</a></p>
                    <p><a href="#">Donation</a></p>
                    <li class="cart-icon">
                        <a href="cart.php" class="cart-icon">
                            <img src="images/icon-basket.png" alt="Cart">
                            Cart (0)
                        </a>
                    </li>
                    <li class="profile-item">
                        <a href="#" class="profile-icon">
                            <img src="images/icon-profile.png" alt="Profile"> 
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <div class="success-container">
        <!-- Success Message -->
        <img src="https://cdn-icons-png.flaticon.com/512/5839/5839078.png" alt="Success" class="success-icon">
        <h1 class="success-title">Order Successfully Placed!</h1>
        <p class="success-message">Thank you for your order. We'll process it right away!</p>

        <!-- Order Details -->
        <div class="order-details">
            <p class="order-id">Order ID: <?php echo $order_id; ?></p>
            
            <div class="detail-group">
                <p class="detail-label">Order Date:</p>
                <p><?php echo $order_date; ?></p>
            </div>

            <div class="detail-group">
                <p class="detail-label">Payment Method:</p>
                <p><?php echo ucfirst($payment_method); ?></p>
            </div>

            <div class="detail-group">
                <p class="detail-label">Phone Number:</p>
                <p><?php echo $phone; ?></p>
            </div>

            <div class="detail-group">
                <p class="detail-label">Delivery Method:</p>
                <p><?php echo ucfirst($pickup_method); ?></p>
            </div>

            <div class="detail-group">
                <p class="detail-label">Total Amount:</p>
                <p>Rp<?php echo number_format($total, 0, ',', '.'); ?></p>
            </div>

        </div>

        <!-- Payment Instructions -->
        <div class="payment-instructions">
            <h3 class="instruction-title">Payment Instructions</h3>
            <ul class="instruction-steps">
                <li>
                    <span class="step-number">1</span>
                    Open your <?php echo strtoupper($payment_method); ?> app
                </li>
                <li>
                    <span class="step-number">2</span>
                    Scan the QR code or enter the payment details manually
                </li>
                <li>
                    <span class="step-number">3</span>
                    Enter the amount: Rp<?php echo number_format($total, 0, ',', '.'); ?>
                </li>
                <li>
                    <span class="step-number">4</span>
                    Complete the payment within 24 hours
                </li>
            </ul>
        </div>

        <div class="divider"></div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="index.html" class="action-button primary-button">Back to Home</a>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="footer-section">
                <h3>Surplus to Solution</h3>
                <p>Let's Connect!</p>
                <div class="social-icons">
                    <a href="#"><img src="images/fb.png" alt="Facebook"></a>
                    <a href="#"><img src="images/ig.png" alt="Instagram"></a>
                    <a href="#"><img src="images/x.png" alt="Twitter"></a>
                </div>
            </div>            
            <div class="footer-section">
                <h3>About Us</h3>
                <p><a href="#">About Us</a></p>
                <p><a href="#">Service Us</a></p>
                <p><a href="#">Contact</a></p>
                <p><a href="#">Company</a></p>
            </div>
            <div class="footer-section">
                <h3>Company</h3>
                <p><a href="#">Partnership</a></p>
                <p><a href="#">Terms of Use</a></p>
                <p><a href="#">Privacy</a></p>
                <p><a href="#">Sitemap</a></p>
            </div>
        </div>
    </footer>
</body>
</html>
