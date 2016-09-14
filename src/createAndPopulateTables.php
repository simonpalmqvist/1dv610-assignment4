<?php

require_once('./config.php');

$setupUserTableQuery = "
CREATE TABLE users (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) BINARY NOT NULL UNIQUE,
  password VARCHAR(255) BINARY NOT NULL,
  create_date TIMESTAMP
);
";

$addAdminUserQuery = "
INSERT INTO users (username, password) 
VALUES(:username, :password);
";

$credentials = array(
    'username' => 'Admin',
    'password' => password_hash('Password', PASSWORD_DEFAULT)
);

$db->exec($setupUserTableQuery);
$db->prepare($addAdminUserQuery)->execute($credentials);