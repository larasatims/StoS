<?php
session_start();
include('config.php');

// Verify account
function checkLogin($username, $password) {
    global $conn; 

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0; // Mengembalikan true jika akun ditemukan
}


// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Get selected items from cart
$selectedItems = $_SESSION['cart'];
$total = 0;
foreach ($selectedItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Menangani proses ketika form checkout disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memeriksa login berdasarkan username dan password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Jika akun tidak valid, tampilkan pesan error
    if (!checkLogin($username, $password)) {
        $error = "Username atau Password tidak valid!";
    } else {
        // Lanjutkan ke proses pembayaran atau pengolahan lebih lanjut
        header('Location: process_payment.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Surplus to Solution</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .payment-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .payment-title {
            font-size: 2.5rem;
            color: #FFA41B;
            margin-bottom: 2rem;
            position: relative;
        }

        .payment-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 3px;
            background: #FF5B22;
            position: absolute;
            bottom: -5px;
        }

        .payment-section {
            background: #FFF5F5;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-title {
            color: #FF5B22;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .selected-items {
            margin-bottom: 1.5rem;
        }

        .item-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 1rem;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .payment-method.selected {
            border-color: #FFA41B;
        }

        .payment-method img {
            width: 80px; 
            height: 40px; 
            object-fit: contain; 
            margin-bottom: 0.5rem;
        }

        .address-form input,
        .address-form textarea {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .pickup-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .pickup-option {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .pickup-option.selected {
            border-color: #FFA41B;
        }

        .checkout-button {
            background: #FFA41B;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 2rem;
        }

        .checkout-button:hover {
            background: #FF5B22;
        }

        .total-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .total-amount {
            font-size: 1.5rem;
            color: #FF5B22;
            font-weight: bold;
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
                            Cart (<span><?php echo count($_SESSION['cart']); ?></span>)
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

    <div class="payment-container">
        <h1 class="payment-title">Checkout</h1>

        <form action="process_payment.php" method="POST">
            <!-- Selected Items Section -->
            <div class="payment-section">
                <h2 class="section-title">Selected Items</h2>
                <div class="selected-items">
                    <?php foreach ($selectedItems as $item): ?>
                    <div class="item-card">
                        <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="item-image">
                        <div class="item-details">
                            <h3><?php echo $item['name']; ?></h3>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                            <p>Price: Rp<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Payment Method Section -->
            <div class="payment-section">
                <h2 class="section-title">Payment Method</h2>
                <div class="payment-methods">
                    <div class="payment-method" onclick="selectPayment(this)">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/1200px-Logo_dana_blue.svg.png" alt="DANA">
                        <p>DANA</p>
                        <input type="radio" name="payment_method" value="dana" required>
                    </div>
                    <div class="payment-method" onclick="selectPayment(this)">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/768px-Logo_ovo_purple.svg.png" alt="OVO">
                        <p>OVO</p>
                        <input type="radio" name="payment_method" value="ovo" required>
                    </div>
                    <div class="payment-method" onclick="selectPayment(this)">
                        <img src="https://assets.promediateknologi.id/crop/0x0:0x0/750x500/webp/photo/2023/08/03/GoPay-logo-vector-svg-eps-formats-free-download-Brandlogos_net-3997437626.png" alt="GoPay">
                        <p>GoPay</p>
                        <input type="radio" name="payment_method" value="gopay" required>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="payment-section">
                <h2 class="section-title">Delivery Address</h2>
                <div class="address-form">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="text" name="phone" placeholder="Phone Number" required>
                    <textarea name="address" placeholder="Complete Address" rows="3" required></textarea>
                </div>
            </div>

            <!-- Pickup Method Section -->
            <div class="payment-section">
                <h2 class="section-title">Pickup Method</h2>
                <div class="pickup-options">
                    <div class="pickup-option" onclick="selectPickup(this)">
                        <h3>Self Pickup</h3>
                        <p>Pick up your order at our store</p>
                        <input type="radio" name="pickup_method" value="self" required>
                    </div>
                    <div class="pickup-option" onclick="selectPickup(this)">
                        <h3>Delivery</h3>
                        <p>We'll deliver to your address</p>
                        <input type="radio" name="pickup_method" value="delivery" required>
                    </div>
                </div>
            </div>

            <!-- Total Section -->
            <div class="total-section">
                <p>Total Amount:</p>
                <p class="total-amount">Rp<?php echo number_format($total, 0, ',', '.'); ?></p>
            </div>

            <button type="submit" class="checkout-button">Proceed to Payment</button>
        </form>
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

    <script>
        function selectPayment(element) {
            // Remove selection from all payment methods
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('selected');
            });
            // Add selection to clicked method
            element.classList.add('selected');
            // Check the radio button
            element.querySelector('input[type="radio"]').checked = true;
        }

        function selectPickup(element) {
            // Remove selection from all pickup options
            document.querySelectorAll('.pickup-option').forEach(option => {
                option.classList.remove('selected');
            });
            // Add selection to clicked option
            element.classList.add('selected');
            // Check the radio button
            element.querySelector('input[type="radio"]').checked = true;
        }
    </script>
</body>
</html>
