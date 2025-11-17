<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_inventaris");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?> 
