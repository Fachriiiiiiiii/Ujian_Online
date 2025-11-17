<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    $data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama_kategori FROM kategori WHERE id_kategori='$id'"));
    
    if($data) {
        $query = "DELETE FROM kategori WHERE id_kategori='$id'";
        
        if(mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Kategori " . htmlspecialchars($data['nama_kategori']) . " berhasil dihapus!');
                    window.location.href='data_kategori.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus kategori!');
                    window.location.href='data_kategori.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href='data_kategori.php';
              </script>";
    }
} else {
    header("Location: data_kategori.php");
}
?>