<?php

declare(strict_types=1);

// copies this file as config.php and replaces the values
// with settings for my local db

return [
  'db_host' => 'localhost',
  'db_port' => 8889,
  'db_user' => 'your_database_user',
  'db_password' => 'your_database_password',
  'db_name' => 'entire_recipe_db',
  'db_socket' => '/path/to/your/mysql.sock',
];