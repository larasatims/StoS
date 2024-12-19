<?php
session_start(); 

// Initialize item count for cart
$item_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoS - Search Results</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Styling for cart item count */
        .cart-icon {
            position: relative;
            display: inline-block;
            color: white; /* Warna teks */
        }

        .item-count {
            position: absolute;
            top: -10px; /* Adjust sesuai tampilan */
            right: -10px; /* Adjust sesuai tampilan */
            background-color: red; /* Warna latar badge */
            color: white; /* Warna teks badge */
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="navbar clearfix">
            <div class="logo">
                <img src="images/StoS-logo.png" alt="Logo" class="img-responsive">
            </div>
            <div class="menu">
                <ul>
                    <p><a href="index.html">Home</a></p>
                    <p><a href="#">Offers And Promo</a></p>
                    <p><a href="#">Orders</a></p>
                    <p><a href="#">Privacy Policy</a></p>
                    <p><a href="#">Donation</a></p>
                    <li class="cart-item">
                        <a href="cart.php" class="cart-icon">
                            <img src="images/icon-basket.png" alt="Cart">
                            Cart
                            <?php
                            // Display item count if there are items in the cart
                            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                                echo '<span class="item-count">' . count($_SESSION['cart']) . '</span>';
                            }
                            ?>
                        </a>
                    </li>
                    <li class="profile-item"><a href="#" class="profile-icon"><img src="images/icon-profile.png" alt="Profile"></a></li>
                </ul>
            </div>
        </div>        
    </section>
    <!-- Navbar Section Ends Here -->

    <!-- Food Search Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            <form action="food-search.php" method="POST" class="search-container">
                <span class="icon-search-left">
                    <img src="images/icon-search-left.png" alt="Search Icon" class="icon-image">
                </span>
                <input type="search" name="search" placeholder="Search food" required>
                <button type="submit" name="submit" class="search-button">
                    <img src="images/icon-search-right.png" alt="Search Icon" class="icon-image">
                </button>
            </form>
        </div>
    </section>
    <!-- Food Search Section Ends Here -->

    <!-- PHP Code to Fetch and Display Search Results -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Search Results</h2>

            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', 'rahasia', 'stos');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle add to cart functionality
            if (isset($_POST['add_to_cart'])) {
                $item = [
                    'id' => $_POST['food_id'],
                    'name' => $_POST['food_name'],
                    'price' => $_POST['food_price'],
                    'image' => $_POST['food_image']
                ];

                // Initialize the cart session if it does not exist
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Add the item to the cart
                $_SESSION['cart'][] = $item;

                // Update item count after adding
                $item_count = count($_SESSION['cart']);

                // Display success message
                echo "<p class='text-center success-message'>Item successfully added to cart!</p>";
                
            }

            // Search functionality
            if (isset($_POST['search'])) {
                $search = $conn->real_escape_string($_POST['search']);

                // Query to search food items based on keyword
                $sql = "SELECT * FROM food_items WHERE name LIKE '%$search%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Display search results
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="food-card">
                            <div class="card-bg"></div>
                            <img src="images/'.$row['image'].'" alt="'.$row['name'].'" class="food-image"> 
                            <div class="food-name">'.$row['name'].'</div>
                            <div class="food-price">Rp.'.$row['price'].'</div>
                            <form action="food-search.php" method="POST" class="add-to-cart-form">
                                <input type="hidden" name="food_id" value="'.$row['id'].'">
                                <input type="hidden" name="food_name" value="'.$row['name'].'">
                                <input type="hidden" name="food_price" value="'.$row['price'].'">
                                <input type="hidden" name="food_image" value="'.$row['image'].'">
                                <div class="add-to-cart-container">
                                    <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                                </div>
                            </form>
                        </div>';
                    }
                } else {
                    // If no results found
                    echo '<p class="text-center">Sorry, no items found.</p>';
                }
            }

            $conn->close();
            ?>
        </div>
    </section>
    <!-- Food Menu Section Ends Here -->

    <!-- Slogan Section -->
    <div class="slogan-section">
        <div class="slogan-overlay"></div>
        <div class="slogan-content">
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
