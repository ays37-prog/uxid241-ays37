<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/db.php';
// LOADS EXISTING db.php 




$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';



  // PREPARED STATEMENT FINDS USER BY EMAIL
  $statement = mysqli_prepare(
    $conn,
    'SELECT id, email, password_hash
     FROM users
     WHERE email = ?'
  );



  mysqli_stmt_bind_param(
    $statement,
    's',
    $email
  );



  mysqli_stmt_execute($statement);

  $result = mysqli_stmt_get_result($statement);
  $user = mysqli_fetch_assoc($result);



  // VERIFY ENTERED PASSWORD AGAINST STORED HASH
  if (
    $user &&
    password_verify($password, $user['password_hash'])
  ) {



    // CREATE A NEW SESSION ID AT LOGIN
    session_regenerate_id(true);



    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_email'] = $user['email'];



    header('Location: admin.php');
    exit;
  }


  
  $login_error = 'Invalid email or password.';
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In | UXID 241 Cookbook</title>
  <link rel="stylesheet" href="style.css?v=5">
</head>


<body>
  <main class="login-page">
    <a href="index.php">← Back to Recipes</a>


    <h1>Log In</h1>


    <form action="login.php" method="POST">
    <!-- sends the information back to login.php-->
    <!-- send the submitted information inside the http request rather than putting it in the URL -->





      <label for="email">Email</label>
      <input
        type="email"
        id="email"
        name="email"
        required>
    <!-- do not submit the form is the field is empty -->




      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        required>

      <button type="submit">Log In</button>
      <!-- sends email and pw to login.php with POST -->


    </form>

  </main>

</body>
</html>