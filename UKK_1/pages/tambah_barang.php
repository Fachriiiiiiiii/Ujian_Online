<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil daftar kategori dari database
$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Proses tambah barang
if(isset($_POST['simpan'])) {
    $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $jumlah = (int)$_POST['jumlah'];
    $harga = (float)$_POST['harga'];
    $tanggal_masuk = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);
    
    $query = "INSERT INTO barang (nama_barang, kategori, jumlah, harga, tanggal_masuk) 
              VALUES ('$nama_barang', '$kategori', $jumlah, $harga, '$tanggal_masuk')";
    
    if(mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data barang berhasil ditambahkan!');
                window.location.href='data_barang.php';
              </script>";
    } else {
        $error = mysqli_error($koneksi);
        echo "<script>
                alert('Gagal menambahkan data: $error');
              </script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Barang Baru</h2>
        
        <?php if(mysqli_num_rows($kategori_list) == 0): ?>
        <div class="kategori-info">
            ⚠️ Belum ada kategori tersedia. <a href="data_kategori.php">Tambah kategori terlebih dahulu</a>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Barang: <span style="color:red;">*</span></label>
                <input type="text" name="nama_barang" placeholder="Masukkan nama barang" required autofocus>
            </div>
            
            <div class="form-group">
    <label style="display: block;">Kategori: <span style="color:red;">*</span></label>
    <select name="kategori" required>
        <option value="">Pilih Kategori</option>
        <?php 
        mysqli_data_seek($kategori_list, 0);
        while($kat = mysqli_fetch_assoc($kategori_list)) { 
        ?>
        <option value="<?= htmlspecialchars($kat['nama_kategori']) ?>">
            <?= htmlspecialchars($kat['nama_kategori']) ?>
        </option>
        <?php } ?>
    </select>
</div>
            <div class="form-group">
                <label>Jumlah: <span style="color:red;">*</span></label>
                <input type="number" name="jumlah" min="0" value="0" required>
            </div>
            
            <div class="form-group">
                <label>Harga (Rp): <span style="color:red;">*</span></label>
                <input type="number" name="harga" min="0" step="0.01" placeholder="Masukkan harga" required>
            </div>
            
            <div class="form-group">
                <label>Tanggal Masuk: <span style="color:red;">*</span></label>
                <input type="date" name="tanggal_masuk" value="<?= date('Y-m-d') ?>" required>
            </div>
            
            <div class="btn-group">
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan</button>
                <a href="data_barang.php" class="btn btn-secondary">↩ Batal</a>
            </div>
        </form>
    </div>
</body>
</html>