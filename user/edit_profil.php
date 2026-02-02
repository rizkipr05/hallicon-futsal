<?php
session_start();
require "../functions.php";

$loggedIn = isset($_SESSION['role']);

if (!$loggedIn) {
  header("Location: ../login.php");
  exit;
}

$id_user = $_SESSION["id_user"];
$profil = query("SELECT * FROM user_212279 WHERE 212279_id_user = '$id_user'")[0];

if (isset($_POST["simpan"])) {
  if (edit($_POST) > 0) {
    echo "<script>
          alert('Berhasil Diubah');
          </script>";
  } else {
    echo "<script>
          alert('Gagal Diubah');
          </script>";
  }
  $profil = query("SELECT * FROM user_212279 WHERE 212279_id_user = '$id_user'")[0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Edit Profil</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
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
          <li><a href="pesanan.php">Pesanan</a></li>
          <li><a href="membership.php">Membership</a></li>
          <li><a href="../kontak.php">Kontak</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

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

    </div>
  </header>

  <main class="main">
    <section class="section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="shadow rounded p-4 bg-white">
              <h3 class="mb-4">Edit Profil</h3>
              <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="fotoLama" value="<?= $profil["212279_foto"]; ?>">

                <div class="row g-3 align-items-center">
                  <div class="col-md-4 text-center">
                    <img src="../img/<?= $profil["212279_foto"]; ?>" alt="Foto Profil" class="img-fluid rounded">
                  </div>
                  <div class="col-md-8">
                    <div class="mb-3">
                      <label class="form-label">Nama Lengkap</label>
                      <input type="text" name="nama_lengkap" class="form-control" value="<?= $profil["212279_nama_lengkap"]; ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Jenis Kelamin</label>
                      <select class="form-control" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?php if ($profil['212279_jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php if ($profil['212279_jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-md-6">
                    <label class="form-label">No Telp</label>
                    <input type="number" name="hp" class="form-control" value="<?= $profil["212279_no_handphone"]; ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $profil["212279_email"]; ?>" required>
                  </div>
                </div>

                <div class="mt-3">
                  <label class="form-label">Alamat</label>
                  <input type="text" name="alamat" class="form-control" value="<?= $profil["212279_alamat"]; ?>">
                </div>

                <div class="mt-3">
                  <label class="form-label">Foto</label>
                  <input type="file" name="foto" class="form-control">
                </div>

                <div class="mt-4 d-flex gap-2">
                  <a href="../index.php" class="btn btn-secondary">Batal</a>
                  <button type="submit" class="btn btn-inti" name="simpan" id="simpan">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>
