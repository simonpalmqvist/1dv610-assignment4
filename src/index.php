<?php

require_once('./config.php');
require_once('model/Authentication.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');


foreach($db->query('SHOW TABLES') as $row) {
    echo $row[0];
}

$view = new LoginView();
$dateTimeView = new DateTimeView();
$loginController = new LoginController($view);

$loginController->handleRequest();

LayoutView::render($view, $dateTimeView);
