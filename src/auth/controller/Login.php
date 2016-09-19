<?php

namespace auth\controller;

require_once(dirname(__FILE__) . '/../model/Authentication.php');

use auth\view\LoginForm;
use auth\view\LogoutButton;
use auth\model\Authentication;

class Login {
    private $model;
    private $loginForm;
    private $logoutButton;

    public function __construct (\PDO $db, LoginForm $loginForm, LogoutButton $logoutButton) {
        $this->model = new Authentication($db);
        $this->loginForm = $loginForm;
        $this->logoutButton = $logoutButton;
    }

    public function getHTMLToPresent () : string {
        return  Authentication::userIsAuthenticated() ?
                $this->logoutButton->generateHTML() :
                $this->loginForm->generateHTML();
    }

    public function handleRequest () {
        if (Authentication::userIsAuthenticated()) {
            $this->handleAuthenticatedUser();
        } else {
            $this->handleGuest();
        }
    }

    private function handleAuthenticatedUser () {
        if ($this->logoutButton->isPostRequest()) {
            $this->logoutUser();
        }
    }

    private function handleGuest() {
        if ($this->loginForm->isPostRequest()) {
            $this->tryLoginUser();

        } else if ($this->model->canLoginWithCookies()) {
            $this->tryLoginWithCookies();

        } else if (isset($_SESSION['registered_user'])) {
            $this->handleRedirectFromRegistration();
        }
    }

    private function tryLoginUser () {
        try {
            $this->loginUser();

        } catch (\WrongUsernameOrPasswordException $e) {
            $this->loginForm->setUsername($this->loginForm->getRequestUsername());
            $this->loginForm->setMessageWrongUsernameOrPassword();

        } catch (\UsernameIsMissingException $e) {
            $this->loginForm->setMessageUsernameIsMissing();

        } catch (\PasswordIsMissingException $e) {
            $this->loginForm->setMessagePasswordIsMissing();
        }
    }

    private function loginUser () {
        $username = $this->loginForm->getRequestUsername();
        $password = $this->loginForm->getRequestPassword();
        $remember = $this->loginForm->getRequestKeep();

        $this->model->loginUserWithCredentials($username, $password, $remember);
        $this->logoutButton->setWelcomeMessage($remember);
    }

    private function logoutUser () {
        $this->model->logoutUser();
        $this->loginForm->setMessageLogout();
    }

    private function tryLoginWithCookies () {
        try {
            $this->model->LoginUserWithCookies();
            $this->logoutButton->setWelcomeWithCookiesMessage();
        } catch (\InvalidCookiesException $e) {
            $this->loginForm->setMessageInvalidCookies();
        }
    }

    private function handleRedirectFromRegistration () {
        $this->loginForm->setMessageRegisteredUser();
        $this->loginForm->setUsername($_SESSION['registered_user']);
        unset($_SESSION['registered_user']);
    }
}