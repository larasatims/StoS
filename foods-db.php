error_reporting(E_ALL);
ini_set('display_errors', 1);

<?php
// Konfigurasi database
$host = "localhost"; 
$user = "root";      
$pass = "rahasia";         
$db_name = "stos"; 

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db_name);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil parameter pencarian dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mencari makanan sesuai kata kunci
$sql = "SELECT * FROM foods WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();

// Ambil hasil dalam format JSON
$foods = [];
while ($row = $result->fetch_assoc()) {
    $foods[] = [
        'name' => $row['name'],
        'price' => $row['price'],
        'image' => $row['image']
    ];
}

// Output dalam format JSON
header('Content-Type: application/json');
echo json_encode($foods);

// Tutup koneksi
$stmt->close();
$conn->close();
?>
