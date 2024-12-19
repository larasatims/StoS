<?php
// Memulai session
session_start();
require 'config.php'; // Pastikan file ini berisi koneksi ke database

// Cek apakah form registrasi sudah dikirim
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Hash password menggunakan password_hash untuk keamanan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data pengguna ke dalam tabel users
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Persiapkan statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter untuk mencegah SQL Injection
        $stmt->bind_param("sss", $username, $hashedPassword, $email);

        // Eksekusi query
        if ($stmt->execute()) {
            // Set session dan tampilkan pop-up sukses
            echo "<script>alert('Akun berhasil dibuat!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat membuat akun!');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Terjadi kesalahan dengan query database!');</script>";
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - StoS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #FFF5F5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo img {
            max-width: 150px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .login-button {
            background: #FFA41B;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 25px;
            width: 100%;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-button:hover {
            background: #FF5B22;
        }

        .register-link {
            text-align: center;
            margin-top: 1rem;
        }

        .register-link a {
            color: #FFA41B;
            text-decoration: none;
        }

        .error-message {
            background: #ffe6e6;
            color: #ff0000;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="images/StoS-logo.png" alt="StoS Logo">
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="nama" id="nama" name="nama" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button type="submit" name="register" class="login-button">Register</button>
        </form>
        
        <div class="register-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
