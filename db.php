<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//report errors don't let them just go

$config = require __DIR__ . '/config.php';
// loads private config.php
// returned settings store in $config

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
// store  in conn if it links right 

  } catch (mysqli_sql_exception $e) {
  exit('Database connection failed.');
}
// catch errors and report
