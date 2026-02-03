<?php
session_start();
require "../functions.php";

$loggedIn = isset($_SESSION['role']);
$id_user = null;
$profil = null;

if ($loggedIn) {
  $id_user = $_SESSION["id_user"];
  $profil = query("SELECT * FROM user_212279 WHERE 212279_id_user = '$id_user'")[0];
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$conn = $conn ?? null;
$escapedSearch = $search;
if ($search !== '') {
  $escapedSearch = mysqli_real_escape_string($conn, $search);
}

$jmlHalamanPerData = 10;

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

$halamanAktif = isset($_GET['halaman']) ? max(1, intval($_GET['halaman'])) : 1;
$halamanAktif = min($halamanAktif, $jmlHalaman);
$awalData = ($jmlHalamanPerData * $halamanAktif) - $jmlHalamanPerData;

$match = query("SELECT sewa_212279.212279_id_sewa,
  user_212279.212279_nama_lengkap,
  lapangan_212279.212279_nama,
  sewa_212279.212279_tanggal_pesan,
  sewa_212279.212279_jam_mulai,
  sewa_212279.212279_jam_habis,
  sewa_212279.212279_lama_sewa
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
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Jempol Futsal</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="../assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="#" class="logo d-flex align-items-center me-auto">
        <img src="../assets/img/WhatsApp Image 2025-01-15 at 23.09.18_f546f133.jpg" alt="">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="../index.php">Beranda<br></a></li>
          <li><a href="lapangan.php">Lapangan</a></li>
          <?php if ($loggedIn) : ?>
            <li>
              <a href="pesanan.php">Pesanan</a>
            </li>
          <?php endif; ?>
          <li><a href="find_match.php" class="active">Find Match</a></li>
          <li><a href="membership.php">Membership</a></li>
          <li><a href="../kontak.php">Kontak</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <?php if ($loggedIn) : ?>
        <div class="dropdown">
          <a class="btn-getstarted dropdown-toggle profile-trigger" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../img/<?= $profil["212279_foto"]; ?>" alt="Foto Profil" class="profile-thumb">
            <span>Profil</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="edit_profil.php">Edit Profil</a></li>
            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
          </ul>
        </div>
      <?php else : ?>
        <a href="../login.php" class="btn-getstarted" type="submit">
          <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
      <?php endif; ?>

    </div>
  </header>

  <main class="main">
    <div class="page-title" data-aos="fade">
      <img src="../assets/img/photo-1630420598913-44208d36f9af.jpg" alt="">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1>Find Match</h1>
              <p class="mb-0">Daftar jadwal lapangan yang sudah dikonfirmasi</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <form method="get" class="row g-2 align-items-end mt-4">
        <div class="col-md-6">
          <label class="form-label">Cari Nama / Lapangan / Tanggal</label>
          <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search); ?>" placeholder="Contoh: Gold, 2025-01-15">
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary">Cari</button>
          <a href="find_match.php" class="btn btn-secondary">Reset</a>
        </div>
      </form>

      <div class="table-responsive mt-4">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th scope="col">No</th>
              <th scope="col">Nama Pemesan</th>
              <th scope="col">Nama Lapangan</th>
              <th scope="col">Tanggal Pesan</th>
              <th scope="col">Jam Main</th>
              <th scope="col">Jam Habis</th>
              <th scope="col">Lama</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($match)) : ?>
              <tr>
                <td colspan="7" class="text-center">Belum ada match terkonfirmasi.</td>
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
                  <td><?= $row["212279_lama_sewa"]; ?> Jam</td>
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
  </main>

  <footer id="footer" class="footer position-relative light-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-6 col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">Lokasi</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Babakan, Kec. Legok, Kabupaten Tangerang, Banten 15820</p>
            <p class="mt-3"><strong>Phone:</strong> <span>085810731351</span></p>
            <p><strong>instagram:</strong> <span>@halliconlegok</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-whatsapp"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
          </div>
        </div>

        <div class=" col-6 col-lg-4 col-md-6 footer-links">
          <h4>Navigasi</h4>
          <div class="row">
            <div class="col-6 col-lg-4">
              <ul>
                <li><a href="../index.php">Beranda</a></li>
                <li><a href="lapangan.php">Lapangan</a></li>
                <li><a href="membership.php">Membership</a></li>
              </ul>
            </div>
            <div class="col-6 col-lg-4">
              <ul>
                <li><a href="../kontak.php">Kontak</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-4 col-md-6 footer-links">
          <h4>Syarat & Ketentuan</h4>
          <ul>
            <li><a href="#">Lihat Syarat & Ketentuan</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../assets/js/main.js"></script>
</body>

</html>
