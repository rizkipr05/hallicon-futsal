<?php
session_start();
require "functions.php";

$loggedIn = isset($_SESSION['role']);

if ($loggedIn) {

  $id_user = $_SESSION["id_user"];

  // Melakukan query hanya jika $_SESSION["id_user"] sudah terdefinisi
  $profil = query("SELECT * FROM user_212279 WHERE 212279_id_user = '$id_user'")[0];
}

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


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="#" class="logo d-flex align-items-center me-auto">
                <img src="assets/img/WhatsApp Image 2025-01-15 at 23.09.18_f546f133.jpg" alt="">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php">Beranda<br></a></li>
                    <li><a href="user/lapangan.php">Lapangan</a></li>
                    <?php if ($loggedIn): ?>
            <li class="nav-item">
              <a href="user/pesanan.php">Pesanan</a>
            </li>
        <?php endif; ?>
                    <li><a href="user/membership.php" >Membership</a></li>
                    <li><a href="kontak.php" class="active">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            <?php if ($loggedIn): ?>
            <!-- Jika sudah login, tampilkan tombol profil -->
            <a class="btn-getstarted" data-bs-toggle="modal" data-bs-target="#profilModal">
                <i class="bi bi-person"></i> Profil
            </a>
        <?php else: ?>
            <!-- Jika belum login, tampilkan tombol login -->
            <a href="login.php" class="btn-getstarted" type="submit">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        <?php endif; ?>



        </div>
    </header>

      <!-- Modal Profil -->
      <div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="profilModalLabel">Profil Pengguna</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-4 my-5">
                    <img src="img/<?= $profil["212279_foto"]; ?>" alt="Foto Profil" class="img-fluid ">
                  </div>
                  <div class="col-8">
                    <h5 class="mb-3"><?= $profil["212279_nama_lengkap"]; ?></h5>
                    <p><?= $profil["212279_jenis_kelamin"]; ?></p>
                    <p><?= $profil["212279_email"]; ?></p>
                    <p><?= $profil["212279_no_handphone"]; ?></p>
                    <p><?= $profil["212279_alamat"]; ?></p>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                    <a href="" data-bs-toggle="modal" data-bs-target="#editProfilModal" class="btn btn-inti">Edit Profil</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Modal Profil -->

      <!-- Edit profil -->
  <div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog edit modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfilModalLabel">Edit Profil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="fotoLama" class="form-control" id="exampleInputPassword1" value="<?= $profil["212279_foto"]; ?>">
          <div class="modal-body">
            <div class="row justify-content-center align-items-center">
              <div class="mb-3">
                <img src="img/<?= $profil["212279_foto"]; ?>" alt="Foto Profil" class="img-fluid ">
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Nama Lengkap</label>
                  <input type="text" name="212279_nama_lengkap" class="form-control" id="exampleInputPassword1" value="<?= $profil["212279_nama_lengkap"]; ?>">
                </div>
                <div class="mb-3">
                  <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                  <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="Laki-laki" <?php if ($profil['212279_jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if ($profil['212279_jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="212279_no_handphone" class="form-label">No Telp</label>
                  <input type="number" name="212279_no_handphone" class="form-control" id="exampleInputPassword1" value="<?= $profil["212279_no_handphone"]; ?>">
                </div>
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="exampleInputPassword1" value="<?= $profil["212279_email"]; ?>">
                </div>
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">alamat</label>
                <input type="text" name="212279_alamat" class="form-control" id="exampleInputPassword1" value="<?= $profil["212279_alamat"]; ?>">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Foto : </label>
                <input type="file" name="212279_foto" class="form-control" id="exampleInputPassword1">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-inti" name="simpan" id="simpan">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Edit Modal -->


   <main class="main">
   <section id="contact" class="contact section">

<div class="mb-5" data-aos="fade-up" data-aos-delay="200">
  <iframe style="border:0; width: 100%; height: 300px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.3914843820053!2d105.25494839999999!3d-5.357085100000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c52ff8c4ab75%3A0xd64e118f07f67bd0!2sFutsal%20Jempol!5e0!3m2!1sid!2sid!4v1736950060971!5m2!1sid!2sid" frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div><!-- End Google Maps -->

<div class="container" data-aos="fade-up" data-aos-delay="100">

  <div class="row gy-4">

    <div class="col-lg-4">
      <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
        <i class="bi bi-geo-alt flex-shrink-0"></i>
        <div>
          <h3>Alamat</h3>
          <p>Jl. Bougenville No.17, Labuhan Dalam, Kec. Tj. Senang, Kota Bandar Lampung, Lampung 35141</p>
        </div>
      </div><!-- End Info Item -->

      <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
        <i class="bi bi-telephone flex-shrink-0"></i>
        <div>
          <h3>No Telepon</h3>
          <p>0821-8222-6616</p>
        </div>
      </div><!-- End Info Item -->

      <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
        <i class="bi bi-envelope flex-shrink-0"></i>
        <div>
          <h3>Email Us</h3>
          <p>JempolFutsal@gmail.com</p>
        </div>
      </div><!-- End Info Item -->
    </div>

  <div class="col-lg-8">
  <form id="whatsapp-form" data-aos="fade-up" data-aos-delay="200">
    <div class="row gy-4">
      <div class="col-md-12">
        <input type="text" id="name" class="form-control rounded" placeholder="Nama Lengkap" required="">
      </div>
      <div class="col-md-12">
        <textarea id="message" class="form-control rounded" rows="6" placeholder="Pesan" required=""></textarea>
      </div>
      <div class="col-md-12 text-center">
        <button type="button" id="send-btn" class="btn-custom">Kirim via WhatsApp</button>
      </div>
    </div>
  </form>
</div>

</div>
</div>
</section>
<!-- /Contact Section -->
   </main>

   <footer id="footer" class="footer position-relative light-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-6 col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">Lokasi</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Jl. Bougenville No.17, Labuhan Dalam, Kec. Tj. Senang, Kota Bandar Lampung, Lampung 35141</p>
            <p class="mt-3"><strong>Phone:</strong> <span>0821-8222-6616</span></p>
            <p><strong>Email:</strong> <span>JempolFutsal@gmail.com</span></p>
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
                <li><a href="#">Beranda</a></li>
                <li><a href="user/lapangan.php">Lapangan</a></li>
                <li><a href="user/membership.php">Membership</a></li>
              </ul>
            </div>
            <div class="col-6 col-lg-4">
              <ul>
                <li><a href="kontak.php">Kontak</a></li>
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

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>


<script>
   document.getElementById('send-btn').addEventListener('click', function () {
    // Ambil data dari input
    const name = document.getElementById('name').value.trim();
    const message = document.getElementById('message').value.trim();

    // Validasi input
    if (name === '' || message === '') {
      alert('Harap isi semua bidang!');
      return;
    }

    // Nomor WhatsApp tujuan (ganti dengan nomor Anda, gunakan format internasional tanpa tanda "+" atau spasi)
    const phoneNumber = '6288276477014';

    // Format pesan WhatsApp
    const whatsappMessage = `Halo, saya ${name}.\n\n${message}`;

    // Buat URL WhatsApp
    const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(whatsappMessage)}`;

    // Buka WhatsApp tanpa mereset formulir
    window.open(whatsappURL, '_blank');

    // Inputan tetap ada, jadi tidak perlu dihapus
  });
</script>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>