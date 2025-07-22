<?php
include '../includes/auth.php';
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, category, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $category, $image]);
    header('Location: dashboard.php');
    exit;
}
?>
<h2>Tambah Produk</h2>
<form method="POST" enctype="multipart/form-data">
    Nama: <input type="text" name="name"><br>
    Harga: <input type="text" name="price"><br>
    Deskripsi: <textarea name="description"></textarea><br>
    Kategori: <input type="text" name="category"><br>
    Gambar: <input type="file" name="image"><br>
    <button type="submit">Tambah</button>
</form>
