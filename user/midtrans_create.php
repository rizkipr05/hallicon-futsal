<?php
session_start();
require "../functions.php";

$config = require "../config.php";
$midtrans = $config['midtrans'];
global $conn;

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
  http_response_code(403);
  echo json_encode(['error' => 'Anda harus login terlebih dahulu.']);
  exit;
}

$id_user = $_SESSION['id_user'];
$id_sewa = isset($_POST['id_sewa']) ? intval($_POST['id_sewa']) : 0;

if ($id_sewa <= 0) {
  http_response_code(400);
  echo json_encode(['error' => 'ID sewa tidak valid.']);
  exit;
}

$sewaQuery = "SELECT sewa_212279.*, lapangan_212279.212279_nama, user_212279.212279_nama_lengkap, user_212279.212279_email, user_212279.212279_no_handphone
  FROM sewa_212279
  JOIN lapangan_212279 ON sewa_212279.212279_id_lapangan = lapangan_212279.212279_id_lapangan
  JOIN user_212279 ON sewa_212279.212279_id_user = user_212279.212279_id_user
  WHERE sewa_212279.212279_id_sewa = '$id_sewa' AND sewa_212279.212279_id_user = '$id_user'
  LIMIT 1";
$sewaData = query($sewaQuery);

if (!$sewaData) {
  http_response_code(404);
  echo json_encode(['error' => 'Data sewa tidak ditemukan.']);
  exit;
}

$sewa = $sewaData[0];
$grossAmount = intval($sewa['212279_total']);
if ($grossAmount <= 0) {
  http_response_code(400);
  echo json_encode(['error' => 'Total pembayaran tidak valid.']);
  exit;
}

$bayar = query("SELECT * FROM bayar_212279 WHERE 212279_id_sewa = '$id_sewa' LIMIT 1");
if ($bayar && $bayar[0]['212279_konfirmasi'] === 'Terkonfirmasi') {
  http_response_code(400);
  echo json_encode(['error' => 'Pesanan sudah dibayar.']);
  exit;
}

$orderId = 'SEWA-' . $id_sewa . '-' . time();

$payload = [
  'transaction_details' => [
    'order_id' => $orderId,
    'gross_amount' => $grossAmount,
  ],
  'item_details' => [
    [
      'id' => (string) $id_sewa,
      'price' => $grossAmount,
      'quantity' => 1,
      'name' => 'Sewa Lapangan ' . $sewa['212279_nama'],
    ],
  ],
  'customer_details' => [
    'first_name' => $sewa['212279_nama_lengkap'],
    'email' => $sewa['212279_email'],
    'phone' => $sewa['212279_no_handphone'],
  ],
];

$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => $midtrans['snap_url'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode($midtrans['server_key'] . ':'),
  ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode >= 400) {
  http_response_code(500);
  echo json_encode(['error' => 'Gagal membuat transaksi Midtrans.', 'detail' => $curlError ?: $response]);
  exit;
}

$result = json_decode($response, true);
if (!isset($result['token'])) {
  http_response_code(500);
  echo json_encode(['error' => 'Respons Midtrans tidak valid.']);
  exit;
}

$today = date('Y-m-d');
if ($bayar) {
  mysqli_query($conn, "UPDATE bayar_212279 SET 212279_bukti = '$orderId', 212279_tanggal_upload = '$today', 212279_konfirmasi = 'Pending' WHERE 212279_id_sewa = '$id_sewa'");
} else {
  mysqli_query($conn, "INSERT INTO bayar_212279 (212279_id_sewa, 212279_bukti, 212279_tanggal_upload, 212279_konfirmasi) VALUES ('$id_sewa', '$orderId', '$today', 'Pending')");
}

echo json_encode([
  'token' => $result['token'],
  'redirect_url' => $result['redirect_url'] ?? null,
]);
