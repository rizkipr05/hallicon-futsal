<?php
session_start();
require "../functions.php";

if (isset($_SESSION["role"])) {
  $role = $_SESSION["role"];
  if ($role == "Admin") {
    header("Location: home.php");
  } else {
    header("Location: ../user/lapangan.php");
  }
}

if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $cariadmin = query("SELECT * FROM admin_212279 WHERE 212279_email = '$username' AND 212279_password = '$password'");

  if ($cariadmin) {
    $_SESSION['username'] = $cariadmin[0]['212279_nama'];
    $_SESSION['role'] = "Admin";
    header("Location: admin.php");
  } else {
    echo "<div class='alert alert-warning'>Username atau Password salah</div>
    <meta http-equiv='refresh' content='2'>";
  }
}
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="../style.css">
</head>

<body class="login">
  <div class="center">
    <h1>Login Admin</h1>
    <form method="POST">
      <div class="txt_field">
        <input type="email" name="username" required>
        <span></span>
        <label>Email</label>
      </div>
      <div class="txt_field">
        <input type="password" name="password" required>
        <span></span>
        <label>Password</label>
      </div>
      <button class="button btn-inti" name="login" id="login">Login</button>
      <div class="signup_link">
        Bukan admin? <a href="../login.php">Login User</a>
      </div>
    </form>
  </div>

</body>

</html>
