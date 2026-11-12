<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}



// AI-assisted code week 8
// I want to populate admin.php with the same display from index.php
// so that admin users aren't permanantly tinkering with the index.php 
// page even for logged out users. I want separation. What's the 
// best and simplest way to go about bringing the same information from 
// index.php into admin.php?


require __DIR__ . '/db.php';

$sql = 'SELECT
          recipes.id,
          recipes.title,
          recipes.subtitle,
          recipes.main_image,
          recipes.servings,
          categories.name AS category
        FROM recipes
        INNER JOIN categories
          ON recipes.category_id = categories.id
        ORDER BY recipes.title';

$result = mysqli_query($conn, $sql);

$database_recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);
// connects this page to load the php admin database intel, ready for pulling
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel | UXID 241 Cookbook</title>
  <link rel="stylesheet" href="style.css?v=8">
</head>





<body>
  <main>
      <a href="logout.php" class="round-button">Log Out Now</a>
    <h1>Admin Panel</h1>
    <h2>Welcome back!</h2>
    <h2>Manage Recipes Here:</h2>

    <div class="centered-container">
         <a href="create_recipe.php" class="round-button">+ Add New Recipe</a>
         <br><br>
    </div>




      
    <!--Recipe management list to mimick index.php without damaging real index.php-->
    <!--AI assisted code: see prompt above-->  
<div class="recipe-grid">
    <?php foreach ($database_recipes as $recipe) : ?>
    <article class="recipe-card">

      <!--AI assisted code: 
      is there a way to change the buttons actually to each be round? 
      then inside, for the delete button is just a white trash can icon. 
      And for the edit button it is just a white pencil. 
      the trash can button will be on the top left of the card, 
      overlapping some of the image, and the edit button will be on 
      the top right of each card, also masking the image butonthe opposite 
      side? do you understand? is this possible?-->
    <div class="admin-card-buttons">
      <a
      href="delete_recipe.php?id=<?= (int) $recipe['id']; ?>"
      class="admin-icon-button delete-button"
      aria-label="Delete recipe">
      &times;
      </a>

      <a
      href="edit_recipe.php?id=<?= (int) $recipe['id']; ?>"
      class="admin-icon-button edit-button"
      aria-label="Edit recipe">
      ✎
      </a>
    </div>


      <!--recipe image-->
      <?php if (!empty($recipe['main_image'])) : ?>
        <img
        src="<?= htmlspecialchars($recipe['main_image'], ENT_QUOTES, 'UTF-8'); ?>"
        alt="<?= htmlspecialchars($recipe['title'], ENT_QUOTES, 'UTF-8'); ?>">
      <?php endif; ?>


      <!--title-->
      <h3><?= htmlspecialchars($recipe['title'], ENT_QUOTES, 'UTF-8'); ?></h3>


      <!--subtitle-->
      <?php if (!empty($recipe['subtitle'])) : ?>
        <p>
        <?= htmlspecialchars($recipe['subtitle'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
      <?php endif; ?>


      <!--category and servings-->
      <div class="recipe-info">
        <span class="recipe-category">
        <?= htmlspecialchars($recipe['category'], ENT_QUOTES, 'UTF-8'); ?>
        </span>

        <span class="recipe-servings">
        <?= htmlspecialchars((string) $recipe['servings'], ENT_QUOTES, 'UTF-8'); ?> servings
        </span>
      </div>


      </article>
        <?php endforeach; ?>
      </div>



  </main>

</body>
</html>