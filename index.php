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
  'green bean'
  'chicken and rice'
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


