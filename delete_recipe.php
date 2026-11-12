<?php

declare(strict_types=1);

session_start();

// only logged-in admins can delete recipes.
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require __DIR__ . '/db.php';

$recipe_id = (int) ($_GET['id'] ?? 0);

if ($recipe_id < 1) {
  exit('Invalid recipe.');
}



// AI-assisted code: safely find the recipe before 
// deleting the recipe. Present the recipe card that 
// was slicked upon on the center of the page.
// this part gives all the information needed to popualte the card
$statement = mysqli_prepare(
  $conn,
        'SELECT
        recipes.id,
        recipes.title,
        recipes.subtitle,
        recipes.main_image,
        recipes.servings,
        categories.name AS category
        FROM recipes
        INNER JOIN categories
        ON recipes.category_id = categories.id
        WHERE recipes.id = ?'
);

mysqli_stmt_bind_param(
  $statement,
  'i',
  $recipe_id
);


mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
$recipe = mysqli_fetch_assoc($result);




if (!$recipe) {
  exit('Recipe not found.');
}


// AI-assisted code: delete one recipe using a prepared statement.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// only delete after the confirmation form is submitted

  $delete_statement = mysqli_prepare(
    $conn,
    'DELETE FROM recipes
     WHERE id = ?'
// delete only the selcted recipe, not all of them
  );

  mysqli_stmt_bind_param(
    $delete_statement,
    'i',
    $recipe_id
  );

  mysqli_stmt_execute($delete_statement);

  header('Location: admin.php');
  exit;
}

?>





<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Recipe | UXID 241 Cookbook</title>
  <link rel="stylesheet" href="style.css?=v2">
</head>


<body>
  <main class="login-page">
    <h1>Delete Recipe?</h1>
    <h2>&diams; Are you sure you want to delete this recipe? &diams;</h2>

<!--display recipe card-->
    <div class="delete-recipe-preview">
        <article class="recipe-card">


        <?php if (!empty($recipe['main_image'])) : ?>
        <img
        src="<?= htmlspecialchars($recipe['main_image'], ENT_QUOTES, 'UTF-8'); ?>"
        alt="<?= htmlspecialchars($recipe['title'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>

        
        <h3><?= htmlspecialchars($recipe['title'], ENT_QUOTES, 'UTF-8'); ?></h3>


        <?php if (!empty($recipe['subtitle'])) : ?>
        <p><?= htmlspecialchars($recipe['subtitle'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>


        <div class="recipe-info">

      
        <span class="recipe-category">
        <?= htmlspecialchars($recipe['category'], ENT_QUOTES, 'UTF-8'); ?>
        </span>


        <span class="recipe-servings">
        <?= htmlspecialchars((string) $recipe['servings'], ENT_QUOTES, 'UTF-8'); ?>
        servings
        </span>
    </div>

  </article>

</div>



<!-- Delete recipe button -->
<form action="delete_recipe.php?id=<?= (int) $recipe['id']; ?>" method="POST">
<button type="submit" class="dark-button">Yes, Delete Recipe</button>
</form>
<br><br>
<!-- Cancel recipe button -->
<a href="admin.php" class="round-button">Cancel</a>

</main>
</body>
</html>