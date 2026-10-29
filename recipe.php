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
<link rel="stylesheet" href="style.css?v=3">
    <title><?= escape_html($recipe['title']); ?></title>
</head>

<body class="recipe-page">
<a href="index.php" class="back-button">← Back to Recipes</a>
  <h1><?= escape_html($recipe['title']); ?></h1>

  <?php if (!empty($recipe['subtitle'])) : ?>
    <h2><?= escape_html($recipe['subtitle']); ?></h2>
  <?php endif; ?>



<?php if (!empty($recipe['main_image'])) : ?>
  <img
    src="<?= escape_html($recipe['main_image']); ?>"
    alt="<?= escape_html($recipe['title']); ?>">
<?php endif; ?>



  <p><?= escape_html($recipe['description']); ?></p>

  <p>Category: <?= escape_html($recipe['category']); ?></p>

  <p>Servings: <?= (int) $recipe['servings']; ?></p>





<?php if (!empty($recipe['ingredients_image'])) : ?>
  <img
    src="<?= escape_html($recipe['ingredients_image']); ?>"
    alt="Ingredients for <?= escape_html($recipe['title']); ?>">
<?php endif; ?>





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








<?php if (!empty($recipe['tools'])) : ?>

<div class="recipe-note">
  <h2>Tools</h2>
  <p><?= escape_html($recipe['tools']); ?></p>
</div>

<?php endif; ?>







<h2>Instructions</h2>

<?php foreach ($instructions as $step) : ?>

    <section class="instruction-step">
    <h3>Step <?= (int) $step['step_number']; ?></h3>

    <p><?= escape_html($step['instruction']); ?></p>

    <?php if (!empty($step['image'])) : ?>
      <img
        src="<?= escape_html($step['image']); ?>"
        alt="Step <?= (int) $step['step_number']; ?>">
    <?php endif; ?>

  </section>

<?php endforeach; ?>








<?php if (!empty($recipe['tips'])) : ?>

<div class="recipe-note">
  <h2>Tips</h2>
  <p><?= escape_html($recipe['tips']); ?></p>
</div>

<?php endif; ?>





</body>
</html>