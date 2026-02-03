<?php
session_start();

// Optional: only allow logged-in users to see this debug info.
if (!isset($_SESSION['id_user'])) {
  http_response_code(403);
  echo "Forbidden";
  exit;
}

$config = require "../config.php";
$midtrans = $config['midtrans'] ?? [];

function mask_key($key) {
  if (!is_string($key) || $key === '') {
    return '(empty)';
  }
  $prefix = substr($key, 0, 10);
  $suffix = substr($key, -4);
  return $prefix . '...' . $suffix;
}

header('Content-Type: text/plain; charset=utf-8');
echo "server_key: " . mask_key($midtrans['server_key'] ?? '') . PHP_EOL;
echo "client_key: " . mask_key($midtrans['client_key'] ?? '') . PHP_EOL;
echo "is_sandbox: " . (isset($midtrans['is_sandbox']) ? var_export($midtrans['is_sandbox'], true) : '(unset)') . PHP_EOL;
echo "snap_url: " . ($midtrans['snap_url'] ?? '(unset)') . PHP_EOL;
