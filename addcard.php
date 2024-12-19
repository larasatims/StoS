<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "rahasia";
$dbname = "stos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$search_keyword = isset($_POST['search']) ? $_POST['search'] : '';
$message = '';

function addToCart($itemId, $qty) {
    global $message, $conn;

    $sql = "SELECT * FROM produk WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        if (isset($_SESSION['cart'][$item['id']])) {
            $_SESSION['cart'][$item['id']]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$item['id']] = [
                'nama' => $item['nama'],
                'harga' => $item['harga'],
                'qty' => $qty
            ];
        }
        $message = "Item '{$item['nama']}' berhasil ditambahkan ke keranjang!";
    } else {
        $message = "Item tidak ditemukan.";
    }
}

if (isset($_POST['add_to_cart']) && !empty($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    $qty = 1;
    addToCart($itemId, $qty);
}

$sql = "SELECT * FROM produk WHERE nama LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search_keyword%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Makanan</title>
    <link rel="stylesheet" href="addcard.css">
</head>
<body>
    <div class="menu-header">
        <h1>Surplus to Solution</h1>
    </div>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="search-bar">
        <form method="post" action="">
            <input type="text" name="search" placeholder="Cari makanan..." value="<?php echo htmlspecialchars($search_keyword); ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="menu-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="menu-item">
                    <h3><?php echo htmlspecialchars($item['nama']); ?></h3>
                    <p>Harga: Rp<?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                    <form method="post" action="">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                        <button type="submit" name="add_to_cart">Tambah ke Keranjang</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada hasil untuk pencarian "<strong><?php echo htmlspecialchars($search_keyword); ?></strong>".</p>
        <?php endif; ?>
    </div>

    <div class="cart-link">
        
        <form action="Card.php" method="get">
            <button type="submit" class="go-to-cart">Ke Keranjang</button>
        </form>
    </div>
</body>
</html>
