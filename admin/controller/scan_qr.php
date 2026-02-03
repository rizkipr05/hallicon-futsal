<?php
session_start();
require "../../functions.php";

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
  http_response_code(403);
  echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
  exit;
}

$payload = isset($_POST['payload']) ? trim($_POST['payload']) : '';
if ($payload === '') {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'QR kosong.']);
  exit;
}

$id_sewa = null;
if (preg_match('/^SEWA[:\-]?(\d+)$/i', $payload, $matches)) {
  $id_sewa = $matches[1];
} elseif (ctype_digit($payload)) {
  $id_sewa = $payload;
}

if (!$id_sewa) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Format QR tidak dikenali.']);
  exit;
}

$id_sewa = intval($id_sewa);

$rows = query("SELECT sewa_212279.212279_id_sewa,
  sewa_212279.212279_tanggal_pesan,
  sewa_212279.212279_jam_mulai,
  sewa_212279.212279_jam_habis,
  user_212279.212279_nama_lengkap,
  lapangan_212279.212279_nama,
  IFNULL(bayar_212279.212279_konfirmasi,'Belum') AS 212279_konfirmasi
  FROM sewa_212279
  JOIN user_212279 ON sewa_212279.212279_id_user = user_212279.212279_id_user
  JOIN lapangan_212279 ON sewa_212279.212279_id_lapangan = lapangan_212279.212279_id_lapangan
  LEFT JOIN bayar_212279 ON sewa_212279.212279_id_sewa = bayar_212279.212279_id_sewa
  WHERE sewa_212279.212279_id_sewa = '$id_sewa'");

if (!$rows) {
  http_response_code(404);
  echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan.']);
  exit;
}

$row = $rows[0];
$status = $row['212279_konfirmasi'] ?? 'Belum';

if ($status !== 'Terkonfirmasi') {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => 'Pesanan belum terkonfirmasi. Status: ' . $status
  ]);
  exit;
}

echo json_encode([
  'success' => true,
  'data' => [
    'nama' => $row['212279_nama_lengkap'],
    'lapangan' => $row['212279_nama'],
    'tanggal_pesan' => $row['212279_tanggal_pesan'],
    'jam_mulai' => $row['212279_jam_mulai'],
    'jam_habis' => $row['212279_jam_habis'],
    'status' => $status,
    'id_sewa' => $row['212279_id_sewa']
  ]
]);
