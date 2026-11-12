<?php
declare(strict_types=1);
session_start();


if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require __DIR__ . '/db.php';
// kick out non userws from this page



// AI-assisted code:
// PROMPT: using a prepared statement, set up this form so 
// that it correctly validates the user input and adds a 
// new recipe properly from the given form fields. 
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $servings = (int) ($_POST['servings'] ?? 0);
  $category_id = (int) ($_POST['category_id'] ?? 0);

  
  if ($title === '') {
    $errors[] = 'Recipe title is required.';
  }
  if ($description === '') {
    $errors[] = 'Description is required.';
  }
  if ($servings < 1) {
    $errors[] = 'Servings must be at least 1.';
  }
  if ($category_id < 1) {
    $errors[] = 'Please choose a category.';
  }


  // only insert if valid
  if (empty($errors)) {

    $statement = mysqli_prepare(
      $conn,
      'INSERT INTO recipes
        (title, description, servings, category_id)
       VALUES (?, ?, ?, ?)'
    );

    mysqli_stmt_bind_param(
      $statement,
      'ssii',
      $title,
      $description,
      $servings,
      $category_id
    );

    mysqli_stmt_execute($statement);

    header('Location: index.php');
    exit;
  }
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add A Recipe | UXID 241 Cookbook</title>
  <link rel="stylesheet" href="style.css?v=6">
</head>


<body>

  <a href="admin.php" class="round-button">← Back to Admin</a>


  <main class="login-page">
    <h1>Add a Recipe</h1>


    <form action="create_recipe.php" method="POST">
      <label for="title"><h2>Recipe Title</h2></label>
      <input
        class="form-input"
        type="text"
        id="title"
        name="title"
        required>


      <label for="description"><h2>Description</h2></label>
      <textarea
        class="form-input"
        id="description"
        name="description"
        required></textarea>


      <label for="servings"><h2>Servings</h2></label>
      <input
        class="form-input"
        type="number"
        id="servings"
        name="servings"
        min="1"
        required>


      <label for="category_id"><h2>Category</h2></label>
      <select
        class="form-input"
        id="category_id"
        name="category_id"
        required>


        <option value="">Choose a category</option>
        <option value="1">Chicken</option>
        <option value="2">Beef</option>
        <option value="3">Vegetarian</option>
        <option value="4">Fish & Seafood</option>
        <option value="5">Pork</option>
        <option value="6">Turkey</option>


      </select>


      <br>
      <button type="submit" class="dark-button">
        Add Recipe
      </button>

    </form>

  </main>

</body>
</html>








