<?php
declare(strict_types=1);

require __DIR__ . '/db.php';
$recipe_id = (int) ($_GET['id'] ?? 0);



$sql = 'SELECT
          recipes.*,
          categories.name AS category
        FROM recipes
        INNER JOIN categories
          ON recipes.category_id = categories.id
        WHERE recipes.id = ?';



$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $recipe_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$recipe = mysqli_fetch_assoc($result);

if (!$recipe) {
  exit('Recipe not found.');
}



$instructions_sql = 'SELECT
                       step_number,
                       instruction,
                       image
                     FROM instructions
                     WHERE recipe_id = ?
                     ORDER BY step_number';
$instructions_stmt = mysqli_prepare($conn, $instructions_sql);



mysqli_stmt_bind_param(
  $instructions_stmt,
  'i',
  $recipe_id
);



mysqli_stmt_execute($instructions_stmt);
$instructions_result = mysqli_stmt_get_result($instructions_stmt);
$instructions = mysqli_fetch_all(
  $instructions_result,
  MYSQLI_ASSOC
);



function escape_html(string $text): string {
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css?v=5">
    <title><?= escape_html($recipe['title']); ?></title>
</head>
<body class="recipe-page">



<!-- BACK BUTTON -->
<a href="index.php" class="back-button">← Back to Recipes</a>




<!-- AI ASSISTED CODE PAGE STRUCTURE -->
<!-- PROMPT: I want to diplay my title, subtitle, main_image, description, category, servings, steps, instructions, and their subsequent imges. How do I do this with the dat in my php admin? -->
        
<!-- INDIVIDUAL RECIPE TITLE -->
        <h1>&diams; <?= escape_html($recipe['title']); ?> &diams;</h1>


        <!-- INDIVIDUAL RECIPE SUBTITLE -->
        <?php if (!empty($recipe['subtitle'])) : ?>
            <h2><?= escape_html($recipe['subtitle']); ?></h2>
        <?php endif; ?>


        <!-- INDIVIDUAL RECIPE MAIN IMAGE -->
        <?php if (!empty($recipe['main_image'])) : ?>
        <img
            src="<?= escape_html($recipe['main_image']); ?>"
            alt="<?= escape_html($recipe['title']); ?>">
        <?php endif; ?>


        <!-- INDIVIDUAL RECIPE DESCRIPTION -->
        <p><?= escape_html($recipe['description']); ?></p>


        <!-- INDIVIDUAL RECIPE CATEGORY -->
        <p>Category: <?= escape_html($recipe['category']); ?></p>


        <!-- INDIVIDUAL RECIPE SERVINGS -->
        <p>Servings: <?= (int) $recipe['servings']; ?></p>



        <!-- INDIVIDUAL RECIPE INGREDIENT IMAGE -->
        <!-- MOST LIKELY NONE -->
        <?php if (!empty($recipe['ingredients_image'])) : ?>
        <img
            src="<?= escape_html($recipe['ingredients_image']); ?>"
            alt="Ingredients for <?= escape_html($recipe['title']); ?>">
        <?php endif; ?>




<!-- INDIVIDUAL RECIPE INGREDIENTS LISTED 1-10 -->
<h2>Ingredients</h2>

    <ul>
    <?php for ($i = 1; $i <= 10; $i++) : ?>

    <?php
      $ingredient = $recipe['ingredient_' . $i];
    ?>

    <?php if (!empty($ingredient)) : ?>
      <li><?= escape_html($ingredient); ?></li>
    <?php endif; ?>

  <?php endfor; ?>
</ul>



<!-- LIST TOOLS -->
<?php if (!empty($recipe['tools'])) : ?>



<!-- TOOLS WORD BOX CONTINER -->
<div class="recipe-note">
  <h2>Tools</h2>
  <p><?= escape_html($recipe['tools']); ?></p>
</div>

<?php endif; ?>





<!-- INSTRUCTIONS SECTION -->
<h2>Instructions</h2>
<?php foreach ($instructions as $step) : ?>


    <!-- STEP NUMBER -->
    <section class="instruction-step">
    <h3>Step <?= (int) $step['step_number']; ?></h3>


    <!-- SUBSEQUENT INSTRUCTION -->
    <p><?= escape_html($step['instruction']); ?></p>


    <!-- SUBSEQUENT STEP IMAGE -->
    <?php if (!empty($step['image'])) : ?>
        <img
        src="<?= escape_html($step['image']); ?>"
        alt="Step <?= (int) $step['step_number']; ?>">
    <?php endif; ?>
    </section>

<?php endforeach; ?>




<!-- TIPS SECTION (MOST LIKELY EMPTY) -->
<?php if (!empty($recipe['tips'])) : ?>
<div class="recipe-note">
  <h2>Tips</h2>
  <p><?= escape_html($recipe['tips']); ?></p>
</div>
<?php endif; ?>




</body>
</html>