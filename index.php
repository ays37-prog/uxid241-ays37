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
