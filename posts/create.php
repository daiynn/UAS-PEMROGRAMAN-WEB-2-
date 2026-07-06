<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

if (isset($_POST['simpan'])) {

    $user_id = $_SESSION['id'];
    $category_id = $_POST['category'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $image = "";

    if ($_FILES['image']['name'] != "") {

        $image = time() . "_" . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/" . $image
        );
    }

    mysqli_query($conn, "
        INSERT INTO posts(user_id, category_id, title, content, image)
        VALUES('$user_id','$category_id','$title','$content','$image')
    ");

    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Buat Post</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<div class="card p-4 text-white">

<h3>Buat Postingan</h3>

<form method="POST" enctype="multipart/form-data">

<label>Judul</label>

<input
type="text"
name="title"
class="form-control mb-3"
required>

<label>Kategori</label>

<select
name="category"
class="form-control mb-3">

<?php

$query=mysqli_query($conn,"SELECT * FROM categories");

while($data=mysqli_fetch_assoc($query))
{

?>

<option value="<?php echo $data['id']; ?>">

<?php echo $data['category_name']; ?>

</option>

<?php

}

?>

</select>

<label>Gambar</label>

<input
type="file"
name="image"
class="form-control mb-3">

<label>Isi Postingan</label>

<textarea
name="content"
rows="6"
class="form-control mb-3"
required></textarea>

<button
class="btn btn-warning"
name="simpan">

Posting

</button>

<a
href="../index.php"
class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

</body>

</html>