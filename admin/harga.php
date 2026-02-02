<?php
session_start();
require "session_admin.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:login.php");
}

if (isset($_POST['tambah_harga'])) {
  if (tambahHarga($_POST) > 0) {
    echo "<script>alert('Harga berhasil ditambahkan');document.location.href='harga.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan harga');</script>";
  }
}

if (isset($_POST['edit_harga'])) {
  if (editHarga($_POST) > 0) {
    echo "<script>alert('Harga berhasil diubah');document.location.href='harga.php';</script>";
  } else {
    echo "<script>alert('Gagal mengubah harga');</script>";
  }
}

$rules = query("SELECT * FROM harga_212279 ORDER BY 212279_hari, 212279_jam_mulai");
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
  <title>Harga Per Jam</title>
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
          <a class="navbar-brand" href="#">Admin Dashboard</a>
        </div>
      </nav>

      <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Atur Harga Per Jam</h3>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahHargaModal">Tambah</button>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Harga/Jam</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($rules) === 0): ?>
                <tr><td colspan="6" class="text-center">Belum ada data harga.</td></tr>
              <?php endif; ?>
              <?php $i = 1; ?>
              <?php foreach ($rules as $row): ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= $row['212279_hari'] === 'weekend' ? 'Weekend' : 'Weekday'; ?></td>
                  <td><?= sprintf('%02d:00', $row['212279_jam_mulai']); ?></td>
                  <td><?= sprintf('%02d:00', $row['212279_jam_selesai']); ?></td>
                  <td><?= $row['212279_harga']; ?></td>
                  <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editHarga<?= $row['212279_id_harga']; ?>">Edit</button>
                    <a href="controller/hapusHarga.php?id=<?= $row['212279_id_harga']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                  </td>
                </tr>

                <div class="modal fade" id="editHarga<?= $row['212279_id_harga']; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="post">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit Harga</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="id_harga" value="<?= $row['212279_id_harga']; ?>">
                          <div class="mb-3">
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-control" required>
                              <option value="weekday" <?= $row['212279_hari'] === 'weekday' ? 'selected' : ''; ?>>Weekday</option>
                              <option value="weekend" <?= $row['212279_hari'] === 'weekend' ? 'selected' : ''; ?>>Weekend</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Jam Mulai (0-23)</label>
                            <input type="number" name="jam_mulai" class="form-control" min="0" max="23" value="<?= $row['212279_jam_mulai']; ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Jam Selesai (1-24)</label>
                            <input type="number" name="jam_selesai" class="form-control" min="1" max="24" value="<?= $row['212279_jam_selesai']; ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Harga / Jam</label>
                            <input type="number" name="harga" class="form-control" min="0" value="<?= $row['212279_harga']; ?>" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary" name="edit_harga">Simpan</button>
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

  <div class="modal fade" id="tambahHargaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Harga</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Hari</label>
              <select name="hari" class="form-control" required>
                <option value="weekday">Weekday</option>
                <option value="weekend">Weekend</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Jam Mulai (0-23)</label>
              <input type="number" name="jam_mulai" class="form-control" min="0" max="23" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Jam Selesai (1-24)</label>
              <input type="number" name="jam_selesai" class="form-control" min="1" max="24" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Harga / Jam</label>
              <input type="number" name="harga" class="form-control" min="0" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" name="tambah_harga">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
