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
  // ai assisted set up



// FOOD SEARCHED IN SEARCHBAR 
$food_searched_in_bar = get_input('recipe_search'); 
$recipes_that_match = []; 
// sets user's search as variable $food_searched_in_bar
// empty array, matching recipes stored here



$all_recipes = [ // array of all recipes
  'rice',
  'rice and chicken',
  'rice and beef',
  'apple',
  'yogurt',
  'banana',
  'baked potato',
  'mashed potato',
  'potato french fries',
]; 



if ($food_searched_in_bar !== '') { //user actually types something 
  foreach ($all_recipes as $an_examined_recipe) { 
    if (str_contains(
      // temporarily converts everything to lowercase
      strtolower($an_examined_recipe), 
      strtolower($food_searched_in_bar)
      )) {
      // only runs when there's a match
      // loop through all recipes and check if given recipe contain words in searchbar
      $recipes_that_match[] = $an_examined_recipe;
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
  <link rel="stylesheet" href="style.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UXID 241 Cookbook</title>
</head>
<body>



<h1>UXID 241 Cookbook</h1>


  
<!--SEED DATA RECIPES DISPLAYED -->
<h2>Recipes from Database</h2>

<!-- WEEK 4 DUE WEEK 5 - RECIPES READ FROM MYSQL DATABASE -->
<?php foreach ($database_recipes as $recipe) : ?>
  <!-- loop through each recipe returned from the database. -->
  <!-- 1st ancho-orange chicken db row, 2nd beef medallions db row, 3rd broccoli & pesto sw db row -->



<!-- DISPLAYING ARRAY PF INFORMATION -->
  <article>

    <h3><?= escape_html($recipe['title']); ?></h3>

    <p>
      <?= escape_html($recipe['description']); ?>
    </p>

    <p>
      Category: <?= escape_html($recipe['category']); ?>
    </p>

    <p>
      Servings: <?= escape_html((string) $recipe['servings']); ?>
    </p>

    <p>
      Prep time: <?= escape_html((string) $recipe['prep_time']); ?> minutes.
    </p>

  </article>



<?php endforeach; ?>



<h2>Search for a food</h2>

  <form action="index.php" method="GET"> <!--creates form-->
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



  <br>
      


  <h2>Submit your recipe</h2>
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


      <br>


    <label for="email">Your Email:</label>
    <input
      type="email"
      id="email"
      name="email"
      value="<?= escape_html($submitted_email); ?>">


      <br>


    <button type="submit">Submit your recipe</button> </form>
</body>
</html>