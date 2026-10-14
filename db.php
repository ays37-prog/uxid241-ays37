<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$config = require __DIR__ . '/config.php';

try {
  $conn = mysqli_connect(
    $config['db_host'],
    $config['db_user'],
    $config['db_password'],
    $config['db_name'],
    $config['db_port'],
    $config['db_socket']
  );

  mysqli_set_charset($conn, 'utf8mb4');
  } catch (mysqli_sql_exception $e) {
  exit('Database connection failed.');
}