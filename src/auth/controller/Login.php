<?php

namespace auth\controller;

require_once(dirname(__FILE__) . '/../model/Authentication.php');
require_once(dirname(__FILE__) . '/../view/LoginForm.php');
require_once(dirname(__FILE__) . '/../view/LogoutButton.php');

use auth\model\Authentication;
use auth\view\LoginForm;
use auth\view\LogoutButton;

class Login {
    private $model;
    private $loginForm;
    private $logoutButton;

    public function __construct (Authentication $authentication, LoginForm $loginForm, LogoutButton $logoutButton) {
        $this->model = $authentication;
        $this->loginForm = $loginForm;
        $this->logoutButton = $logoutButton;
    }

    public function getHTML () : string {
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

    private function handleGuest () {
        if ($this->loginForm->isPostRequest()) {
            $this->tryLoginUser();

        } else if ($this->model->canLoginWithCookies()) {
            $this->tryLoginWithCookies();

        } else if ($this->model->hasNewlyRegisteredUser()) {
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
            $this->loginForm->setUsername($this->loginForm->getRequestUsername());
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
        $this->loginForm->setUsername($this->model->getNewlyRegisteredUsername());
    }
}