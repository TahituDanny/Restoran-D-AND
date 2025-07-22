<?php
include '../includes/auth.php';
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total = $_POST['total'];
    $stmt = $pdo->prepare("INSERT INTO transaksi (total) VALUES (?)");
    $stmt->execute([$total]);
    $transaksi_id = $pdo->lastInsertId();

    foreach ($_SESSION['cart'] as $id => $item) {
        $stmt = $pdo->prepare("INSERT INTO transaksi_detail (transaksi_id, id_produk, jumlah) VALUES (?, ?, ?)");
        $stmt->execute([$transaksi_id, $id, $item['jumlah']]);
    }

    $_SESSION['cart'] = [];
    header("Location: histori_transaksi.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Checkout</h2>
    <form method="POST">
        <p>Total: Rp <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item) {
            $stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
            $stmt->execute([$id]);
            $produk = $stmt->fetch();
            $subtotal = $produk['harga'] * $item['jumlah'];
            $total += $subtotal;
        }
        echo number_format($total);
        ?></p>
        <input type="hidden" name="total" value="<?= $total ?>">
        <button type="submit" class="btn btn-success">Proses Transaksi</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
