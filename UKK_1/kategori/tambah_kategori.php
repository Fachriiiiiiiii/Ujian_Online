<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

if(isset($_POST['simpan'])) {
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    
    // Cek apakah kategori sudah ada
    $check = mysqli_query($koneksi, "SELECT * FROM kategori WHERE nama_kategori='$nama_kategori'");
    
    if(mysqli_num_rows($check) > 0) {
        echo "<script>alert('Kategori sudah ada!');</script>";
    } else {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
        
        if(mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Kategori berhasil ditambahkan!');
                    window.location.href='data_kategori.php';
                  </script>";
        } else {
            echo "<script>alert('Gagal menambahkan kategori!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Kategori Baru</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Kategori: <span style="color:red;">*</span></label>
                <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori" required autofocus>
            </div>
            
            <div class="btn-group">
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan</button>
                <a href="data_kategori.php" class="btn btn-secondary">↩ Batal</a>
            </div>
        </form>
    </div>
</body>
</html>