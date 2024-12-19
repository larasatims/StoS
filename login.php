<?php
session_start();

// Sambungkan ke database
$conn = new mysqli("localhost", "root", "rahasia", "stos");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi pesan error
$error_message = "";

if (isset($_POST['login'])) {
    // Tangkap data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa username di tabel users
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Login berhasil, buat session
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;

            // Redirect ke halaman utama (index.php)
            header('Location: index.php');
            exit();
        } else {
            // Password salah
            $error_message = "Password salah!";
        }
    } else {
        // Username tidak ditemukan
        $error_message = "Username tidak ditemukan!";
    }
    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StoS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #FFF5F5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
        .logo { text-align: center; margin-bottom: 2rem; }
        .logo img { max-width: 150px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: #333; }
        .form-group input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; }
        .login-button { background: #FFA41B; color: white; padding: 1rem; border: none; border-radius: 25px; width: 100%; font-size: 1.1rem; cursor: pointer; transition: background 0.3s ease; }
        .login-button:hover { background: #FF5B22; }
        .register-link { text-align: center; margin-top: 1rem; }
        .register-link a { color: #FFA41B; text-decoration: none; }
        .error-message { background: #ffe6e6; color: #ff0000; padding: 0.5rem; border-radius: 5px; margin-bottom: 1rem; text-align: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="images/StoS-logo.png" alt="StoS Logo">
        </div>
        
        <?php if ($error_message): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" name="login" class="login-button">Login</button>
        </form>
        
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
