<?php
declare(strict_types=1);
session_start();


if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require __DIR__ . '/db.php';
// kick out non users from this page



// AI assisted code: 
// Prompt: help me with creating a safe and unique filename for the user's uploaded image.
function make_safe_name(
  string $original_name,
  string $extension,
  string $folder
): string {

// needs 3 pieces of information
// original name like My Delicious Pasta!!.JPG
// extension type determined by the file's real MIME type such as jpg
// and folder we're saving to "uploads"
// the function eventually returns a string


  $base = pathinfo($original_name, PATHINFO_FILENAME);
  // removes the original extension


  $base = strtolower($base);
  // all lowercase


  $base = preg_replace('/[^a-z0-9]+/', '-', $base);
  // anything that isn't a-z or 0-9 is replaced by -
  // so: my-delicious-pasta-


  $base = trim($base, '-');
  // removes extra - from the beginning and end, not between words


  $base = substr($base, 0, 60);
  // file name maximum character count is 60


  if ($base === '') {
    $base = 'upload';
  }
  // if !!!.jpg becomes empty, give it the name upload.jpg


  $safe_name = $base . '.' . $extension;
  // combines safe trimmed base with verified MIME extension


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



  // validate regular recipe information
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



  // check for red flags in recipe image
  $image = $_FILES['recipe_image'] ?? null;

  // did the image upload successfully
  if (
    !$image ||
    $image['error'] !== UPLOAD_ERR_OK
  ) {
    $errors[] = 'Please upload a recipe image.';
  }


  // MB size check. is it too large
  if (
    $image &&
    $image['error'] === UPLOAD_ERR_OK &&
    $image['size'] > 2 * 1024 * 1024
  ) {
    $errors[] = 'The recipe image must be 2 MB or smaller.';
  }



  // check actual file type jpg png webp
  if (
    $image &&
    $image['error'] === UPLOAD_ERR_OK &&
    $image['size'] <= 2 * 1024 * 1024
  ) {

    // examines the actual uploaded file instead of what it looks like upfront
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    // inspect temporary file and tell what type of file it actually is
    $mime = $finfo->file($image['tmp_name']);


    // allowlist of file types that are actually allowed
    // also connects each real MIME type to a safe extension
    $allowed_types = [
      'image/jpeg' => 'jpg',
      'image/png' => 'png',
      'image/webp' => 'webp'
    ];


    if (!isset($allowed_types[$mime])) {
      $errors[] = 'Only JPG, PNG, and WebP images are allowed.';
    }
  }



  // only continue if the form and image passed all validation
  if (empty($errors)) {


    // save the validated image and store location to uploads folder
    $upload_folder = __DIR__ . '/uploads';


    $extension = $allowed_types[$mime];
    // gets trusted extension from real MIME type that was checked before


    $safe_name = make_safe_name(
      $image['name'],
      $extension,
      $upload_folder
    );


    $target = $upload_folder . '/' . $safe_name;
    // saves the complete final location:
    // uploads/chicken-tacos.jpg



    // MOVE THE FILE FROM TEMPORARY PHP LOCATION INTO REAL UPLOADS FOLDER
    if (!move_uploaded_file($image['tmp_name'], $target)) {

      $errors[] = 'Could not save the recipe image.';
    }



    // only put into database if image saved successfully
    if (empty($errors)) {

      $image_path = 'uploads/' . $safe_name;
      // this is the image path that will be saved in MySQL
      $statement = mysqli_prepare(
        $conn,
        'INSERT INTO recipes
          (title, description, servings, category_id, main_image)
         VALUES (?, ?, ?, ?, ?)'
      );


      mysqli_stmt_bind_param(
        $statement,
        'ssiis',
        $title,
        $description,
        $servings,
        $category_id,
        $image_path
      );


      mysqli_stmt_execute($statement);

      // after successfully adding recipe, return to admin page
      header('Location: admin.php');
      exit;
    }
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








