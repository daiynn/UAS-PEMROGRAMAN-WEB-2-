<?php
session_start();
include "../config/koneksi.php";

if(isset($_SESSION['login'])){
header("Location: ../index.php");
exit;
}

if(isset($_POST['login'])){

$email=$_POST['email'];
$password=$_POST['password'];

$query=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($query)==1){

$data=mysqli_fetch_assoc($query);

if(password_verify($password,$data['password'])){

$_SESSION['login']=true;
$_SESSION['id']=$data['id'];
$_SESSION['username']=$data['username'];
$_SESSION['role']=$data['role'];

header("Location: ../index.php");
exit;

}else{

$error="Password salah.";

}

}else{

$error="Email tidak ditemukan.";

}

}

include "../includes/header.php";
?>

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-md-5">

<div class="card p-4">

<div class="logo text-center">

THE CATRAP

</div>

<h4 class="text-center mb-4">

Login

</h4>

<?php
if(isset($error)){
echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

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
name="login">

Login

</button>

</form>

<div class="text-center mt-3">

Belum punya akun?

<a href="register.php">

Daftar

</a>

</div>

</div>

</div>

</div>

</div>

<?php
include "../includes/footer.php";
?>