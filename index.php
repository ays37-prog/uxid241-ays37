<?php
declare(strict_types=1);
// 0=strict types off
// 1=strict types on
// stricter about the types of information my function accepts

function value_trim(string $key): string {
  return trim($_GET[$key] ?? '');
}
// gets that value the user submitted through GET and trims extra spaces the user may type 
// if the item doesn't exist, ??'' is blank 


function post_value(string $key): string {
  return trim($_POST[$key] ?? '');
}
// gets information from a POST form
// get the POST input with its name
// ??'' use an empty string if it doesnt exist
// remove extra spaces and return the cleaned value 


function e(string $value): string {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
// htmlspecialchars() converts HTML characters into solid and safe text



// SEARCHING FOR FOOD IN THE DATABASE

$food_searched = value_trim('q');
// gets user's typed input named q and removes extra spaces
// stores results as a variable in "food_searched"
// food_searched will now contain their input

$food_results = [];
// empty array for matching recipes to collect here

$recipes = [
  'chicken',
  'chicken 2',
  'chicken 3',
  'milk',
  'yogurt',
  'rice',
  'green bean',
  'chicken and rice',
];
// temporary cookbook array containing my recipes
// it will fill bigger with the recipes from the 200 pdfs later 

if ($food_searched !== '') {
// only do the following if this condition is true
// only seach recipes if the user actually types something 

  foreach ($recipes as $recipe) {
// foreach is a loop
// it goes through all my arrays in $recipes one recipe at a time
// $recipe represents whichever recipe PHP currently is checking

    if (str_contains(
// asks does this text contain other text 
// checking whether the recipe contains the search
      strtolower($recipe), 
      strtolower($food_searched)
      )) {
// makes the text lowercase so all variations of FISH Fish fish work
// does chicken 2 contain chicken?
// does chicken and rice contain rice?
      $food_results[] = $recipe;
    }
  }
}
// if a recipe matches results, add that recipe to $food_results array



// SUBMITTING THE RECIPE 

$recipe_name = '';
$email = '';
$errors = [];
$success = false;
// start empty since user hasn't submitted anything
// empty array where error messages cn be added
// clears the form since it hasn't been submitted yet


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // did the user submit the POST form
  $recipe_name = post_value('name');
  $email = post_value('email');
  // get user's submitted recipe name and email
  // trim extra spaces
  // store values in $recipename and $email



  // VALIDATING THE RECIPE NAME

  if ($recipe_name === '') {
    $errors[] = 'Recipe name is required.';
  }
  // makes sure the user didn't submit nothing
  // error message gets added to $errors array

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
  }
  // checks if the email address is invalid
  // error message gets added to $errors array

  if (empty($errors)) {
    $success = true;
  }
}
  // if it passes the conditions success updates to true 
?>

 <!-- PHP SET UP ASSIGNMENT FROM WEEK 2 -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UXID 241 Cookbook</title>
</head>

<body>

  <h1>UXID 241 Cookbook</h1>

  <p>Php is working!</p>



  <!-- SEARCHING FOR THE FOOD -->

  <h2>Food Search</h2>

  <form action="index.php" method="GET">
      <!--creates an area where users can enter information and submit it -->
      <!--action="index.php" sends their information to index.php upon search submissoin-->

    <label for="q">Recipe name has:</label>
      <!--recipe name has: next to the search box above-->
      <!--q for query aka search-->

    <input
      type="search"
      id="q"
      name="q"
      value="<?= e($food_searched); ?>"
    >
     <!--makes an input box for searching-->
     <!--this label q aka search belongs to the input box-->
     <!--name=q sets user's search to q aka search-->
     <!--URL updates with users submitted search-->

     <!--value box showcases the user's search with their entered item (echo/display)-->
     <!--also keeps their search visable even upon refresh-->
     <!--e is my function, displaying text this but making it safe to display first -->

     
  

    <button type="submit">Search</button>
  </form>
      <!--when search button is clicked, form is submitted-->
      <!--name q (users entered item) gets sent to index.php-->

  <?php if ($food_searched !== '') : ?>
      <!--if search bar submission is not empty, show results-->
      <!--if theres no search, no results seen-->

    <p>
      <?= count($food_results); ?> result(s) for
      "<?= e($food_searched); ?>"
      <!--count how many results for their search are in the database-->
      <!--if searched 'chicken', all the chicken1-3 will return 4 result(s) for chicken-->
    </p>

    <ul>
      <?php foreach ($food_results as $recipe) : ?>
        <li><?= e($recipe); ?></li>
        <!--go through each item inside $foodresults and list them (echo)-->
      <?php endforeach; ?>
    </ul>

  <?php endif; ?>

  <!--
  $key=string, name of the user's input we're trying to get, set as q
  $value=string, text passed into the e() function to make sure its safe before displaying
  $food_searched=string, the item the user searched for
  $food_results=array, of recipes that matched the search
  $recipes=array, all of my recipe names (will be big later)
  $recipe=string, one recipe selected at a time while a foreach loop runs
  $recipe_name=string, the recipe name submitted through the POST form
  $email=string, the submitted email address
  $errors=array, any validation error messages
  $success=boolean, either true or false, depending on whether submission succeeded
  $error=string, one error at a time while the error loop runs
  -->

