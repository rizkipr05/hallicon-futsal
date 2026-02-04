<?php

$conn = mysqli_connect("localhost", "root", "", "dbfutsal");

function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

function getCount($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  if ($row = mysqli_fetch_assoc($result)) {
    return (int) $row['total'];
  }
  return 0;
}

function hapusMember($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM user_212279 WHERE 212279_id_user = $id");

  return mysqli_affected_rows($conn);
}

function hapusLpg($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM lapangan_212279 WHERE 212279_id_lapangan = $id");

  return mysqli_affected_rows($conn);
}

function hapusAdmin($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM admin_212279 WHERE 212279_id_user = $id");

  return mysqli_affected_rows($conn);
}

function hapusPesan($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM sewa_212279 WHERE 212279_id_sewa = $id");

  return mysqli_affected_rows($conn);
}

function daftar($data)
{
  global $conn;

  $username = strtolower(stripslashes($data["email"]));
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone = $data["hp"];
  $alamat = $data["alamat"];
  $gender = $data["gender"];
  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }

  $result = mysqli_query($conn, "SELECT 212279_email FROM user_212279 WHERE 212279_email = '$username'");

  if (mysqli_fetch_assoc($result)) {
    echo "<script>
            alert('Username sudah terdaftar!');
        </script>";
    return false;
  }
  mysqli_query($conn, "INSERT INTO user_212279 (212279_email,212279_password,212279_no_handphone,212279_jenis_kelamin,212279_nama_lengkap,212279_alamat,212279_foto) VALUES ('$username','$password','$no_handphone','$gender','$nama','$alamat','$upload')");
  return mysqli_affected_rows($conn);
}

function edit($data)
{
  global $conn;

  $userid = $_SESSION["id_user"];
  $username = strtolower(stripslashes($data["email"] ?? ($data["212279_email"] ?? "")));
  $nama = $data["nama_lengkap"] ?? ($data["212279_nama_lengkap"] ?? "");
  $no_handphone = $data["hp"] ?? ($data["212279_no_handphone"] ?? "");
  $gender = $data["jenis_kelamin"] ?? ($data["212279_jenis_kelamin"] ?? "");
  $alamat = $data["alamat"] ?? ($data["212279_alamat"] ?? "");
  $gambar = $data["foto"] ?? ($data["212279_foto"] ?? "");
  $gambarLama = $data["fotoLama"] ?? ($data["212279_foto"] ?? "");
  $fileKey = isset($_FILES["foto"]) ? "foto" : (isset($_FILES["212279_foto"]) ? "212279_foto" : "foto");

  // Cek apakah User pilih gambar baru
  if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]["error"] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  $query = "UPDATE user_212279 SET 212279_email = '$username', 
  212279_nama_lengkap = '$nama',
  212279_no_handphone = '$no_handphone',
  212279_jenis_kelamin = '$gender',
  212279_alamat = '$alamat',
  212279_foto = '$gambar'
  WHERE 212279_id_user = '$userid'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function getPricingRules($useFallbackDefaults = true)
{
  global $conn;
  ensurePricingTable();
  static $rulesCacheRaw = null;
  if ($rulesCacheRaw === null) {
    $rulesCacheRaw = [];

    try {
      $result = mysqli_query($conn, "SELECT * FROM harga_212279 ORDER BY 212279_hari, 212279_jam_mulai");
      if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
          $rulesCacheRaw[] = $row;
        }
      }
    } catch (mysqli_sql_exception $e) {
      $rulesCacheRaw = [];
    }
  }

  if (empty($rulesCacheRaw) && $useFallbackDefaults) {
    return [
      ['212279_hari' => 'weekday', '212279_jam_mulai' => 8,  '212279_jam_selesai' => 16, '212279_harga' => 1],
      ['212279_hari' => 'weekday', '212279_jam_mulai' => 16, '212279_jam_selesai' => 22, '212279_harga' => 1],
      ['212279_hari' => 'weekend', '212279_jam_mulai' => 8,  '212279_jam_selesai' => 22, '212279_harga' => 1],
    ];
  }

  return $rulesCacheRaw;
}

function ensurePricingTable()
{
  global $conn;
  try {
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS harga_212279 (
      212279_id_harga INT(11) NOT NULL AUTO_INCREMENT,
      212279_hari VARCHAR(10) NOT NULL,
      212279_jam_mulai TINYINT NOT NULL,
      212279_jam_selesai TINYINT NOT NULL,
      212279_harga INT(11) NOT NULL,
      PRIMARY KEY (212279_id_harga)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci");
    seedDefaultPricingRules();
    return true;
  } catch (mysqli_sql_exception $e) {
    return false;
  }
}

function seedDefaultPricingRules()
{
  global $conn;
  try {
    $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM harga_212279");
    if ($res && ($row = mysqli_fetch_assoc($res))) {
      if (intval($row['total']) > 0) {
        return;
      }
    }
    mysqli_query($conn, "INSERT INTO harga_212279 (212279_hari, 212279_jam_mulai, 212279_jam_selesai, 212279_harga) VALUES
      ('weekday', 8, 16, 100000),
      ('weekday', 16, 22, 150000),
      ('weekend', 8, 22, 170000)");
  } catch (mysqli_sql_exception $e) {
    // ignore seeding errors
  }
}

function hasPricingRules()
{
  $rules = getPricingRules(false);
  return !empty($rules);
}

function tambahHarga($data)
{
  global $conn;
  $hari = $data['hari'];
  $jamMulai = intval($data['jam_mulai']);
  $jamSelesai = intval($data['jam_selesai']);
  $harga = intval($data['harga']);

  mysqli_query($conn, "INSERT INTO harga_212279 (212279_hari, 212279_jam_mulai, 212279_jam_selesai, 212279_harga)
    VALUES ('$hari', '$jamMulai', '$jamSelesai', '$harga')");

  return mysqli_affected_rows($conn);
}

function editHarga($data)
{
  global $conn;
  $id = intval($data['id_harga']);
  $hari = $data['hari'];
  $jamMulai = intval($data['jam_mulai']);
  $jamSelesai = intval($data['jam_selesai']);
  $harga = intval($data['harga']);

  mysqli_query($conn, "UPDATE harga_212279 SET
    212279_hari = '$hari',
    212279_jam_mulai = '$jamMulai',
    212279_jam_selesai = '$jamSelesai',
    212279_harga = '$harga'
    WHERE 212279_id_harga = '$id'");

  return mysqli_affected_rows($conn);
}

function hapusHarga($id)
{
  global $conn;
  $id = intval($id);
  mysqli_query($conn, "DELETE FROM harga_212279 WHERE 212279_id_harga = '$id'");
  return mysqli_affected_rows($conn);
}

function ensureMembershipTable()
{
  global $conn;
  try {
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS membership_212279 (
      212279_id_membership INT(11) NOT NULL AUTO_INCREMENT,
      212279_nama VARCHAR(50) NOT NULL,
      212279_harga INT(11) NOT NULL,
      212279_durasi_jam INT(11) NOT NULL,
      212279_kapasitas INT(11) NOT NULL,
      212279_bola_gratis TINYINT(1) NOT NULL DEFAULT 1,
      212279_minuman_gratis TINYINT(1) NOT NULL DEFAULT 0,
      212279_diskon_hari_kerja TINYINT(1) NOT NULL DEFAULT 0,
      212279_featured TINYINT(1) NOT NULL DEFAULT 0,
      212279_populer TINYINT(1) NOT NULL DEFAULT 0,
      212279_urutan INT(11) NOT NULL DEFAULT 0,
      212279_aktif TINYINT(1) NOT NULL DEFAULT 1,
      PRIMARY KEY (212279_id_membership)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci");
    seedDefaultMemberships();
    return true;
  } catch (mysqli_sql_exception $e) {
    return false;
  }
}

function seedDefaultMemberships()
{
  global $conn;
  try {
    $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM membership_212279");
    if ($res && ($row = mysqli_fetch_assoc($res))) {
      if (intval($row['total']) > 0) {
        return;
      }
    }
    mysqli_query($conn, "INSERT INTO membership_212279
      (212279_nama, 212279_harga, 212279_durasi_jam, 212279_kapasitas, 212279_bola_gratis, 212279_minuman_gratis, 212279_diskon_hari_kerja, 212279_featured, 212279_populer, 212279_urutan, 212279_aktif)
      VALUES
      ('Bronze', 10000, 1, 10, 1, 0, 0, 0, 0, 1, 1),
      ('Silver', 30000, 2, 12, 1, 1, 0, 1, 0, 2, 1),
      ('Gold', 30000, 3, 15, 1, 1, 1, 0, 0, 3, 1),
      ('Diamond', 40000, 4, 20, 1, 1, 1, 0, 1, 4, 1)");
  } catch (mysqli_sql_exception $e) {
    // ignore seeding errors
  }
}

function getMembershipPackages($onlyActive = true)
{
  global $conn;
  ensureMembershipTable();
  $filter = $onlyActive ? "WHERE 212279_aktif = 1" : "";
  return query("SELECT * FROM membership_212279 $filter ORDER BY 212279_urutan, 212279_id_membership");
}

function tambahMembership($data)
{
  global $conn;
  $nama = htmlspecialchars($data['nama']);
  $harga = intval($data['harga']);
  $durasi = intval($data['durasi_jam']);
  $kapasitas = intval($data['kapasitas']);
  $bola = isset($data['bola_gratis']) ? 1 : 0;
  $minuman = isset($data['minuman_gratis']) ? 1 : 0;
  $diskon = isset($data['diskon_hari_kerja']) ? 1 : 0;
  $featured = isset($data['featured']) ? 1 : 0;
  $populer = isset($data['populer']) ? 1 : 0;
  $urutan = intval($data['urutan']);
  $aktif = isset($data['aktif']) ? 1 : 0;

  mysqli_query($conn, "INSERT INTO membership_212279
    (212279_nama, 212279_harga, 212279_durasi_jam, 212279_kapasitas, 212279_bola_gratis, 212279_minuman_gratis, 212279_diskon_hari_kerja, 212279_featured, 212279_populer, 212279_urutan, 212279_aktif)
    VALUES
    ('$nama', '$harga', '$durasi', '$kapasitas', '$bola', '$minuman', '$diskon', '$featured', '$populer', '$urutan', '$aktif')");

  return mysqli_affected_rows($conn);
}

function editMembership($data)
{
  global $conn;
  $id = intval($data['id_membership']);
  $nama = htmlspecialchars($data['nama']);
  $harga = intval($data['harga']);
  $durasi = intval($data['durasi_jam']);
  $kapasitas = intval($data['kapasitas']);
  $bola = isset($data['bola_gratis']) ? 1 : 0;
  $minuman = isset($data['minuman_gratis']) ? 1 : 0;
  $diskon = isset($data['diskon_hari_kerja']) ? 1 : 0;
  $featured = isset($data['featured']) ? 1 : 0;
  $populer = isset($data['populer']) ? 1 : 0;
  $urutan = intval($data['urutan']);
  $aktif = isset($data['aktif']) ? 1 : 0;

  mysqli_query($conn, "UPDATE membership_212279 SET
    212279_nama = '$nama',
    212279_harga = '$harga',
    212279_durasi_jam = '$durasi',
    212279_kapasitas = '$kapasitas',
    212279_bola_gratis = '$bola',
    212279_minuman_gratis = '$minuman',
    212279_diskon_hari_kerja = '$diskon',
    212279_featured = '$featured',
    212279_populer = '$populer',
    212279_urutan = '$urutan',
    212279_aktif = '$aktif'
    WHERE 212279_id_membership = '$id'");

  return mysqli_affected_rows($conn);
}

function hapusMembership($id)
{
  global $conn;
  $id = intval($id);
  mysqli_query($conn, "DELETE FROM membership_212279 WHERE 212279_id_membership = '$id'");
  return mysqli_affected_rows($conn);
}

function parseDurationHours($timeStr)
{
  if (!is_string($timeStr) || $timeStr === '') {
    return 0;
  }
  if (strpos($timeStr, ':') !== false) {
    $parts = explode(':', $timeStr);
    return max(0, intval($parts[0]));
  }
  return max(0, intval($timeStr));
}

function getRateByDatetime($datetimeStr, $fallbackRate = 1)
{
  $dt = new DateTime($datetimeStr);
  $dayOfWeek = intval($dt->format('N')); // 1=Mon ... 7=Sun
  $hour = intval($dt->format('G')); // 0-23
  $dayType = ($dayOfWeek >= 6) ? 'weekend' : 'weekday';
  $rules = getPricingRules(false);

  foreach ($rules as $rule) {
    if ($rule['212279_hari'] !== $dayType) {
      continue;
    }
    $start = intval($rule['212279_jam_mulai']);
    $end = intval($rule['212279_jam_selesai']);
    if ($hour >= $start && $hour < $end) {
      return intval($rule['212279_harga']);
    }
  }

  return intval($fallbackRate);
}

function calculateTotalByHours($datetimeStr, $durationHours, $fallbackRate = 1)
{
  $durationHours = max(0, intval($durationHours));
  $dt = new DateTime($datetimeStr);
  $total = 0;

  for ($i = 0; $i < $durationHours; $i++) {
    $total += getRateByDatetime($dt->format('Y-m-d H:i:s'), $fallbackRate);
    $dt->modify('+1 hour');
  }

  return $total;
}

function pesan($data)
{
    global $conn;

    $userid = $_SESSION["id_user"];
    $idlpg = $data["id_lpg"];
    $tanggal_pesan = date('Y-m-d H:i:s'); // Menyimpan tanggal dan waktu saat ini
    $lama = parseDurationHours($data["jam_mulai"]); // Menganggap 'jam_mulai' adalah jumlah jam sewa
    $mulai = $data["tgl_main"];
    $mulai_waktu = strtotime($mulai); // Mengubah format datetime-local menjadi format UNIX timestamp
    $habis_waktu = $mulai_waktu + ($lama * 3600); // Menambahkan waktu sewa dalam jam ke waktu mulai
    $habis = date('Y-m-d H:i:s', $habis_waktu); // Format datetime untuk MySQL
    $hargaPerJam = 0;
    $lapanganHarga = query("SELECT 212279_harga FROM lapangan_212279 WHERE 212279_id_lapangan = '$idlpg' LIMIT 1");
    if (!empty($lapanganHarga)) {
        $hargaPerJam = intval($lapanganHarga[0]['212279_harga']);
    }
    $fallbackRate = $hargaPerJam > 0 ? $hargaPerJam : 1;
    $useDynamicPricing = hasPricingRules();

    if ($lama <= 0) {
        return false;
    }

    // Cek bentrokan jadwal
    $query = "SELECT * FROM sewa_212279 WHERE 212279_id_lapangan = '$idlpg' 
              AND ((212279_jam_mulai < '$habis' AND 212279_jam_habis > '$mulai') 
              OR (212279_jam_mulai < '$mulai' AND 212279_jam_habis > '$habis'))";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Jika ada bentrokan jadwal
        return false;
    } else {
        // Tidak ada bentrokan, lanjutkan dengan penyimpanan data
        if ($useDynamicPricing) {
            $hargaPerJam = getRateByDatetime($mulai, $fallbackRate);
            $total = calculateTotalByHours($mulai, $lama, $fallbackRate);
        } else {
            if ($hargaPerJam > 0) {
                $total = $hargaPerJam * $lama;
            } else {
                $hargaPerJam = getRateByDatetime($mulai, $fallbackRate);
                $total = calculateTotalByHours($mulai, $lama, $fallbackRate);
            }
        }
        mysqli_query($conn, "INSERT INTO sewa_212279 (212279_id_user, 212279_id_lapangan, 212279_tanggal_pesan, 212279_lama_sewa, 212279_jam_mulai, 212279_jam_habis, 212279_harga, 212279_total) 
                             VALUES ('$userid', '$idlpg', '$tanggal_pesan', '$lama', '$mulai', '$habis', '$hargaPerJam', '$total')");

        return mysqli_affected_rows($conn);
    }
}


function bayar($data)
{

  global $conn;
  $id_sewa = $data["id_sewa"];
  $tanggal_upload = date('Y-m-d H:i:s'); // Menyimpan tanggal dan waktu saat ini


  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }

  mysqli_query($conn, "INSERT INTO bayar_212279 (212279_id_sewa, 212279_bukti, 212279_tanggal_upload, 212279_konfirmasi) VALUES ('$id_sewa', '$upload', '$tanggal_upload', 'Sudah Bayar')");

  return mysqli_affected_rows($conn);
}

function tambahLpg($data)
{
  global $conn;

  $lapangan_212279 = $data["lapangan"];
  $harga = $data["harga"];
  $ket = $data["ket"];

  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }


  $query = "INSERT INTO lapangan_212279 (212279_nama,212279_harga,212279_foto,212279_keterangan) VALUES ('$lapangan_212279','$harga','$upload','$ket')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function upload()
{
  $namaFile = $_FILES['foto']['name'];
  $ukuranFile = $_FILES['foto']['size'];
  $error = $_FILES['foto']['error'];
  $tmpName = $_FILES['foto']['tmp_name'];

  // Cek apakah tidak ada gambar yang di upload
  if ($error === 4) {
    echo "<script>
    alert('Pilih gambar terlebih dahulu');
    </script>";
    return false;
  }

  // Cek apakah gambar
  $extensiValid = ['jpg', 'png', 'jpeg'];
  $extensiGambar = explode('.', $namaFile);
  $extensiGambar = strtolower(end($extensiGambar));

  if (!in_array($extensiGambar, $extensiValid)) {
    echo "<script>
    alert('Yang anda upload bukan gambar!');
    </script>";
    return false;
  }

  if ($ukuranFile > 1000000) {
    echo "<script>
    alert('Ukuran Gambar Terlalu Besar!');
    </script>";
    return false;
  }

  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $extensiGambar;
  // Move File
  move_uploaded_file($tmpName, '../img/' . $namaFileBaru);
  return $namaFileBaru;
}

function editLpg($data)
{
  global $conn;

  $id = $data["idlap"];
  $lapangan_212279 = $data["lapangan"];
  $ket = $data["ket"];
  $harga = $data["harga"];
  $gambarLama =  $data["fotoLama"];

  // Cek apakah User pilih gambar baru
  if ($_FILES["foto"]["error"] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }


  $query = "UPDATE lapangan_212279 SET 
  212279_nama = '$lapangan_212279',
  212279_keterangan = '$ket',
  212279_harga = '$harga',
  212279_foto = '$gambar' WHERE 212279_id_lapangan = '$id'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}


function tambahAdmin($data)
{
  global $conn;

  $username = $data["username"];
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone = $data["hp"];
  $email = $data["email"];

  $query = "INSERT INTO admin_212279 (212279_username,212279_password,212279_nama,212279_no_handphone,212279_email) VALUES ('$username','$password','$nama','$no_handphone','$email')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function editAdmin($data)
{
  global $conn;

  $id = $data["id"];
  $username = $data["username"];
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone = $data["hp"];
  $email = $data["email"];

  $query = "UPDATE admin_212279 SET 
  212279_username = '$username',
  212279_password = '$password',
  212279_nama = '$nama',
  212279_no_handphone = '$no_handphone',
  212279_email  = '$email' WHERE 212279_id_user = '$id'
  
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function konfirmasi($id_sewa)
{
  global $conn;

  $id = $id_sewa;

  mysqli_query($conn, "UPDATE bayar_212279 set 212279_konfirmasi = ('Terkonfirmasi') WHERE 212279_id_sewa = '$id'");
  return mysqli_affected_rows($conn);
}
