<?php
declare(strict_types=1);
session_start();


if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require __DIR__ . '/db.php';
// kick out non userws from this page



// AI assisted code: 
// Prompt: help me with creating a safe and unique filename for the user's uploaded image.
function make_safe_name(
  string $original_name,
  string $extension,
  string $folder
): string {
// needs 3 pieaces of information
// origional name like My Delicious Pasta!!.JPG
// extention type determined by the file's real MIME type such as jpg
// and folder we're saving to "uploads"
// the funciton eventually returns a string



  $base = pathinfo($original_name, PATHINFO_FILENAME);
//removes the oigional extension

  $base = strtolower($base);
// all lowercase

  $base = preg_replace('/[^a-z0-9]+/', '-', $base);
// anything that isnt a-z or 0-9 is replaces by - so: my-delicious-pasta-
  
$base = trim($base, '-');
// removes extra - from the beggining and end, not betwen words. still need separation.
  
$base = substr($base, 0, 60);
//file name maxiumum character count is 60. will NOT take long file names

  if ($base === '') {
    $base = 'upload';
  }
// if !!!.jpg --> .jpg just give it A name upload.jpg so it's not empty

  $safe_name = $base . '.' . $extension;
// combines safe trimmed base with MIME extension

  $counter = 1;

// is there already a file with this name inside my uploads folder?
  while (file_exists($folder . '/' . $safe_name)) {
    $safe_name = $base . '-' . $counter . '.' . $extension;
    $counter++;
  }

  return $safe_name;
}



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



// check red flags in the uploaded recipe image
$image = $_FILES['recipe_image'] ?? null;


// check did image uploaded successfully
if (
  !$image ||
  $image['error'] !== UPLOAD_ERR_OK
) {
  $errors[] = 'Please upload a recipe image.';
}


// size. check is image too large
if (
  $image &&
  $image['error'] === UPLOAD_ERR_OK &&
  $image['size'] > 2 * 1024 * 1024
) {
  $errors[] = 'The recipe image must be 2 MB or smaller.';
}




// check file's actual type png jpeg webp
if (
  $image &&
  $image['error'] === UPLOAD_ERR_OK &&
  $image['size'] <= 2 * 1024 * 1024
) {

 // examines the actual uploaded file instead of what it looks like upfront
  $finfo = new finfo(FILEINFO_MIME_TYPE);
// inspect temporary file and tell what type of file its actually is
  $mime = $finfo->file($image['tmp_name']);

  $allowed_types = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp'
  ];

  if (!isset($allowed_types[$mime])) {
    $errors[] = 'Only JPG, PNG, and WebP images are allowed.';
  }
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
  <link rel="stylesheet" href="style.css?v=7">
</head>


<body>

  <a href="admin.php" class="round-button">← Back to Admin</a>


  <main class="login-page">
    <h1>Add a Recipe</h1>


<form
    action="create_recipe.php"
    method="POST"
    enctype="multipart/form-data">
    

    <label for="title"><h2>Recipe Title</h2></label>
    <input
        class="form-input"
        type="text"
        id="title"
        name="title"
        required>


    <label for="recipe_image"><h2>Recipe Image</h2></label>
    <input
        class="form-input"
        type="file"
        id="recipe_image"
        name="recipe_image"
        accept=".jpg,.jpeg,.png,.webp"
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








