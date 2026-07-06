<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

// Ambil data posting
$query = mysqli_query($conn, "
SELECT * FROM posts
WHERE id='$id'
");

$post = mysqli_fetch_assoc($query);

// Jika posting tidak ditemukan
if (!$post) {
    die("Postingan tidak ditemukan.");
}

// Cek hak akses
if ($_SESSION['id'] != $post['user_id'] && $_SESSION['role'] != "admin") {
    die("Anda tidak memiliki hak mengedit postingan ini.");
}

// Proses Update
if (isset($_POST['update'])) {

    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];

    $image = $post['image'];

    // Jika upload gambar baru
    if ($_FILES['image']['name'] != "") {

        // Hapus gambar lama
        if ($image != "" && file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }

        $image = time() . "_" . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/" . $image
        );
    }

    mysqli_query($conn, "
    UPDATE posts SET
    category_id='$category',
    title='$title',
    content='$content',
    image='$image'
    WHERE id='$id'
    ");

    header("Location: detail.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Edit Postingan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<div class="card p-4">

<h2>Edit Postingan</h2>

<form method="POST" enctype="multipart/form-data">

<label>Judul</label>

<input
type="text"
name="title"
class="form-control mb-3"
value="<?php echo $post['title']; ?>"
required>

<label>Kategori</label>

<select
name="category"
class="form-control mb-3">

<?php

$kategori = mysqli_query($conn, "SELECT * FROM categories");

while($k = mysqli_fetch_assoc($kategori))
{

?>

<option
value="<?php echo $k['id']; ?>"

<?php

if($post['category_id']==$k['id']){

echo "selected";

}

?>

>

<?php echo $k['category_name']; ?>

</option>

<?php

}

?>

</select>

<label>Gambar Lama</label><br>

<?php

if($post['image']!=""){

?>

<img
src="../uploads/<?php echo $post['image']; ?>"
class="img-fluid rounded mb-3"
style="max-height:250px;">

<?php

}else{

echo "<p>Tidak ada gambar.</p>";

}

?>

<label>Ganti Gambar (Opsional)</label>

<input
type="file"
name="image"
class="form-control mb-3">

<label>Isi Postingan</label>

<textarea
name="content"
rows="8"
class="form-control mb-3"
required><?php echo $post['content']; ?></textarea>

<button
class="btn btn-warning"
name="update">

Simpan Perubahan

</button>

<a
href="detail.php?id=<?php echo $id; ?>"
class="btn btn-secondary">

Batal

</a>

</form>

</div>

</div>

</body>

</html>