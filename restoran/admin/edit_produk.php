<?php
include '../includes/auth.php';
include '../includes/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $image = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, category = ?, image = ? WHERE id = ?");
    $stmt->execute([$name, $price, $description, $category, $image, $id]);
    header('Location: dashboard.php');
    exit;
}
?>
<h2>Edit Produk</h2>
<form method="POST" enctype="multipart/form-data">
    Nama: <input type="text" name="name" value="<?= $product['name'] ?>"><br>
    Harga: <input type="text" name="price" value="<?= $product['price'] ?>"><br>
    Deskripsi: <textarea name="description"><?= $product['description'] ?></textarea><br>
    Kategori: <input type="text" name="category" value="<?= $product['category'] ?>"><br>
    Gambar: <input type="file" name="image"><br>
    <img src="../<?= $product['image'] ?>" width="100"><br>
    <button type="submit">Update</button>
</form>
