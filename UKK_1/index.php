<?php
include 'config/koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$user' AND password='$pass'");
    $data = mysqli_fetch_array($query);

    if ($data) {
        $_SESSION['user'] = $data['username'];
        header("Location: home.php");
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-box">
        <h2>Login Inventaris</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
