<?php

require_once('model/Authentication.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');

// MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');
// --

session_start();

$view = new LoginView();
$dateTimeView = new DateTimeView();
$loginController = new LoginController($view);

$loginController->handleRequest();

LayoutView::render($view, $dateTimeView);
