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

// Ambil data komentar
$query = mysqli_query($conn, "SELECT * FROM comments WHERE id='$id'");
$komen = mysqli_fetch_assoc($query);

if (!$komen) {
    die("Komentar tidak ditemukan.");
}

// Cek hak akses
if ($_SESSION['id'] != $komen['user_id'] && $_SESSION['role'] != "admin") {
    die("Anda tidak memiliki hak menghapus komentar ini.");
}

// Simpan id posting sebelum komentar dihapus
$post_id = $komen['post_id'];

// Hapus komentar
mysqli_query($conn, "DELETE FROM comments WHERE id='$id'");

// Kembali ke halaman detail posting
header("Location: ../posts/detail.php?id=" . $post_id);
exit;
?>