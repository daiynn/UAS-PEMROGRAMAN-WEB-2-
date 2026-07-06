<?php
session_start();
include "../config/koneksi.php";

if(isset($_POST['register'])){

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$cek = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' OR email='$email'");

if(mysqli_num_rows($cek)>0){

$error="Username atau Email sudah digunakan.";

}else{

mysqli_query($conn,"INSERT INTO users(username,email,password)
VALUES('$username','$email','$password')");

header("Location: login.php");
exit;

}

}

include "../includes/header.php";
?>

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-md-5">

<div class="card p-4">

<div class="text-center logo mb-3">

THE CATRAP

</div>

<h4 class="text-center mb-4">

Register

</h4>

<?php
if(isset($error)){
echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

<input
type="text"
name="username"
class="form-control mb-3"
placeholder="Username"
required>

<input
type="email"
name="email"
class="form-control mb-3"
placeholder="Email"
required>

<input
type="password"
name="password"
class="form-control mb-3"
placeholder="Password"
required>

<button
class="btn btn-gold w-100"
name="register">

Daftar

</button>

</form>

<div class="text-center mt-3">

Sudah punya akun?

<a href="login.php">

Login

</a>

</div>

</div>

</div>

</div>

</div>

<?php
include "../includes/footer.php";
?>