<?php

require_once('./config.php');
require_once('./model/Authentication.php');
require_once('./view/LoginView.php');
require_once('./view/DateTimeView.php');
require_once('./view/LayoutView.php');
require_once('./controller/LoginController.php');

$authentication = new Authentication($db);
$view = new LoginView();
$dateTimeView = new DateTimeView();
$loginController = new LoginController($view, $authentication);

$loginController->handleRequest();

LayoutView::render($authentication->userIsAuthenticated(), $view, $dateTimeView);
