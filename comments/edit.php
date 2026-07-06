<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM comments WHERE id='$id'");
$komen = mysqli_fetch_assoc($query);

if(!$komen){
    die("Komentar tidak ditemukan.");
}

if($_SESSION['id'] != $komen['user_id'] && $_SESSION['role'] != "admin"){
    die("Anda tidak memiliki akses.");
}

if(isset($_POST['update'])){

    $comment = $_POST['comment'];

    mysqli_query($conn,"
    UPDATE comments
    SET comment='$comment'
    WHERE id='$id'
    ");

    header("Location: ../posts/detail.php?id=".$komen['post_id']);
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Edit Komentar</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<div class="card p-4 text-white bg-dark">

<h3>Edit Komentar</h3>

<form method="POST">

<textarea
name="comment"
class="form-control mb-3"
rows="5"
required><?php echo htmlspecialchars($komen['comment']); ?></textarea>

<button
name="update"
class="btn btn-warning">

Simpan

</button>

<a
href="../posts/detail.php?id=<?php echo $komen['post_id']; ?>"
class="btn btn-secondary">

Batal

</a>

</form>

</div>

</div>

</body>

</html>