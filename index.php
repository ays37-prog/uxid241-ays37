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



// AI ASSISTED CODE: 
// Prompt: Seearchbar results should show up in the cards results, instead of being displayed in. separate list. So if someone searches a thing, the filters all go blank, and it comes back with the items in the searchbar in the cards. How do I do this?
// Promt 2: When I search a filter item like beef, i only get 2 items back instead of 6. How do i broaden this and have my search give me the same filter results?
// FOOD SEARCHED IN SEARCHBAR 
if ($food_searched_in_bar !== '') {

  // SEARCHING CLEARS FILTER BUTTONS SO THEY DON'T INTERFERE
  $selected_categories = [];

  $database_recipes = array_filter(
    $database_recipes,
    function ($recipe) use ($food_searched_in_bar) {

      $search_term = strtolower($food_searched_in_bar);

      // SEARCHED WORD SEARCHES RECIPE TITLE AND CATEGORY
      if (
        str_contains(strtolower($recipe['title']), $search_term) ||
        str_contains(strtolower($recipe['category']), $search_term)
      ) {
        return true;
      }

      // SEARCHED WORD SEARCHES ALL 10 INGREDIENTS 
      for ($i = 1; $i <= 10; $i++) {

        $ingredient = $recipe['ingredient_' . $i] ?? '';

        if (
          $ingredient !== '' &&
          str_contains(strtolower($ingredient), $search_term)
        ) {
          return true;
        }
      }

      return false;
    }
  );
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
<link rel="stylesheet" href="style.css?v=5">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UXID 241 Cookbook</title>
</head>
<body>

<!--LOG IN BUTTON -->
<a href="login.php" class="login-button">Log In</a>

<main class="main-container">




<!-- HTML STARTS HERE -->
<h1>Uxid 241 Cookbook</h1>

<form action="index.php" method="GET">

  <?php if ($selected_category !== '') : ?>
    <input
      type="hidden"
      name="category"
      value="<?= escape_html($selected_category); ?>">
  <?php endif; ?>




  <!--sends data to this file's server + appends search to URL-->
  <label for="recipe_search"><h2>Search for a food:<h2></label>
  <!--creates form-->
    


<!-- HITTING SEARCH BUTTON -->
  <input
  type="search" 
  id="recipe_search"
  name="recipe_search"
  value="<?= escape_html($food_searched_in_bar); ?>">
  <!--keep in searchbar upon page reloading-->
  <button type="submit">Search</button> </form> 
  





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






<div class="recipe-grid">
<!-- WEEK 4 DUE WEEK 5 - RECIPES READ FROM MYSQL DATABASE -->
<?php foreach ($database_recipes as $recipe) : ?>
  <!-- loop through each recipe returned from the database. -->
  <!-- 1st ancho-orange chicken db row, 2nd beef medallions db row, 3rd broccoli & pesto sw db row -->



<!-- DISPLAYING RECIPE CARDS -->
  <a href="recipe.php?id=<?= (int) $recipe['id']; ?>">
  <article class="recipe-card">



<!-- RECIPE CARD IMAGE -->
  <?php if (!empty($recipe['main_image'])) : ?>
    <img
    src="<?= escape_html($recipe['main_image']); ?>"
    alt="<?= escape_html($recipe['title']); ?>">
  <?php endif; ?>



<!-- SHOW CLICKABLE RECIPE CARD TITLE LINK -->
    <h3>
    <?= escape_html($recipe['title']); ?>
    </h3>


<!-- SHOW RECIPE CARD SUBTITLE -->
    <?php if (!empty($recipe['subtitle'])) : ?>
    <p><?= escape_html($recipe['subtitle']); ?></p>
    <?php endif; ?>



   <!-- SHOW RECIPE CATEGORY AND SERVINGS  -->
    <div class="recipe-info">
      <span class="recipe-category">
      <?= escape_html($recipe['category']); ?>
      </span>

      <span class="recipe-servings">
      <?= escape_html((string) $recipe['servings']); ?> servings
      </span>
    </div>
  </article>
  
  </a>
  <?php endforeach; ?>
  </div>


  <br>
  <br>
      

  <!-- SUBMIT YOUR RECIPE FORM  -->

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