<?php
declare(strict_types=1); 
// strict about type of information accepted



// WEEK 4 DUE WEEK 5 CATEGORIES SETUP
require __DIR__ . '/db.php';
// before continuing, load and run code from db.php
// DIR is the directory to find db.php



$sql = 'SELECT
          recipes.id,
          recipes.title,
          recipes.description,
          recipes.servings,
          recipes.prep_time,
          recipes.slug,
          recipes.subtitle,
          recipes.main_image,
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




$result = mysqli_query($conn, $sql);
$database_recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);
// takes dtabase results and turns into PHP array
// fetch all rows that come back
// organize each row using database column names
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





// FILTRATION LINKS ACTUALLY FILTER THE RECIPES
$selected_category = get_input('category');
$food_searched_in_bar = get_input('recipe_search');

if ($selected_category !== '') {
  $database_recipes = array_filter(
    $database_recipes,
    function ($recipe) use ($selected_category) {
      return $recipe['category'] === $selected_category;
    }
  );
}




// FOOD SEARCHED IN SEARCHBAR 
$recipes_that_match = []; 
// sets user's search as variable $food_searched_in_bar
// empty array, matching recipes stored here
if ($food_searched_in_bar !== '') { //user actually types something 
  foreach ($database_recipes as $an_examined_recipe) {
    if (str_contains(
      // temporarily converts everything to lowercase
      strtolower($an_examined_recipe['title']),
      strtolower($food_searched_in_bar)
      )) {
      // only runs when there's a match
      // loop through all recipes and check if given recipe contain words in searchbar
      $recipes_that_match[] = $an_examined_recipe['title'];
    }
  }
}



// SUBMITTING A RECIPE 
$submitted_recipe_name = '';
$submitted_email = '';
$validation_errors = [];
$submission_successful = false;
// default empty/false for everything 




//SERVER SIDE VALIDATION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // if user submits the POST recipe form then
  $submitted_recipe_name = post_input('name');
  $submitted_email = post_input('email');
  // store corresponding values in each varible



// VALIDATING SUBMITTED RECIPES
  if ($submitted_recipe_name === '') { //submission can't be blank
    $validation_errors[] = 'Recipe name is required.';
  }
  if (!filter_var($submitted_email, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'Please enter a valid email address.';
  }
  if (empty($validation_errors)) {
    $submission_successful = true;
  }
}
?>




<!--PHP SET UP ASSIGNMENT WEEK 2-->
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css?v=3">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UXID 241 Cookbook</title>
</head>
<body>

<main class="main-container">



<h1>UXID 241 Cookbook</h1>



<h2>Search for a food:</h2>

<form action="index.php" method="GET">

  <?php if ($selected_category !== '') : ?>
    <input
      type="hidden"
      name="category"
      value="<?= escape_html($selected_category); ?>">
  <?php endif; ?>
  <!--sends data to this file's server + appends search to URL-->
  <label for="recipe_search">Recipe name has:</label>
  <!--creates form with x icon-->
    
  <input
  type="search" 
  id="recipe_search"
  name="recipe_search"
  value="<?= escape_html($food_searched_in_bar); ?>">
  <!--keep in searchbar upon page reloading-->
  <button type="submit">Search</button> </form> 
  

    <!--ai assisted conversion of special characters to safe neutral code-->
    <?php if ($food_searched_in_bar !== '') : ?> 
    <!--submission can't be blank-->
    <p><?= count($recipes_that_match); ?> result(s) for 
    "<?= escape_html($food_searched_in_bar); ?>"</p>
    <!--count results for their search. all "chicken" recipes will return-->
  
    <ul><?php foreach ($recipes_that_match as $an_examined_recipe) : ?>
      <li><?= escape_html($an_examined_recipe); ?></li>
      <!--list every item that matched-->
      <?php endforeach; ?></ul>
  <?php endif; ?>





<!--SEED DATA RECIPES DISPLAYED -->
<h2><?= count($database_recipes); ?> Recipes Match Your Search</h2>

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







<div class="recipe-grid">
<!-- WEEK 4 DUE WEEK 5 - RECIPES READ FROM MYSQL DATABASE -->
<?php foreach ($database_recipes as $recipe) : ?>
  <!-- loop through each recipe returned from the database. -->
  <!-- 1st ancho-orange chicken db row, 2nd beef medallions db row, 3rd broccoli & pesto sw db row -->



<!-- DISPLAYING ARRAY DB INFORMATION -->
  <article class="recipe-card">


<!-- RECIPE IMAGES -->
  <?php if (!empty($recipe['main_image'])) : ?>
  
    <img
    src="<?= escape_html($recipe['main_image']); ?>"
    alt="<?= escape_html($recipe['title']); ?>">

    
  <?php endif; ?>


  


<!-- CLICKABLE LINKS -->
    <h3>
    <a href="recipe.php?id=<?= (int) $recipe['id']; ?>">
    <?= escape_html($recipe['title']); ?>
    </a>
    </h3>


<!-- SUBTITLE -->
    <?php if (!empty($recipe['subtitle'])) : ?>
    <p><?= escape_html($recipe['subtitle']); ?></p>
    <?php endif; ?>



   <!-- RECIPE INFO CARD -->
 
<div class="recipe-info">
  <span class="recipe-category">
    <?= escape_html($recipe['category']); ?>
  </span>

  <span class="recipe-servings">
    <?= escape_html((string) $recipe['servings']); ?> servings
  </span>
</div>

  </article>
<?php endforeach; ?>
</div>



  <br>






  <br>
      


  <h2>Submit your recipe:</h2>
  <?php if (!empty($validation_errors)) : ?> 
  <!--are any error messages stored in $-->

  <!--if errors exist, open bulleted list-->
  <ul><?php foreach ($validation_errors as $current_error) : ?>
    <li><?= escape_html($current_error); ?></li>
    <!--list all errors in bullets-->
    <?php endforeach; ?></ul>
    <?php endif; ?>
    <!--escape_html blocks malicious code-->



  <!--SUBMISSION WORKS-->
  <?php if ($submission_successful) : ?>
    <p>Recipe submitted successfully!</p>
    <p>Recipe: <?= escape_html($submitted_recipe_name); ?></p>
    <!--prints submitted recipe name safely preventing cross site scripting-->
    <p>Email: <?= escape_html($submitted_email); ?></p>
    <!--also prints safe email-->
  <?php endif; ?>



  <!--SUBMIT A RECIPE FORM-->
  <form action="index.php" method="POST">
  <!--sends data to this file's server + doesn't append to URL-->

    <label for="name">Recipe Name:</label>
    <input
      type="text"
      id="name"
      name="name"
      value="<?= escape_html($submitted_recipe_name); ?>">


      <br><br>


    <label for="email">Your Email:</label>
    <input
      type="email"
      id="email"
      name="email"
      value="<?= escape_html($submitted_email); ?>">


      <br><br>


    <button type="submit">Submit your recipe</button> </form>
    </main>
</body>
</html>