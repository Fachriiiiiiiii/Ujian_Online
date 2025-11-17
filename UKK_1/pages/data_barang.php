<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil daftar kategori dari tabel kategori
$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Pencarian dan Filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : '';

// Build query dengan kondisi
$query = "SELECT * FROM barang WHERE 1=1";

if ($search != '') {
    $query .= " AND (nama_barang LIKE '%$search%' OR kategori LIKE '%$search%' OR id_barang LIKE '%$search%')";
}

if ($filter_kategori != '') {
    $query .= " AND kategori = '$filter_kategori'";
}

$query .= " ORDER BY id_barang ASC";
$result = mysqli_query($koneksi, $query);

// Cek jika query gagal
if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

// Hitung total barang per kategori
$count_query = "SELECT kategori, COUNT(*) as jumlah FROM barang GROUP BY kategori";
$count_result = mysqli_query($koneksi, $count_query);
$kategori_count = [];
if ($count_result) {
    while($count_row = mysqli_fetch_assoc($count_result)) {
        $kategori_count[$count_row['kategori']] = $count_row['jumlah'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Data Barang</h2>
        
        <div class="header-links">
            <a href="tambah_barang.php">+ Tambah Barang</a> | 
            <a href="../home.php">🏠 Kembali</a>
        </div>

        <!-- Form Pencarian dan Filter -->
        <form method="GET" action="">
            <div class="filter-section">
                <?php if($search != '' || $filter_kategori != ''): ?>
                <div class="filter-info">
                    📊 <strong>Filter Aktif:</strong> 
                    <?php if($search != ''): ?>
                        <span style="background: white; padding: 4px 10px; border-radius: 4px; margin-left: 5px;">
                            Pencarian: <strong><?= htmlspecialchars($search) ?></strong>
                        </span>
                    <?php endif; ?>
                    <?php if($filter_kategori != ''): ?>
                        <span style="background: white; padding: 4px 10px; border-radius: 4px; margin-left: 5px;">
                            Kategori: <strong><?= htmlspecialchars($filter_kategori) ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="search-box">
                    <input type="text" name="search" placeholder="Cari barang (nama, kategori, atau ID)..." value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="filter-box">
                    <select name="kategori">
                        <option value="">Semua Kategori</option>
                        <?php 
                        if ($kategori_list && mysqli_num_rows($kategori_list) > 0) {
                            mysqli_data_seek($kategori_list, 0); // Reset pointer
                            while($kat = mysqli_fetch_assoc($kategori_list)) { 
                                $count = isset($kategori_count[$kat['nama_kategori']]) ? $kategori_count[$kat['nama_kategori']] : 0;
                        ?>
                        <option value="<?= htmlspecialchars($kat['nama_kategori']) ?>" 
                            <?= ($filter_kategori == $kat['nama_kategori']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?> (<?= $count ?> barang)
                        </option>
                        <?php 
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="button-group">
                    <button type="submit">🔍 Cari & Filter</button>
                    <?php if($search != '' || $filter_kategori != ''): ?>
                        <a href="data_barang.php" class="reset-btn">🔄 Reset Filter</a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">No</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th style="width: 120px;">Jumlah</th>
                    <th style="width: 150px;">Harga</th>
                    <th style="width: 150px;">Tanggal Masuk</th>
                    <th style="width: 180px;">Aksi</th>
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
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td>
                        <span style="background: #e3f2fd; padding: 5px 12px; border-radius: 4px; font-weight: 500;">
                            <?= htmlspecialchars($row['kategori']) ?>
                        </span>
                    </td>
                    <td><?= $row['jumlah'] ?> <?= isset($row['satuan']) ? htmlspecialchars($row['satuan']) : '' ?></td>
                    <td>Rp <?= number_format(isset($row['harga']) ? $row['harga'] : 0, 0, ',', '.') ?></td>
                    <td>
                        <?php 
                        if(isset($row['tanggal_masuk']) && !empty($row['tanggal_masuk']) && $row['tanggal_masuk'] != '0000-00-00') {
                            echo date('d/m/Y', strtotime($row['tanggal_masuk']));
                        } else {
                            echo '<span style="color: red;">-</span>';
                        }
                        ?>
                    </td>
                    <td class="action-links">
                        <a href="edit_barang.php?id=<?= $row['id_barang'] ?>">Edit</a> |
                        <a href="hapus_barang.php?id=<?= $row['id_barang'] ?>" onclick="return confirm('Yakin ingin menghapus barang <?= htmlspecialchars($row['nama_barang']) ?>?')">Hapus</a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="7" class="empty-message">
                        <?php 
                        if($search != '' || $filter_kategori != '') {
                            echo "❌ Tidak ada hasil untuk filter yang dipilih";
                        } else {
                            echo "📦 Belum ada data barang";
                        }
                        ?>
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