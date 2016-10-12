<?php

require_once('auth/model/UsersDB.php');

$users = new \auth\model\DefaultUsers();
$users->setup();