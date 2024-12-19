<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Function to get user details (in a real application, this would fetch from database)
function getUserDetails() {
    return [
        'username' => isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest',
        // Add more user details as needed
    ];
}
?>