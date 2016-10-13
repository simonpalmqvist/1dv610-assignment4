<?php

require_once('controller/Application.php');

session_start();

$application = new controller\Application();

$application->renderPage();