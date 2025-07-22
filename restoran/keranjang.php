<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header('Location: keranjang.php');
    exit;
}

$cart = $_SESSION['cart'];
$products = [];
$total = 0;

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $stmt->fetch()) {
        $products[$row['id']] = $row;
        $total += $row['price'] * $cart[$row['id']];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4">Keranjang Belanja</h1>
    <?php if(!empty($cart)): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr>
                <td><?= $product['name'] ?></td>
                <td>Rp <?= number_format($product['price']) ?></td>
                <td><?= $cart[$product['id']] ?></td>
                <td>Rp <?= number_format($product['price'] * $cart[$product['id']]) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Total: Rp <?= number_format($total) ?></h3>
    <a href="checkout.php" class="btn btn-success">Checkout</a>
    <?php else: ?>
    <p>Keranjang Anda kosong.</p>
    <?php endif; ?>
    <a href="index.php" class="btn btn-primary mt-4">Lanjut Belanja</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
