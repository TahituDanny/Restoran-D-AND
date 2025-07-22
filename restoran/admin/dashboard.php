<?php
include '../includes/auth.php';
include '../includes/config.php';
$stmt = $pdo->query("SELECT * FROM products");
?>
<h2>Dashboard Admin</h2>
<a href="tambah_produk.php">Tambah Produk</a> | <a href="../logout.php">Logout</a>
<table border="1">
<tr><th>Nama</th><th>Harga</th><th>Aksi</th></tr>
<?php while($p = $stmt->fetch()): ?>
<tr>
<td><?= $p['name'] ?></td>
<td><?= $p['price'] ?></td>
<td>
    <a href="edit_produk.php?id=<?= $p['id'] ?>">Edit</a> |
    <a href="hapus_produk.php?id=<?= $p['id'] ?>">Hapus</a>
</td>
</tr>
<?php endwhile; ?>
</table>
