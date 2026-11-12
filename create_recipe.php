<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require __DIR__ . '/db.php';
?>
<!--kick out non admin from this page-->


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








