<?php

require_once('config.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('auth/model/Auth.php');

$authModel = new \auth\model\Auth($db);
$view = new LoginView();
$dateTimeView = new DateTimeView();
$loginController = new LoginController($view, $authModel);

$loginController->handleRequest();

LayoutView::render($authModel->userIsAuthenticated(), $view, $dateTimeView);
