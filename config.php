<?php

$localConfig = __DIR__ . '/config.local.php';
if (is_readable($localConfig)) {
  return require $localConfig;
}

return [
  'midtrans' => [
    'server_key' => getenv('MIDTRANS_SERVER_KEY') ?: '',
    'client_key' => getenv('MIDTRANS_CLIENT_KEY') ?: '',
    'is_sandbox' => true,
    'snap_url' => 'https://app.sandbox.midtrans.com/snap/v1/transactions',
    'api_base' => 'https://api.sandbox.midtrans.com/v2',
  ],
];
