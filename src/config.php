<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

session_start();

try {
    $db = new PDO('mysql:host=mysql;dbname=auth', 'user', 'pass');
} catch (PDOException $exception) {
    echo "Couldn't connect to database";
}
