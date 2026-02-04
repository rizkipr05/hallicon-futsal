<?php
session_start();
require "session_admin.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:login.php");
}

if (isset($_POST['tambah_membership'])) {
  if (tambahMembership($_POST) > 0) {
    echo "<script>alert('Paket membership berhasil ditambahkan');document.location.href='membership.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan paket');</script>";
  }
}

if (isset($_POST['edit_membership'])) {
  if (editMembership($_POST) > 0) {
    echo "<script>alert('Paket membership berhasil diubah');document.location.href='membership.php';</script>";
  } else {
    echo "<script>alert('Gagal mengubah paket');</script>";
  }
}

$membershipReady = ensureMembershipTable();
$packages = $membershipReady
  ? query("SELECT * FROM membership_212279 ORDER BY 212279_urutan, 212279_id_membership")
  : [];
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
  <title>Membership</title>
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

      <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Atur Paket Membership</h3>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMembershipModal">Tambah</button>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga/Jam</th>
                <th>Durasi</th>
                <th>Kapasitas</th>
                <th>Fitur</th>
                <th>Urutan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($packages) === 0): ?>
                <tr><td colspan="9" class="text-center">Belum ada paket membership.</td></tr>
              <?php endif; ?>
              <?php $i = 1; ?>
              <?php foreach ($packages as $row): ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= htmlspecialchars($row['212279_nama']); ?></td>
                  <td>Rp <?= number_format((int) $row['212279_harga'], 0, ',', '.'); ?></td>
                  <td><?= (int) $row['212279_durasi_jam']; ?> Jam</td>
                  <td><?= (int) $row['212279_kapasitas']; ?> Orang</td>
                  <td>
                    <?= $row['212279_bola_gratis'] ? 'Bola Gratis, ' : ''; ?>
                    <?= $row['212279_minuman_gratis'] ? 'Minuman Gratis, ' : ''; ?>
                    <?= $row['212279_diskon_hari_kerja'] ? 'Diskon Hari Kerja, ' : ''; ?>
                    <?= $row['212279_featured'] ? 'Featured, ' : ''; ?>
                    <?= $row['212279_populer'] ? 'Populer' : ''; ?>
                  </td>
                  <td><?= (int) $row['212279_urutan']; ?></td>
                  <td><?= $row['212279_aktif'] ? 'Aktif' : 'Nonaktif'; ?></td>
                  <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editMembership<?= $row['212279_id_membership']; ?>">Edit</button>
                    <a href="controller/hapusMembership.php?id=<?= $row['212279_id_membership']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                  </td>
                </tr>

                <div class="modal fade" id="editMembership<?= $row['212279_id_membership']; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="post">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit Paket</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="id_membership" value="<?= $row['212279_id_membership']; ?>">
                          <div class="mb-3">
                            <label class="form-label">Nama Paket</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['212279_nama']); ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Harga / Jam</label>
                            <input type="number" name="harga" class="form-control" min="0" value="<?= (int) $row['212279_harga']; ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Durasi (Jam)</label>
                            <input type="number" name="durasi_jam" class="form-control" min="1" value="<?= (int) $row['212279_durasi_jam']; ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" name="kapasitas" class="form-control" min="1" value="<?= (int) $row['212279_kapasitas']; ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Fitur</label>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="bola_gratis" id="bola<?= $row['212279_id_membership']; ?>" <?= $row['212279_bola_gratis'] ? 'checked' : ''; ?>>
                              <label class="form-check-label" for="bola<?= $row['212279_id_membership']; ?>">Bola Futsal Gratis</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="minuman_gratis" id="minuman<?= $row['212279_id_membership']; ?>" <?= $row['212279_minuman_gratis'] ? 'checked' : ''; ?>>
                              <label class="form-check-label" for="minuman<?= $row['212279_id_membership']; ?>">Minuman Gratis</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="diskon_hari_kerja" id="diskon<?= $row['212279_id_membership']; ?>" <?= $row['212279_diskon_hari_kerja'] ? 'checked' : ''; ?>>
                              <label class="form-check-label" for="diskon<?= $row['212279_id_membership']; ?>">Diskon Hari Kerja</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="featured" id="featured<?= $row['212279_id_membership']; ?>" <?= $row['212279_featured'] ? 'checked' : ''; ?>>
                              <label class="form-check-label" for="featured<?= $row['212279_id_membership']; ?>">Featured</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="populer" id="populer<?= $row['212279_id_membership']; ?>" <?= $row['212279_populer'] ? 'checked' : ''; ?>>
                              <label class="form-check-label" for="populer<?= $row['212279_id_membership']; ?>">Populer</label>
                            </div>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" min="0" value="<?= (int) $row['212279_urutan']; ?>">
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif<?= $row['212279_id_membership']; ?>" <?= $row['212279_aktif'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="aktif<?= $row['212279_id_membership']; ?>">Aktif</label>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary" name="edit_membership">Simpan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="tambahMembershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Paket</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nama Paket</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Harga / Jam</label>
              <input type="number" name="harga" class="form-control" min="0" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Durasi (Jam)</label>
              <input type="number" name="durasi_jam" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Kapasitas</label>
              <input type="number" name="kapasitas" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Fitur</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="bola_gratis" id="bola_new" checked>
                <label class="form-check-label" for="bola_new">Bola Futsal Gratis</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="minuman_gratis" id="minuman_new">
                <label class="form-check-label" for="minuman_new">Minuman Gratis</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="diskon_hari_kerja" id="diskon_new">
                <label class="form-check-label" for="diskon_new">Diskon Hari Kerja</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="featured" id="featured_new">
                <label class="form-check-label" for="featured_new">Featured</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="populer" id="populer_new">
                <label class="form-check-label" for="populer_new">Populer</label>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Urutan</label>
              <input type="number" name="urutan" class="form-control" min="0" value="0">
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="aktif" id="aktif_new" checked>
              <label class="form-check-label" for="aktif_new">Aktif</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" name="tambah_membership">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
