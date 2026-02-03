<?php
session_start();
require "session_admin.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:login.php");
}

// Pagination
$jmlHalamanPerData = 4;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$baseQuery = "FROM sewa_212279
JOIN user_212279 ON sewa_212279.212279_id_user = user_212279.212279_id_user
LEFT JOIN bayar_212279 ON sewa_212279.212279_id_sewa = bayar_212279.212279_id_sewa
WHERE 1=1";

if ($search) {
  $baseQuery .= " AND user_212279.212279_nama_lengkap LIKE '%$search%'";
}

if ($statusFilter) {
  $baseQuery .= " AND IFNULL(bayar_212279.212279_konfirmasi,'Belum') = '$statusFilter'";
}

$jumlahData = count(query("SELECT sewa_212279.212279_id_sewa $baseQuery"));
$jmlHalaman = ceil($jumlahData / $jmlHalamanPerData);

if (isset($_GET["halaman"])) {
  $halamanAktif = $_GET["halaman"];
} else {
  $halamanAktif = 1;
}

$awalData = ($jmlHalamanPerData * $halamanAktif) - $jmlHalamanPerData;

$queryParams = [];
if ($search) {
  $queryParams['search'] = $search;
}
if ($statusFilter) {
  $queryParams['status'] = $statusFilter;
}

$pesan = query("SELECT sewa_212279.212279_id_sewa,
  user_212279.212279_nama_lengkap,
  sewa_212279.212279_tanggal_pesan,
  sewa_212279.212279_jam_mulai,
  sewa_212279.212279_lama_sewa,
  sewa_212279.212279_total,
  bayar_212279.212279_bukti,
  IFNULL(bayar_212279.212279_konfirmasi,'Belum') AS 212279_konfirmasi
  $baseQuery
  LIMIT $awalData, $jmlHalamanPerData");


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

  <title>Home</title>
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
          <a class="navbar-brand" href="#">
            Admin Dashboard
          </a>
        </div>
      </nav>
        <!-- Konten -->
        <div class="container">
          <form method="get" class="row g-2 align-items-end mt-3 mb-3">
            <div class="col-md-4">
              <label class="form-label">Cari Nama</label>
              <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-control">
                <option value="">Semua</option>
                <option value="Belum" <?= $statusFilter === 'Belum' ? 'selected' : ''; ?>>Belum</option>
                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Terkonfirmasi" <?= $statusFilter === 'Terkonfirmasi' ? 'selected' : ''; ?>>Terkonfirmasi</option>
                <option value="Gagal" <?= $statusFilter === 'Gagal' ? 'selected' : ''; ?>>Gagal</option>
              </select>
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="pesan.php" class="btn btn-secondary">Reset</a>
            </div>
          </form>
        <!-- Konten -->
        <h3 class="mt-4">Data Pesanan</h3>
        <hr>
        <form action="" method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama customer..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        <a href="export.php" class="btn btn-inti mb-2">Download</a>
        <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-inti">
            <tr>
              <th scope="col">No</th>
              <th scope="col">NamaCust</th>
              <th scope="col">TglPesan</th>
              <th scope="col">TglMain</th>
              <th scope="col">Lama</th>
              <th scope="col">Total</th>
              <th scope="col">Bukti</th>
              <th scope="col">Konfir</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            <?php foreach ($pesan as $row) : ?>
              <tr>
                <td><?= $i++; ?></td>
                <td><?= $row["212279_nama_lengkap"]; ?></td>
                <td><?= $row["212279_tanggal_pesan"]; ?></td>
                <td><?= $row["212279_jam_mulai"]; ?></td>
                <td><?= $row["212279_lama_sewa"]; ?></td>
                <td><?= $row["212279_total"]; ?></td>
                <td>
                  <?php
                  $bukti = $row["212279_bukti"];
                  $buktiPath = "../img/" . $bukti;
                  if (!empty($bukti) && file_exists($buktiPath)) {
                    echo '<img src="' . $buktiPath . '" width="50" height="50">';
                  } else {
                    echo $bukti ? $bukti : '-';
                  }
                  ?>
                </td>
                <td><?= $row["212279_konfirmasi"]; ?></td>
                <td>
                  <?php
                  $id_sewa = $row["212279_id_sewa"];
                  if ($row["212279_konfirmasi"] == "Terkonfirmasi") {
                    // tampilkan tombol Bayar dan Hapus
                    echo '';
                  } else {
                    // tampilkan tombol Detail
                    echo ' <button type="button" class="btn btn-inti" data-bs-toggle="modal" data-bs-target="#konfirmasiModal' . $id_sewa . '">
                    Konfir
                  </button>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal' . $id_sewa . '">
                    Hapus
                  </button>
                  ';
                  }
                  ?>
                </td>
              </tr>
              <!-- Modal Konfirmasi -->
              <div class="modal fade" id="konfirmasiModal<?= $row["212279_id_sewa"]; ?>" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Pesanan <?= $row["212279_nama_lengkap"]; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Anda yakin ingin mengkonfirmasi pesanan ini?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <a href="./controller/konfirmasiPesan.php?id=<?= $row["212279_id_sewa"]; ?>" class="btn btn-primary">Konfirmasi</a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Modal Konfirmasi -->

              <!-- Modal Hapus -->
              <div class="modal fade" id="hapusModal<?= $row["212279_id_sewa"]; ?>" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="hapusModalLabel">Hapus Pesanan <?= $row["212279_nama_lengkap"]; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Anda yakin ingin menghapus pesanan ini?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <a href="./controller/hapusPesan.php?id=<?= $row["212279_id_sewa"]; ?>" class="btn btn-danger">Hapus</a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Modal Konfirmasi -->
            <?php endforeach; ?>
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
              <li class="page-item "><a class="page-link" href="?<?= $pagePrefix; ?>halaman=<?= $i; ?>"><?= $i; ?></a></li>
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
