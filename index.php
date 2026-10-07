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

