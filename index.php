<?php
declare(strict_types=1); 
// strict about type of information accepted

session_start();

require __DIR__ . '/db.php';
// before continuing, load and run code from db.php
// DIR is the directory to find db.php


function get_input(string $input_name): string {
//  GET & POST 
  return trim($_GET[$input_name] ?? '');
} // GET what user entered, trim spaces, returns cleaned text


  // if it does not exist, use an empty string instead
function post_input(string $input_name): string {
  return trim($_POST[$input_name] ?? '');
} // gets what user entered, trims spaces, returns clean text


function escape_html(string $text_to_escape): string {
  return htmlspecialchars($text_to_escape, ENT_QUOTES, 'UTF-8');
} // converts HTML characters into solid safe text


$food_searched_in_bar = get_input('recipe_search');
$selected_category = get_input('category');


$sql = 'SELECT
          recipes.id,
          recipes.title,
          recipes.description,
          recipes.servings,
          recipes.prep_time,
          recipes.slug,
          recipes.subtitle,
          recipes.main_image,
          recipes.ingredient_1,
          recipes.ingredient_2,
          recipes.ingredient_3,
          recipes.ingredient_4,
          recipes.ingredient_5,
          recipes.ingredient_6,
          recipes.ingredient_7,
          recipes.ingredient_8,
          recipes.ingredient_9,
          recipes.ingredient_10,
          categories.name AS category
        FROM recipes
        INNER JOIN categories
          ON recipes.category_id = categories.id
        ORDER BY recipes.title';
// match each recipe's category_id with the corresponding id in categories
// id name: 1 chicken, 2 beef, 3 vegetarian, 4 fish & seafood, 5 pork
// categories.name shows word insead of just number
// AS category: $recipe['category']
// FROM: table retrieval FROM recipes
// INNER JOIN: connect categories table to these recipe results
// ON: if category_id and title id are same, correct recipe sorting
// ORDER BY: sort recipes alphabetically by title






// AI-assisted: search the database safely with a prepared LIKE statement.
if ($food_searched_in_bar !== '') {
  $search_term = '%' . $food_searched_in_bar . '%';
  $search_sql = 'SELECT
                  recipes.id,
                  recipes.title,
                  recipes.description,
                  recipes.servings,
                  recipes.prep_time,
                  recipes.slug,
                  recipes.subtitle,
                  recipes.main_image,
                  recipes.ingredient_1,
                  recipes.ingredient_2,
                  recipes.ingredient_3,
                  recipes.ingredient_4,
                  recipes.ingredient_5,
                  recipes.ingredient_6,
                  recipes.ingredient_7,
                  recipes.ingredient_8,
                  recipes.ingredient_9,
                  recipes.ingredient_10,
                  categories.name AS category
                FROM recipes
                INNER JOIN categories
                  ON recipes.category_id = categories.id
                WHERE recipes.title LIKE ?
                   OR recipes.description LIKE ?
                   OR categories.name LIKE ?
                ORDER BY recipes.title';


  $result = mysqli_execute_query(
    $conn,
    $search_sql,
    [$search_term, $search_term, $search_term]
  );

} else {
  $result = mysqli_query($conn, $sql);
}


$database_recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);
// takes dtabase results and turns into PHP array
// fetch all rows that come back
// organize each row using database column names



// FILTRATION LINKS ACTUALLY FILTER THE RECIPES
if ($selected_category !== '') {
  $database_recipes = array_filter(
    $database_recipes,
    function ($recipe) use ($selected_category) {
      return $recipe['category'] === $selected_category;
    }
  );
}
?>



<!--PHP SET UP ASSIGNMENT WEEK 2-->
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css?v=6">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UXID 241 Cookbook</title>
</head>
<body>



<!--log in OR admin button at top left -->
<?php if (isset($_SESSION['user_id'])) : ?>
  <a href="admin.php" class="round-button">Admin</a>

<?php else : ?>
  <a href="login.php" class="round-button">Log In</a>

<?php endif; ?>



<main class="main-container">
<h1>Uxid 241 Cookbook</h1>



<!-- recipe serchbar built here-->
<form action="index.php" method="GET">
    <!-- gets all recipes that match and appends to URL-->
    <?php if ($selected_category !== '') : ?>
    <input
      type="hidden"
      name="category"
      value="<?= escape_html($selected_category); ?>">
    <?php endif; ?>
    <label for="recipe_search"><h2>Search for a food:<h2></label>

    <!-- search button itself -->
    <input
    class="form-input"
    type="search" 
    id="recipe_search"
    name="recipe_search"
    value="<?= escape_html($food_searched_in_bar); ?>">
    <!--keep in searchbar upon page reloading-->

    <button type="submit" class="dark-button">Search</button>
</form> 








<!--NUMBER OF HOW MANY RECIPES MATCH YOUR SEARCH -->
<h2>&diams; <?= count($database_recipes); ?>  Recipes Match Your Search &diams; </h2>
<br><br><br>




<!--AI ASSISTED FILTRATION CODE -->
<!--Promppt: Please show me how to set up the initial filtration button system based off of chicken, beef, vegetarian, fish & seafood, pork, and turkey categories in my php admin -->
<!--Second Promppt: Show me how to visually indicate the current filter in use, working with CSS. -->
<div class="category-filters">

  <a
    href="index.php"
    class="<?= $selected_category === '' ? 'active' : ''; ?>">
    All
  </a>

  <a
    href="index.php?category=Chicken"
    class="<?= $selected_category === 'Chicken' ? 'active' : ''; ?>">
    Chicken
  </a>

  <a
    href="index.php?category=Beef"
    class="<?= $selected_category === 'Beef' ? 'active' : ''; ?>">
    Beef
  </a>

  <a
    href="index.php?category=Vegetarian"
    class="<?= $selected_category === 'Vegetarian' ? 'active' : ''; ?>">
    Vegetarian
  </a>

  <a
    href="index.php?category=Fish%20%26%20Seafood"
    class="<?= $selected_category === 'Fish & Seafood' ? 'active' : ''; ?>">
    Fish &amp; Seafood
  </a>

  <a
    href="index.php?category=Pork"
    class="<?= $selected_category === 'Pork' ? 'active' : ''; ?>">
    Pork
  </a>

  <a
    href="index.php?category=Turkey"
    class="<?= $selected_category === 'Turkey' ? 'active' : ''; ?>">
    Turkey
  </a>
</div>



<!-- AI assisted code:-->
 <!-- prompt: setup a recipe card grid display 
  in which each recipe card contains its name, 
  subtitle, category, and serving size. Set it up in such 
  a way I can adjust the layout in css later-->
<div class="recipe-grid">
<?php foreach ($database_recipes as $recipe) : ?>
  <!-- loop through each recipe returned from the database. -->
  <!-- 1st ancho-orange chicken db row, 2nd beef medallions db row, 3rd broccoli & pesto sw db row -->



<!-- RECIPE CARDS -->
  <a href="recipe.php?id=<?= (int) $recipe['id']; ?>">
  <article class="recipe-card">
<!-- RECIPE IMAGE -->
  <?php if (!empty($recipe['main_image'])) : ?>
    <img
    src="<?= escape_html($recipe['main_image']); ?>"
    alt="<?= escape_html($recipe['title']); ?>">
  <?php endif; ?>
<!-- RECIPE TITLE -->
  <h3><?= escape_html($recipe['title']); ?></h3>
<!-- RECIPE  SUBTITLE -->
    <?php if (!empty($recipe['subtitle'])) : ?>
    <p><?= escape_html($recipe['subtitle']); ?></p>
    <?php endif; ?>
<!--CATEGORY -->
    <div class="recipe-info">
      <span class="recipe-category">
      <?= escape_html($recipe['category']); ?>
      </span>
<!--SEVINGS -->
      <span class="recipe-servings">
      <?= escape_html((string) $recipe['servings']); ?> servings
      </span>
    </div>
  </article>
  
  </a>
  <?php endforeach; ?>
  </div>


</main>
</body>
</html>