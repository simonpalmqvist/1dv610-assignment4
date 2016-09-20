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

// Includes class Authentication to check if users are authenticated with static function isAuthenticated.
use \auth\model\Authentication;

// Views
$registration = new \auth\view\DefaultRegistrationForm();
$login = new \auth\view\DefaultLoginForm();
$logout = new \auth\view\DefaultLogoutButton();
$footer = new Footer();

// Controllers
$loginController = new \auth\controller\Login($db, $login, $logout);
$registerController = new \auth\controller\Register($db, $registration);

// Router - can only reach register controller if not authenticated
if (filter_has_var(INPUT_GET, 'register') && !Authentication::userIsAuthenticated()) {
    $registerController->handleRequest();
    $currentViewHTML = $registerController->getHTMLToPresent();
} else {
    $loginController->handleRequest();
    $currentViewHTML = $loginController->getHTMLToPresent();
}

// Render out output to send to browser
Layout::render(Authentication::userIsAuthenticated(), $currentViewHTML, $footer->generateHTML());
