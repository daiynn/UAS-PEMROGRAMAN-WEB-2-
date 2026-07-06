<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($conn, "
SELECT
posts.*,
users.username,
categories.category_name
FROM posts
JOIN users ON posts.user_id = users.id
JOIN categories ON posts.category_id = categories.id
WHERE posts.id = '$id'
");

$post = mysqli_fetch_assoc($query);

if (!$post) {
    echo "Postingan tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title><?php echo $post['title']; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<div class="card p-4 text-white">

<h2>

<?php echo $post['title']; ?>

</h2>

<hr>

<p>

<strong>Kategori :</strong>

<?php echo $post['category_name']; ?>

</p>

<p>

<strong>Penulis :</strong>

<?php echo $post['username']; ?>

</p>

<?php
if ($post['image'] != "") {
?>

<img
src="../uploads/<?php echo $post['image']; ?>"
class="img-fluid rounded mb-4">

<?php
}
?>

<p>

<?php echo nl2br($post['content']); ?>

</p>

<hr>
<h4 class="mt-4">💬 Komentar</h4>

<?php

$komentar = mysqli_query($conn,"
SELECT
comments.*,
users.username
FROM comments
JOIN users ON comments.user_id = users.id
WHERE comments.post_id = '$id'
ORDER BY comments.id DESC
");

if(mysqli_num_rows($komentar)==0){

echo "<div class='alert alert-secondary'>Belum ada komentar.</div>";

}

while($komen=mysqli_fetch_assoc($komentar)){

?>

<div class="card mb-3 text-white">

<div class="card-body">

<h6>

<?php echo $komen['username']; ?>

</h6>

<p>

<?php echo nl2br($komen['comment']); ?>

</p>

<?php
if($_SESSION['id']==$komen['user_id'] || $_SESSION['role']=="admin"){
?>

<a
href="../comments/edit.php?id=<?php echo $komen['id']; ?>"
class="btn btn-sm btn-warning">

Edit

</a>

<a
href="../comments/delete.php?id=<?php echo $komen['id']; ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Hapus komentar ini?')">

Hapus

</a>

<?php
}
?>

</div>

</div>

<?php

}

?>
<hr>

<h5>Tambah Komentar</h5>

<form action="../comments/create.php" method="POST">

<input
type="hidden"
name="post_id"
value="<?php echo $post['id']; ?>">

<textarea
name="comment"
class="form-control mb-3"
rows="4"
placeholder="Tulis komentar..."
required></textarea>

<button
class="btn btn-warning">

Kirim Komentar

</button>

</form>
<?php
// Tombol Edit dan Hapus hanya untuk pemilik posting atau admin
echo "<pre>";

echo "SESSION ID : " . $_SESSION['id'] . "<br>";

echo "POST USER ID : " . $post['user_id'] . "<br>";

echo "ROLE : " . $_SESSION['role'];

echo "</pre>";
// like 

if ($_SESSION['id'] == $post['user_id'] || $_SESSION['role'] == "admin") {
?>

<?php

$totalLike = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM likes
WHERE post_id='$id'
"));

$cekLike = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM likes
WHERE post_id='$id'
AND user_id='".$_SESSION['id']."'
"));

?>

<hr>

<a
href="../likes/toggle.php?id=<?php echo $post['id']; ?>"
class="btn btn-outline-warning">

<?php

if($cekLike){

echo "❤️ Batal Like";

}else{

echo "🤍 Like";

}

?>

(<?php echo $totalLike['total']; ?>)

</a>


<a
href="edit.php?id=<?php echo $post['id']; ?>"
class="btn btn-warning">

✏️ Edit

</a>

<a
href="delete.php?id=<?php echo $post['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Yakin ingin menghapus postingan ini?')">

🗑️ Hapus

</a>

<?php
}
?>

<a
href="../index.php"
class="btn btn-secondary">

Kembali

</a>

</div>

</div>

</body>

</html>