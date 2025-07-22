<?php
include '../includes/auth.php';
include '../includes/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);
header('Location: dashboard.php');
exit;
?>
