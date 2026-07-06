<?php

session_start();

include "../config/koneksi.php";

$post_id=$_POST['post_id'];

$comment=$_POST['comment'];

$user_id=$_SESSION['id'];

mysqli_query($conn,"
INSERT INTO comments(post_id,user_id,comment)
VALUES('$post_id','$user_id','$comment')
");

header("Location: ../posts/detail.php?id=".$post_id);