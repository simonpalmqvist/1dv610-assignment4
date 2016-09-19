<?php

require_once('config.php');
require_once('auth/model/Authentication.php');
require_once('auth/view/DefaultRegistrationForm.php');
require_once('auth/view/DefaultLoginForm.php');
require_once('auth/view/DefaultLogoutButton.php');
require_once('auth/controller/Register.php');
require_once('auth/controller/Login.php');
require_once('view/Footer.php');
require_once('view/Layout.php');

use \auth\model\Authentication;

// Views
$registration = new \auth\view\DefaultRegistrationForm();
$login = new \auth\view\DefaultLoginForm();
$logout = new \auth\view\DefaultLogoutButton();
$footer = new Footer();

// Controllers
$loginController = new \auth\controller\Login($db, $login, $logout);
$registerController = new \auth\controller\Register($db, $registration, $login);

// Router
if (filter_has_var(INPUT_GET, 'register') && !Authentication::userIsAuthenticated()) {
    $registerController->handleRequest();
    $currentViewHTML = $registerController->getHTMLToPresent();
} else {
    $loginController->handleRequest();
    $currentViewHTML = $loginController->getHTMLToPresent();
}

// Render
Layout::render(Authentication::userIsAuthenticated(), $currentViewHTML, $footer->generateHTML());
