<?php

namespace auth;

require_once('model/Authentication.php');
require_once('model/Registration.php');
require_once('model/Users.php');
require_once('view/LoginForm.php');
require_once('view/LogoutButton.php');
require_once('view/RegistrationForm.php');
require_once('controller/Register.php');
require_once('controller/Login.php');

use auth\model\Users;
use auth\view\LoginForm;
use auth\view\LogoutButton;
use auth\view\RegistrationForm;

class Auth {
    private $loginController;
    private $registerController;
    private $html;

    public function __construct (Users $users, LoginForm $login, LogoutButton $logout, RegistrationForm $register) {
        $authentication = new model\Authentication($users);
        $registration = new model\Registration($users);

        $this->loginController = new controller\Login($authentication, $login, $logout);
        $this->registerController = new controller\Register($registration, $register);
    }

    public function userIsAuthenticated () : bool {
        return model\Authentication::userIsAuthenticated();
    }

    public function handle (bool $wantsToRegister) {
        if ($wantsToRegister && !$this->userIsAuthenticated()) {
            $this->registerController->handleRequest();
            $this->html = $this->registerController->getHTML();
        } else {
            $this->loginController->handleRequest();
            $this->html = $this->loginController->getHTML();
        }
    }

    public function getHTML () : string {
        return $this->html;
    }
}