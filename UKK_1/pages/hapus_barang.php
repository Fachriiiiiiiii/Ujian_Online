<?php
include '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Ambil nama barang untuk konfirmasi
    $data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama_barang FROM barang WHERE id_barang='$id'"));
    
    if($data) {
        $query = "DELETE FROM barang WHERE id_barang='$id'";
        
        if(mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Data " . htmlspecialchars($data['nama_barang']) . " berhasil dihapus!');
                    window.location.href='data_barang.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data!');
                    window.location.href='data_barang.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href='data_barang.php';
              </script>";
    }
} else {
    header("Location: data_barang.php");
}
?>