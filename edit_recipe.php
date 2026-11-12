<?php
declare(strict_types=1);
session_start();


// only logged-in admins can access this page
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require __DIR__ . '/db.php';



// gets the recipe ID from a URL by clicking on 
// the edit recipe storing id=31
// **uses prepared statement
$recipe_id = (int) ($_GET['id'] ?? 0);



// AI-assisted code: safely retrieve one recipe by its ID.
// with precautions for an invalid or missing ID.
if ($recipe_id < 1) {
  exit('Invalid recipe.');
}



// retrieve one recipe by its ID
$statement = mysqli_prepare(
  $conn,
  'SELECT id, title, description, servings, category_id
   FROM recipes
   WHERE id = ?'
);



mysqli_stmt_bind_param(
  $statement,
  'i',
  $recipe_id
);



mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
$recipe = mysqli_fetch_assoc($result);



// stop this that recipe does not exist.
if (!$recipe) {
  exit('Recipe not found.');
}



// AI-assisted: validate and update an existing recipe
// using a prepared statement.
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

  // update only if everything else is valid
  if (empty($errors)) {

    $update_statement = mysqli_prepare(
      $conn,
      'UPDATE recipes
       SET title = ?,
           description = ?,
           servings = ?,
           category_id = ?
       WHERE id = ?'
    );

    mysqli_stmt_bind_param(
      $update_statement,
      'ssiii',
      $title,
      $description,
      $servings,
      $category_id,
      $recipe_id
    );

    mysqli_stmt_execute($update_statement);

    header('Location: admin.php');
    exit;
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Recipe | UXID 241 Cookbook</title>
  <link rel="stylesheet" href="style.css">
</head>


<body>
<a href="admin.php" class="round-button">← Back to Admin</a>



    <main class="login-page">
    <h1>Edit Recipe</h1>



    <form action="edit_recipe.php?id=<?= (int) $recipe['id']; ?>" method="POST">


      <label for="title"><h2>Recipe Title</h2></label>
      <input
        class="form-input"
        type="text"
        id="title"
        name="title"
        value="<?= htmlspecialchars($recipe['title'], ENT_QUOTES, 'UTF-8'); ?>"
        required>



      <label for="description"><h2>Description</h2></label>
      <textarea
        class="form-input"
        id="description"
        name="description"
        required><?= htmlspecialchars($recipe['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>



      <label for="servings"><h2>Servings</h2></label>
      <input
        class="form-input"
        type="number"
        id="servings"
        name="servings"
        min="1"
        value="<?= (int) $recipe['servings']; ?>"
        required>



      <label for="category_id"><h2>Category</h2></label>
      <select
        class="form-input"
        id="category_id"
        name="category_id"
        required>



        <option value="1" <?= (int) $recipe['category_id'] === 1 ? 'selected' : ''; ?>>Chicken</option>
        <option value="2" <?= (int) $recipe['category_id'] === 2 ? 'selected' : ''; ?>>Beef</option>
        <option value="3" <?= (int) $recipe['category_id'] === 3 ? 'selected' : ''; ?>>Vegetarian</option>
        <option value="4" <?= (int) $recipe['category_id'] === 4 ? 'selected' : ''; ?>>Fish & Seafood</option>
        <option value="5" <?= (int) $recipe['category_id'] === 5 ? 'selected' : ''; ?>>Pork</option>
        <option value="6" <?= (int) $recipe['category_id'] === 6 ? 'selected' : ''; ?>>Turkey</option>
      </select>


      <br>
      <button type="submit" class="dark-button">Save Changes</button>
    </form>

  </main>

</body>
</html>