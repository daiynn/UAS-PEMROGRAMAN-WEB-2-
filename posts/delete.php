<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data posting
$query = mysqli_query($conn, "SELECT * FROM posts WHERE id='$id'");
$post = mysqli_fetch_assoc($query);

if (!$post) {
    die("Postingan tidak ditemukan.");
}

// Cek hak akses
if ($_SESSION['id'] != $post['user_id'] && $_SESSION['role'] != "admin") {
    die("Anda tidak memiliki hak menghapus postingan ini.");
}

// Hapus gambar jika ada
if (!empty($post['image'])) {
    $file = "../uploads/" . $post['image'];

    if (file_exists($file)) {
        unlink($file);
    }
}

// Hapus posting dari database
mysqli_query($conn, "DELETE FROM posts WHERE id='$id'");

header("Location: ../index.php");
exit;
?>