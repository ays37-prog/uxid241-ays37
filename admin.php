<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel | UXID 241 Cookbook</title>
  <link rel="stylesheet" href="style.css?v=5">
</head>

<body>

  <main>

    <h1>Admin Panel</h1>

    <p>
      Welcome back
      <?= htmlspecialchars(
        $_SESSION['user_email'],
        ENT_QUOTES,
        'UTF-8'
      ); ?>
    </p>

  </main>

</body>
</html>