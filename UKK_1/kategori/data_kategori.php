<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// Pencarian
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
if ($search != '') {
    $result = mysqli_query($koneksi, "SELECT * FROM kategori WHERE 
        nama_kategori LIKE '%$search%' OR 
        id_kategori LIKE '%$search%'
        ORDER BY id_kategori ASC");
} else {
    $result = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori ASC");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Data Kategori</h2>
        
        <div class="header-links">
            <a href="tambah_kategori.php">+ Tambah Kategori</a> | 
            <a href="../home.php">🏠 Kembali</a>
        </div>

        <!-- Form Pencarian -->
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Cari kategori (nama atau ID)..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">🔍 Cari</button>
                <?php if($search != ''): ?>
                    <a href="data_kategori.php" class="reset-btn">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">No</th>
                    <th>Nama Kategori</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if($result && mysqli_num_rows($result) > 0) {
                    $no = 1; // Nomor urut dimulai dari 1
                    while($row = mysqli_fetch_assoc($result)) { 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    <td class="action-links">
                        <a href="edit_kategori.php?id=<?= $row['id_kategori'] ?>">Edit</a> |
                        <a href="hapus_kategori.php?id=<?= $row['id_kategori'] ?>" onclick="return confirm('Yakin ingin menghapus kategori <?= htmlspecialchars($row['nama_kategori']) ?>?\n\nPeringatan: Barang dengan kategori ini mungkin terpengaruh!')">Hapus</a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="3" class="empty-message">
                        <?= $search != '' ? "Tidak ada hasil untuk '<b>$search</b>'" : "Tidak ada data kategori" ?>
                    </td>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>