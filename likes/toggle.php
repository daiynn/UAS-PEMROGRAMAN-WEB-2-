<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

$post_id = (int) $_GET['id'];
$user_id = $_SESSION['id'];

$cek = mysqli_query($conn, "
SELECT * FROM likes
WHERE post_id='$post_id'
AND user_id='$user_id'
");

if (mysqli_num_rows($cek) > 0) {

    mysqli_query($conn,"
    DELETE FROM likes
    WHERE post_id='$post_id'
    AND user_id='$user_id'
    ");

} else {

    mysqli_query($conn,"
    INSERT INTO likes(post_id,user_id)
    VALUES('$post_id','$user_id')
    ");

}

header("Location: ../posts/detail.php?id=".$post_id);
exit;
?>