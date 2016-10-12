<?php

require_once('auth/Auth.php');
require_once('auth/model/DefaultUsers.php');
require_once('auth/view/DefaultRegistrationForm.php');
require_once('auth/view/DefaultLoginForm.php');
require_once('auth/view/DefaultLogoutButton.php');

require_once('view/Footer.php');
require_once('view/Layout.php');

session_start();

$footer = new Footer();

// Using auth default views and user storage
$users = new \auth\model\DefaultUsers();
$login = new \auth\view\DefaultLoginForm();
$logout = new \auth\view\DefaultLogoutButton();
$registration = new \auth\view\DefaultRegistrationForm();

$auth = new \auth\Auth($users, $login, $logout, $registration);

// Handles authentication
$auth->handle(Layout::getWantsToRegister());

// Render out output to send to browser
Layout::render($auth->userIsAuthenticated(), $auth->getHTML(), $footer->generateHTML());