<?php
session_start();
require "../session_admin.php";
require "../../functions.php";

if ($role !== 'Admin') {
  header("location:../login.php");
  exit;
}

$id = $_GET['id'] ?? 0;
if (hapusHarga($id) > 0) {
  echo "<script>
    alert('Harga berhasil dihapus');
    document.location.href = '../harga.php';
  </script>";
} else {
  echo "<script>
    alert('Harga gagal dihapus');
    document.location.href = '../harga.php';
  </script>";
}
