<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoS</title>
    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
<section class="navbar">
    <div class="navbar clearfix">
        <div class="logo">
            <!-- Tambahkan logo di sini -->
            <img src="images/StoS-logo.png" alt="Logo" class="img-responsive">
        </div>
        <div class="menu">
            <ul>
                <p><a href="index.html">Home</a></p> <!-- Added Home link -->
                <p><a href="#">Offers And Promo</a></p>
                <p><a href="#">Orders</a></p>
                <p><a href="#">Privacy Policy</a></p>
                <p><a href="#">Donation</a></p>
                <li class="cart-item"><a href="#" class="cart-icon"><img src="images/icon-basket.png" alt="Cart">Cart</a></li>
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
                <!-- Ikon kiri di dalam input pencarian -->
                <span class="icon-search-left">
                    <img src="images/icon-search-left.png" alt="Search Icon" class="icon-image">
                </span>
                <input type="search" name="search" placeholder="Search food" required>
                <!-- Tombol submit menggunakan gambar -->
                <button type="submit" name="submit" class="search-button">
                    <img src="images/icon-search-right.png" alt="Search Icon" class="icon-image">
                </button>
            </form>
        </div>
    </section>
    <!-- Food Search Section Ends Here -->

    <!-- Food Menu Section Starts -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Recommended For You!</h2>
            
            <div class="food-card-container">
                <div class="food-card">
                    <div class="card-bg"></div>
                    <img src="images/veggie-tomato-mix.jpg" alt="Tomato Mix" class="food-image"> 
                    <div class="food-name">Veggie Tomato Mix</div>
                    <div class="food-price">Rp.6.000</div>
                    <div class="add-to-cart-container">
                        <button class="add-to-cart-button">Add to Cart</button>
                    </div>
                </div>
        
                <div class="food-card">
                    <div class="card-bg"></div>
                    <img src="images/chicken-soup.jpg" alt="Tomato Mix" class="food-image"> 
                    <div class="food-name">Chicken Soup</div>
                    <div class="food-price">Rp.8.000</div>
                    <div class="add-to-cart-container">
                        <button class="add-to-cart-button">Add to Cart</button>
                    </div>
                </div>
        
                <div class="food-card">
                    <div class="card-bg"></div>
                    <img src="images/sate-taichan.png" alt="Tomato Mix" class="food-image"> 
                    <div class="food-name">Sate Taichan</div>
                    <div class="food-price">Rp.7.000</div>
                    <div class="add-to-cart-container">
                        <button class="add-to-cart-button">Add to Cart</button>
                    </div>
                </div>
        
                <div class="food-card">
                    <div class="card-bg"></div>
                    <img src="images/lodeh-tuna.jpg" alt="Tomato Mix" class="food-image"> 
                    <div class="food-name">Lodeh Tuna</div>
                    <div class="food-price">Rp.5.000</div>
                    <div class="add-to-cart-container">
                        <button class="add-to-cart-button">Add to Cart</button>
                    </div>
                </div>
            </div>
    
            <div class="clearfix"></div>
            
            <p class="text-center">
                <a href="#">See All Foods</a>
            </p>
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