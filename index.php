<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/Authentication.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$testUsers = array();
$testUsers['admin'] = 'pass';

//CREATE OBJECTS OF THE VIEWS
$authentication = new Authentication($testUsers);
$v = new LoginView();
$dtv = new DateTimeView();
$lv = new LayoutView();

$lv->render(false, $v, $dtv);

