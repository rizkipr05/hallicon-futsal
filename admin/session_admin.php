<!-- cek apakah sudah login sebagai admin -->
<?php

if (!isset($_SESSION['role'])) {
  header("location:login.php");
} else {
  $role = $_SESSION['role'];
}
?>
