<?php

namespace controller;

require_once(dirname(__FILE__) . '/../Config.php');

require_once(dirname(__FILE__) . '/../auth/Auth.php');
require_once(dirname(__FILE__) . '/../auth/model/DefaultUsers.php');
require_once(dirname(__FILE__) . '/../auth/view/DefaultRegistrationForm.php');
require_once(dirname(__FILE__) . '/../auth/view/DefaultLoginForm.php');
require_once(dirname(__FILE__) . '/../auth/view/DefaultLogoutButton.php');

require_once(dirname(__FILE__) . '/../view/Footer.php');
require_once(dirname(__FILE__) . '/../view/Layout.php');

class Application {

    private $db;

    public function __construct () {
        try {
            $this->connectDB();

        } catch (\PDOException $exception) {
            $this->quitWithErrorMessage($exception->getMessage());
        }
    }

    private function connectDB () {
        $connectionInfo = 'mysql:host=' . \Config::getHost() . ';dbname=' . \Config::getDbName() . '';

        $this->db = new \PDO($connectionInfo, \Config::getUser(), \Config::getPass());
    }

    private function quitWithErrorMessage(string $message) {
        echo 'Couldn\'t connect to database: ' . $message;
        exit();
    }

    public function renderPage () {
        $footer = new \view\Footer();

        // Using auth default views and user storage
        $users = new \auth\model\DefaultUsers($this->db);
        $login = new \auth\view\DefaultLoginForm();
        $logout = new \auth\view\DefaultLogoutButton();
        $registration = new \auth\view\DefaultRegistrationForm();

        $auth = new \auth\Auth($users, $login, $logout, $registration);

        $auth->handle(\view\Layout::getWantsToRegister());

        // Render out output to send to browser
        \view\Layout::render($auth->userIsAuthenticated(), $auth->getHTML(), $footer->generateHTML());
    }
}