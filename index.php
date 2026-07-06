<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

include "config/koneksi.php";

// ================================
// FILTER KATEGORI
// ================================
$judulForum = "Semua Postingan";

if (isset($_GET['category'])) {

    $category = (int) $_GET['category'];

    // Ambil nama kategori
    $kategori = mysqli_query($conn, "SELECT * FROM categories WHERE id='$category'");
    $kategoriData = mysqli_fetch_assoc($kategori);

    if ($kategoriData) {
        $judulForum = $kategoriData['category_name'];
    }

    $query = mysqli_query($conn, "
        SELECT
            posts.*,
            users.username,
            categories.category_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.category_id = '$category'
        ORDER BY posts.id DESC
    ");

} else {

    $query = mysqli_query($conn, "
        SELECT
            posts.*,
            users.username,
            categories.category_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.id DESC
    ");

}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>THE CATRAP</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-dark shadow">

<div class="container">

<a href="index.php" class="navbar-brand text-warning fw-bold text-decoration-none">

🎮 THE CATRAP

</a>

<div>

<span class="text-white">

Halo,

<b><?php echo $_SESSION['username']; ?></b>

</span>

<a href="auth/logout.php" class="btn btn-warning ms-3">

Logout

</a>

</div>

</div>

</nav>

<!-- CONTENT -->

<div class="container mt-4">

<div class="row">

<!-- SIDEBAR -->

<div class="col-md-3">

<div class="sidebar">

<h5>Kategori</h5>

<hr>

<a href="index.php" class="category-item d-block text-decoration-none text-white">

📌 Semua Postingan

</a>

<?php

$kategori = mysqli_query($conn, "SELECT * FROM categories");

while ($data = mysqli_fetch_assoc($kategori)) {

?>

<a
href="index.php?category=<?php echo $data['id']; ?>"
class="category-item d-block text-decoration-none text-white">

<?php echo $data['category_name']; ?>

</a>

<?php
}
?>

</div>

</div>

<!-- FEED -->

<div class="col-md-9">

<div class="d-flex justify-content-between align-items-center">

<h3>

<?php echo $judulForum; ?>

</h3>

<a href="posts/create.php" class="btn btn-warning">

+ Buat Post

</a>

</div>

<hr>

<?php

if (mysqli_num_rows($query) > 0) {

while ($post = mysqli_fetch_assoc($query)) {

?>

<div class="post-card">

<h3>

<?php echo htmlspecialchars($post['title']); ?>

</h3>

<p>

Kategori :

<b>

<?php echo htmlspecialchars($post['category_name']); ?>

</b>

</p>

<p>

Oleh :

<b>

<?php echo htmlspecialchars($post['username']); ?>

</b>

</p>

<?php

if (!empty($post['image'])) {

?>

<img
src="uploads/<?php echo $post['image']; ?>"
class="img-fluid rounded mb-3">

<?php
}
?>

<p>

<?php

echo nl2br(substr(htmlspecialchars($post['content']), 0, 200));

?>

...

</p>

<a
href="posts/detail.php?id=<?php echo $post['id']; ?>"
class="btn btn-warning">

Lihat Selengkapnya

</a>

</div>

<?php

}

} else {

?>

<div class="alert alert-secondary">

Belum ada postingan pada kategori ini.

</div>

<?php

}

?>

</div>

</div>

</div>

</body>

</html>