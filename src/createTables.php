<?php

require_once('auth/model/UsersDB.php');

$users = new \auth\model\UsersDB();
$users->setup();