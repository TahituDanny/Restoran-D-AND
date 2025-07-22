<?php
include 'includes/config.php';
session_start();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;
$offset = ($page - 1) * $perPage;

// Hitung total produk
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE ?");
$count_stmt->execute(["%$search%"]);
$total = $count_stmt->fetchColumn();
$pages = ceil($total / $perPage);

// Ambil produk sesuai halaman
$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? LIMIT $perPage OFFSET $offset");
$stmt->execute(["%$search%"]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Restoran</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4">Daftar Menu Restoran</h1>
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Cari</button>
        </div>
    </form>
    <div class="row">
        <?php while($row = $stmt->fetch()): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>" style="height:200px;object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['name'] ?></h5>
                    <p class="card-text"><?= $row['description'] ?></p>
                    <p><strong>Rp <?= number_format($row['price']) ?></strong></p>
                    <form method="POST" action="keranjang.php">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                        <button type="submit" class="btn btn-success w-100">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
    <?php else: ?>
        <a href="login.php" class="btn btn-primary mt-4">Login</a>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
