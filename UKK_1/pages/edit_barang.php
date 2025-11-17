<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil daftar kategori dari database
$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id'"));

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_barang.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $jumlah = (int)$_POST['jumlah'];
    $harga = (float)$_POST['harga'];
    $tanggal_masuk = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);

    $query = "UPDATE barang SET 
              nama_barang='$nama', 
              kategori='$kategori', 
              jumlah=$jumlah, 
              harga=$harga, 
              tanggal_masuk='$tanggal_masuk' 
              WHERE id_barang='$id'";
    
    if(mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data berhasil diupdate!');
                window.location.href='data_barang.php';
              </script>";
    } else {
        $error = mysqli_error($koneksi);
        echo "<script>alert('Gagal update data: $error');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Barang</h2>
        
        <div class="info-box">
            📝 Mengedit barang: <strong><?= htmlspecialchars($data['nama_barang']) ?></strong>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Barang: <span style="color:red;">*</span></label>
                <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']) ?>" placeholder="Masukkan nama barang" required>
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
                <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" min="0" required>
            </div>
            
            <div class="form-group">
                <label>Harga (Rp): <span style="color:red;">*</span></label>
                <input type="number" name="harga" value="<?= $data['harga'] ?>" min="0" step="0.01" placeholder="Masukkan harga" required>
            </div>
            
            <div class="form-group">
                <label>Tanggal Masuk: <span style="color:red;">*</span></label>
                <input type="date" name="tanggal_masuk" value="<?= $data['tanggal_masuk'] ?>" required>
            </div>
            
            <div class="btn-group">
                <button type="submit" name="update" class="btn btn-primary">💾 Update</button>
                <a href="data_barang.php" class="btn btn-secondary">↩ Batal</a>
            </div>
        </form>
    </div>
</body>
</html>