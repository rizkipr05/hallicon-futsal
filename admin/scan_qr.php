<?php
session_start();
require "session_admin.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:login.php");
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

  <title>Scan QR</title>
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
          <a class="navbar-brand" href="#">Admin Dashboard</a>
        </div>
      </nav>

      <div class="container">
        <h3 class="mt-4">Scan QR Check-in</h3>
        <hr>
        <div class="row g-4">
          <div class="col-lg-5">
            <div id="qr-reader" class="border rounded p-2 bg-white"></div>
            <p class="text-muted mt-2">Arahkan kamera ke QR code pesanan.</p>
          </div>
          <div class="col-lg-7">
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Hasil Scan</h5>
                <div id="scan-status" class="alert alert-info">Menunggu scan...</div>
                <div id="scan-detail"></div>
              </div>
            </div>

            <div class="card shadow-sm mt-3">
              <div class="card-body">
                <h6 class="card-title">Input Manual</h6>
                <div class="input-group">
                  <input type="text" id="manual-input" class="form-control" placeholder="SEWA:123">
                  <button type="button" id="manual-submit" class="btn btn-primary">Cek</button>
                </div>
                <small class="text-muted">Gunakan jika kamera tidak tersedia.</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      const hamBurger = document.querySelector(".toggle-btn");
      hamBurger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
      });
    </script>

    <script src="https://unpkg.com/html5-qrcode@2.3.9/html5-qrcode.min.js"></script>
    <script>
      const statusEl = document.getElementById('scan-status');
      const detailEl = document.getElementById('scan-detail');
      const manualInput = document.getElementById('manual-input');
      const manualSubmit = document.getElementById('manual-submit');

      let isProcessing = false;

      function setStatus(type, message) {
        statusEl.className = 'alert alert-' + type;
        statusEl.textContent = message;
      }

      function renderDetail(data) {
        detailEl.innerHTML = `
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Nama:</strong> ${data.nama}</li>
            <li class="list-group-item"><strong>Lapangan:</strong> ${data.lapangan}</li>
            <li class="list-group-item"><strong>Tgl Pesan:</strong> ${data.tanggal_pesan}</li>
            <li class="list-group-item"><strong>Jam Main:</strong> ${data.jam_mulai}</li>
            <li class="list-group-item"><strong>Jam Habis:</strong> ${data.jam_habis}</li>
            <li class="list-group-item"><strong>Status:</strong> ${data.status}</li>
          </ul>
        `;
      }

      async function checkPayload(payload) {
        if (!payload || isProcessing) {
          return;
        }
        isProcessing = true;
        setStatus('info', 'Memeriksa data...');
        detailEl.innerHTML = '';

        try {
          const res = await fetch('controller/scan_qr.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'payload=' + encodeURIComponent(payload)
          });
          const data = await res.json();
          if (!res.ok || !data || !data.success) {
            setStatus('danger', data && data.message ? data.message : 'QR tidak valid.');
            return;
          }
          setStatus('success', 'QR valid. Pesanan terkonfirmasi.');
          renderDetail(data.data);
        } catch (err) {
          setStatus('danger', 'Gagal memeriksa QR.');
        } finally {
          isProcessing = false;
        }
      }

      manualSubmit.addEventListener('click', function() {
        checkPayload(manualInput.value.trim());
      });

      const qrReader = new Html5Qrcode('qr-reader');
      const config = { fps: 10, qrbox: 250 };

      qrReader.start(
        { facingMode: 'environment' },
        config,
        (decodedText) => {
          checkPayload(decodedText);
        },
        () => {}
      ).catch(() => {
        setStatus('warning', 'Kamera tidak tersedia. Gunakan input manual.');
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>
