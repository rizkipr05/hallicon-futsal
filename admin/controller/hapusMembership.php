<?php
require "../../functions.php";

$id = $_GET['id'] ?? 0;
if (hapusMembership($id) > 0) {
  echo "<script>
    alert('Paket berhasil dihapus');
    document.location.href = '../membership.php';
  </script>";
} else {
  echo "<script>
    alert('Paket gagal dihapus');
    document.location.href = '../membership.php';
  </script>";
}
