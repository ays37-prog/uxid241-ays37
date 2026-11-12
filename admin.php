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
  <link rel="stylesheet" href="style.css?v=6">
</head>

<body>

  <main>
        <a href="logout.php" class="round-button">Log Out Now</a>


    <h1>Admin Panel</h1>
    

    <h2>Welcome back</h2>

  
<div class="centered-container">
    <br>
      <a href="create_recipe.php" class="round-button">Add New Recipe</a>
    <br>
    <br>
    <a href="index.php" class="round-button">Continue to Cookbook</a>
</div>

  </main>

</body>
</html>