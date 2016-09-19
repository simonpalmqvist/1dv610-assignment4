<?php

session_start();

// Change these credentials for production
try {
    $db = new PDO('mysql:host=mysql;dbname=auth', 'user', 'pass');
} catch (PDOException $exception) {
    echo "Couldn't connect to database";
}
