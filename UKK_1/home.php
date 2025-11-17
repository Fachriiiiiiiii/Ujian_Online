<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="card">
        <h2>👋 Selamat Datang, <b><?= $_SESSION['user']; ?></b>!</h2>
        <p>Silakan pilih menu di bawah ini:</p>
        <div class="menu">
            <a href="pages/data_barang.php">📦 Data Barang</a>
            <a href="kategori/data_kategori.php">🗂️ Data Kategori</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>
</body>
</html>
