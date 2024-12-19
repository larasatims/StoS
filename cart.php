<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Combine similar items and sum their quantities
$consolidated_cart = [];
foreach ($_SESSION['cart'] as $item) {
    $key = $item['id'] . '-' . $item['name'];
    if (!isset($consolidated_cart[$key])) {
        $consolidated_cart[$key] = $item;
        $consolidated_cart[$key]['quantity'] = 1;
    } else {
        $consolidated_cart[$key]['quantity']++;
    }
}

// Handle quantity updates
if (isset($_POST['update_quantity'])) {
    $id = $_POST['item_id'];
    $name = $_POST['item_name'];
    $key = $id . '-' . $name;
    $quantity = $_POST['quantity'];
    
    // Update quantity logic
    if ($quantity > 0) {
        if (isset($consolidated_cart[$key])) {
            $consolidated_cart[$key]['quantity'] = $quantity;
        }
    } else {
        unset($consolidated_cart[$key]); // Remove item if quantity is 0
    }
}

// Rebuild cart session
$_SESSION['cart'] = array_values($consolidated_cart);

// Calculate total
$total = 0;
foreach ($consolidated_cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Surplus to Solution</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .cart-title {
            font-size: 2.5rem;
            color: #FFA41B;
            position: relative;
        }
        
        .cart-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 3px;
            background: #FF5B22;
            position: absolute;
            bottom: -5px;
        }
        
        .select-text {
            color: #FF5B22;
            font-size: 1.2rem;
        }

        /* Styles for the cart icon in the navbar */
        .cart-icon {
            background: none; /* Remove background */
            border: none; /* Remove border */
            border-radius: 0; /* Remove border radius */
            padding: 0; /* Remove padding if needed */
            display: flex; /* Keep it flexible if you want */
            align-items: center; /* Center icon vertically */
            cursor: pointer; 
        }

        .cart-icon img {
            border-radius: 0; /* Ensure the image doesn't have border radius */
        }
        
        .cart-item {
            background: #FFF5F5;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .cart-item.selected {
            border: 2px solid black;
        }
        
        .item-details {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex: 1;
        }
        
        .item-image {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .item-info {
            flex: 1;
        }
        
        .item-info h3 {
            color: #FF5B22;
            margin-bottom: 0.5rem;
        }
        
        .item-price {
            font-weight: bold;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-right: 2rem;
        }
        
        .quantity-btn {
            background: #FFA41B;
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity {
            background: #FFD93D;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            min-width: 40px;
            text-align: center;
        }
        
        .item-total {
            font-weight: bold;
            font-size: 1.2rem;
            min-width: 120px;
            text-align: right;
        }
        
        .checkout-container {
        background: white;
        padding: 1rem;
        display: flex;
        justify-content: flex-end;
        margin-top: 2rem; 
        }

        .checkout-btn {
            background: #FFA41B;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .checkout-btn:hover {
            background: #FF5B22;
        }

        .main-content {
            margin-bottom: 100px; 
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

    <!-- Cart Section -->
    <div class="main-content">
        <div class="cart-container">
            <div class="cart-header">
                <h1 class="cart-title">Your Cart</h1>
                <span class="select-text">Select</span>
            </div>

            <div class="select-container" style="margin-bottom: 1.5rem; display: flex; align-items: center;">
                <img src="images/your-cart.png" alt="Select" style="width: 450px; height: 80px; padding:30px 30px; margin-right: 600px;">
                <span class="select-text" style="font-size: 17px;">Select</span>
            </div>


            <div id="cart-items">
                <?php foreach ($consolidated_cart as $key => $item): 
                    $itemTotal = $item['price'] * $item['quantity'];
                ?>
                <div class="cart-item" onclick="toggleSelection(this)" data-id="<?php echo $item['id']; ?>">
                    <div class="item-details">
                        <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="item-image">
                        <div class="item-info">
                            <h3><?php echo $item['name']; ?></h3>
                            <p class="item-price">Rp<?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                    <div class="quantity-controls">
                        <form method="POST" style="display: flex; align-items: center; gap: 1rem;" 
                            onsubmit="event.stopPropagation();">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="item_name" value="<?php echo $item['name']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                            <button type="submit" name="update_quantity" 
                                    onclick="event.stopPropagation(); this.form.quantity.value = parseInt(this.form.quantity.value) - 1;" 
                                    class="quantity-btn">-</button>
                            <span class="quantity"><?php echo $item['quantity']; ?></span>
                            <button type="submit" name="update_quantity" 
                                    onclick="event.stopPropagation(); this.form.quantity.value = parseInt(this.form.quantity.value) + 1;" 
                                    class="quantity-btn">+</button>
                        </form>
                    </div>
                    <div class="item-total">
                        Total Rp<?php echo number_format($itemTotal, 0, ',', '.'); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Checkout Button -->
    <!-- <div class="checkout-container">
        <button class="checkout-btn" onclick="proceedToCheckout()">Checkout</button>
    </div> -->

    <!-- Checkout Button -->
    <div class="checkout-container">
        <button class="checkout-btn" onclick="proceedToCheckout()">Checkout</button>
    </div>

    <script>
        function toggleSelection(element) {
            // Remove selection from all items
            const items = document.querySelectorAll('.cart-item');
            items.forEach(item => item.classList.remove('selected'));

            // Add selection to the clicked item
            element.classList.add('selected');
        }

        function proceedToCheckout() {
            window.location.href = 'checkout.php'; // Redirect to checkout page
        }
    </script>



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
    function toggleSelection(element) {
        // Remove selection from all items
        const items = document.querySelectorAll('.cart-item');
        items.forEach(item => item.classList.remove('selected'));

        // Add selection to the clicked item
        element.classList.add('selected');
    }
</script>

</body>
</html>
