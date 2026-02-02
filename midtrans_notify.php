<?php
require "functions.php";

$config = require "config.php";
$midtrans = $config['midtrans'];
global $conn;

$rawBody = file_get_contents('php://input');
$notification = json_decode($rawBody, true);

if (!$notification) {
  http_response_code(400);
  echo 'Invalid payload';
  exit;
}

$orderId = $notification['order_id'] ?? '';
$statusCode = $notification['status_code'] ?? '';
$grossAmount = $notification['gross_amount'] ?? '';
$signatureKey = $notification['signature_key'] ?? '';

$expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $midtrans['server_key']);
if ($signatureKey !== $expectedSignature) {
  http_response_code(403);
  echo 'Invalid signature';
  exit;
}

$transactionStatus = $notification['transaction_status'] ?? '';
$fraudStatus = $notification['fraud_status'] ?? '';

$status = 'Pending';
if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
  $status = ($fraudStatus === 'accept' || $fraudStatus === '') ? 'Terkonfirmasi' : 'Pending';
} elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'], true)) {
  $status = 'Gagal';
}

$today = date('Y-m-d');
mysqli_query($conn, "UPDATE bayar_212279 SET 212279_konfirmasi = '$status', 212279_tanggal_upload = '$today' WHERE 212279_bukti = '$orderId'");

echo 'OK';
