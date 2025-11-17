<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori='$id'"));

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_kategori.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    
    // Cek apakah kategori sudah ada (kecuali kategori yang sedang diedit)
    $check = mysqli_query($koneksi, "SELECT * FROM kategori WHERE nama_kategori='$nama_kategori' AND id_kategori != '$id'");
    
    if(mysqli_num_rows($check) > 0) {
        echo "<script>alert('Kategori sudah ada!');</script>";
    } else {
        $query = "UPDATE kategori SET nama_kategori='$nama_kategori' WHERE id_kategori='$id'";
        
        if(mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Kategori berhasil diupdate!');
                    window.location.href='data_kategori.php';
                  </script>";
        } else {
            echo "<script>alert('Gagal mengupdate kategori!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Kategori</h2>
        
        <div class="info-box">
            📝 Mengedit kategori: <strong><?= htmlspecialchars($data['nama_kategori']) ?></strong>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Kategori: <span style="color:red;">*</span></label>
                <input type="text" name="nama_kategori" value="<?= htmlspecialchars($data['nama_kategori']) ?>" placeholder="Masukkan nama kategori" required autofocus>
            </div>
            
            <div class="btn-group">
                <button type="submit" name="update" class="btn btn-primary">💾 Update</button>
                <a href="data_kategori.php" class="btn btn-secondary">↩ Batal</a>
            </div>
        </form>
    </div>
</body>
</html>