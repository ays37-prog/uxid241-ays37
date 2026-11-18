<?php
declare(strict_types=1);

require __DIR__ . '/db.php';


// AI ASSISTED CODE:
// Prompt: Help me create a secure account registration page
// using password_hash and a prepared mysqli statement.

$registration_error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // etting information from the form
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';


  // validating the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $registration_error = 'Please enter a valid email address.';


  // validating the pssword hash
  } elseif (strlen($password) < 8) {

    $registration_error = 'Password must be at least 8 characters.';


  // make sure both passwords match
  } elseif ($password !== $confirm_password) {

    $registration_error = 'Passwords do not match.';


  } else {

    // check whether the email already has an account
    $check_statement = mysqli_prepare(
      $conn,
      'SELECT id
       FROM users
       WHERE email = ?'
    );


    mysqli_stmt_bind_param(
      $check_statement,
      's',
      $email
    );


    mysqli_stmt_execute($check_statement);

    $result = mysqli_stmt_get_result($check_statement);
    $existing_user = mysqli_fetch_assoc($result);


    if ($existing_user) {

      $registration_error = 'An account with this email already exists.';

    } else {

      // turn password into a secure hash
      $password_hash = password_hash(
        $password,
        PASSWORD_DEFAULT
      );


      // save the email and hash to the database
      $insert_statement = mysqli_prepare(
        $conn,
        'INSERT INTO users
          (email, password_hash)
         VALUES (?, ?)'
      );


      mysqli_stmt_bind_param(
        $insert_statement,
        'ss',
        $email,
        $password_hash
      );


      mysqli_stmt_execute($insert_statement);


      // send user to login page
      header('Location: login.php');
      exit;
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Create Account | UXID 241 Cookbook</title>

  <link rel="stylesheet" href="style.css?v=9">
</head>


<body>

  <a href="login.php" class="round-button">
    ← Back to Log In
  </a>


  <main class="login-page">

    <h1>Create an Account</h1>


    <?php if ($registration_error !== '') : ?>

      <p class="login-error">
        <?= htmlspecialchars(
          $registration_error,
          ENT_QUOTES,
          'UTF-8'
        ); ?>
      </p>

    <?php endif; ?>


    <form action="register.php" method="POST">


      <label for="email">
        <h2>Email</h2>
      </label>

      <input
        class="form-input"
        type="email"
        id="email"
        name="email"
        required>


      <label for="password">
        <h2>Password</h2>
      </label>

      <input
        class="form-input"
        type="password"
        id="password"
        name="password"
        minlength="8"
        required>


      <label for="confirm_password">
        <h2>Confirm Password</h2>
      </label>

      <input
        class="form-input"
        type="password"
        id="confirm_password"
        name="confirm_password"
        minlength="8"
        required>


      <br>

      <button type="submit" class="creme-button">
        Create Account
      </button>

    </form>

  </main>

</body>
</html>