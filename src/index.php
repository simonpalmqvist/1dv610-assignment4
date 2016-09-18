<?php

require_once('config.php');
require_once('auth/model/Authentication.php');
require_once('auth/view/DefaultRegistrationForm.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('auth/controller/Register.php');
require_once('controller/LoginController.php');



$authModel = new \auth\model\Authentication($db);
$view = new LoginView();
$registrationView = new \auth\view\DefaultRegistrationForm();
$dateTimeView = new DateTimeView();
$loginController = new LoginController($view, $authModel);
$registerController = new \auth\controller\Register($db, $registrationView);

if (isset($_GET['register'])) {
    $registerController->handleRequest();
    $currentViewHTML = $registerController->getHTMLToPresent();
} else {
    $loginController->handleRequest();
    $currentViewHTML = $loginController->getHTMLToPresent();
}

LayoutView::render($authModel->userIsAuthenticated(), $currentViewHTML, $dateTimeView);
