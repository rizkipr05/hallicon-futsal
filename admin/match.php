<?php
session_start();
require "session_admin.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:login.php");
}

$jmlHalamanPerData = 6;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$escapedSearch = $search;
if ($search !== '') {
  $escapedSearch = mysqli_real_escape_string($conn, $search);
}

$baseQuery = "FROM sewa_212279
JOIN user_212279 ON sewa_212279.212279_id_user = user_212279.212279_id_user
JOIN lapangan_212279 ON sewa_212279.212279_id_lapangan = lapangan_212279.212279_id_lapangan
LEFT JOIN bayar_212279 ON sewa_212279.212279_id_sewa = bayar_212279.212279_id_sewa
WHERE IFNULL(bayar_212279.212279_konfirmasi,'Belum') = 'Terkonfirmasi'";

if ($search !== '') {
  $baseQuery .= " AND (user_212279.212279_nama_lengkap LIKE '%$escapedSearch%'
    OR lapangan_212279.212279_nama LIKE '%$escapedSearch%'
    OR sewa_212279.212279_tanggal_pesan LIKE '%$escapedSearch%')";
}

$jumlahData = count(query("SELECT sewa_212279.212279_id_sewa $baseQuery"));
$jmlHalaman = max(1, ceil($jumlahData / $jmlHalamanPerData));
$halamanAktif = isset($_GET["halaman"]) ? max(1, intval($_GET["halaman"])) : 1;
$halamanAktif = min($halamanAktif, $jmlHalaman);
$awalData = ($jmlHalamanPerData * $halamanAktif) - $jmlHalamanPerData;

$match = query("SELECT sewa_212279.212279_id_sewa,
  user_212279.212279_nama_lengkap,
  lapangan_212279.212279_nama,
  sewa_212279.212279_tanggal_pesan,
  sewa_212279.212279_jam_mulai,
  sewa_212279.212279_jam_habis,
  sewa_212279.212279_lama_sewa,
  sewa_212279.212279_total,
  bayar_212279.212279_bukti
  $baseQuery
  ORDER BY sewa_212279.212279_jam_mulai DESC
  LIMIT $awalData, $jmlHalamanPerData");

$queryParams = [];
if ($search !== '') {
  $queryParams['search'] = $search;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

  <title>Data Match</title>
</head>

<body>
  <div class="wrapper">
    <aside id="sidebar">
      <div class="d-flex">
        <button class="toggle-btn" type="button">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
      <ul class="sidebar-nav">
        <li class="sidebar-item">
          <a href="home.php" class="sidebar-link">
            <i class="fa-solid fa-house"></i>
            <span>Beranda</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="member.php" class="sidebar-link">
            <i class="fa-solid fa-user"></i>
            <span>Data Member</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="lapangan.php" class="sidebar-link">
            <i class="fa-solid fa-dumbbell"></i>
            <span>Data Lapangan</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="pesan.php" class="sidebar-link">
            <i class="fa-solid fa-money-bills"></i>
            <span>Data Pesanan</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="match.php" class="sidebar-link">
            <i class="fa-solid fa-people-group"></i>
            <span>Data Match</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="scan_qr.php" class="sidebar-link">
            <i class="fa-solid fa-qrcode"></i>
            <span>Scan QR</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="harga.php" class="sidebar-link">
            <i class="fa-solid fa-clock"></i>
            <span>Harga Per Jam</span>
          </a>
        </li>
                <li class="sidebar-item">
          <a href="membership.php" class="sidebar-link">
            <i class="fa-solid fa-id-card"></i>
            <span>Membership</span>
          </a>
        </li>
<li class="sidebar-item">
          <a href="admin.php" class="sidebar-link">
            <i class="fa-solid fa-user-tie"></i>
            <span>Data Admin</span>
          </a>
        </li>
      </ul>
      <div class="sidebar-footer">
        <a href="../logout.php" class="sidebar-link">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span>Logout</span>
        </a>
      </div>
    </aside>
    <div class="main">
      <nav class="navbar bg-light shadow">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Admin Dashboard</a>
        </div>
      </nav>
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mt-3">
          <h3 class="mt-4">Data Match Terkonfirmasi</h3>
          <a href="scan_qr.php" class="btn btn-inti">Scan QR</a>
        </div>
        <hr>
        <form method="get" class="row g-2 align-items-end mb-3">
          <div class="col-md-6">
            <label class="form-label">Cari Nama / Lapangan / Tanggal</label>
            <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search); ?>" placeholder="Contoh: Bronze, 2025-01-15">
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="match.php" class="btn btn-secondary">Reset</a>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-inti">
              <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Lapangan</th>
                <th scope="col">Tgl Pesan</th>
                <th scope="col">Jam Main</th>
                <th scope="col">Jam Habis</th>
                <th scope="col">Lama</th>
                <th scope="col">Total</th>
                <th scope="col">Order ID</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($match)) : ?>
                <tr>
                  <td colspan="9" class="text-center">Belum ada match terkonfirmasi.</td>
                </tr>
              <?php else : ?>
                <?php $i = $awalData + 1; ?>
                <?php foreach ($match as $row) : ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row["212279_nama_lengkap"]; ?></td>
                    <td><?= $row["212279_nama"]; ?></td>
                    <td><?= $row["212279_tanggal_pesan"]; ?></td>
                    <td><?= $row["212279_jam_mulai"]; ?></td>
                    <td><?= $row["212279_jam_habis"]; ?></td>
                    <td><?= $row["212279_lama_sewa"]; ?></td>
                    <td><?= $row["212279_total"]; ?></td>
                    <td><?= $row["212279_bukti"] ? $row["212279_bukti"] : '-'; ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <ul class="pagination">
          <?php
          $baseLink = http_build_query($queryParams);
          $pagePrefix = $baseLink ? $baseLink . '&' : '';
          ?>
          <?php if ($halamanAktif > 1) : ?>
            <li class="page-item">
              <a href="?<?= $pagePrefix; ?>halaman=<?= $halamanAktif - 1; ?>" class="page-link">Previous</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $jmlHalaman; $i++) : ?>
            <?php if ($i == $halamanAktif) : ?>
              <li class="page-item active"><a class="page-link" href="?<?= $pagePrefix; ?>halaman=<?= $i; ?>"><?= $i; ?></a></li>
            <?php else : ?>
              <li class="page-item"><a class="page-link" href="?<?= $pagePrefix; ?>halaman=<?= $i; ?>"><?= $i; ?></a></li>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($halamanAktif < $jmlHalaman) : ?>
            <li class="page-item">
              <a href="?<?= $pagePrefix; ?>halaman=<?= $halamanAktif + 1; ?>" class="page-link">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <script>
      const hamBurger = document.querySelector(".toggle-btn");

      hamBurger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>
