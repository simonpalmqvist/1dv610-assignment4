<?php

class LoginController
{
    private $view;
    private $authentication;

    public function __construct(\LoginView $loginView, \auth\model\Auth $authentication) {
        $this->view = $loginView;
        $this->authentication = $authentication;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handlePostRequest();
        } else {
            if (!$this->authentication->userIsAuthenticated() && $this->authentication->tryLoginUserWithCookies()) {
                $this->view->message = 'Welcome back with cookie';
            }
        }

        $this->view->authenticated = $this->authentication->userIsAuthenticated();
    }

    private function handlePostRequest() {
        if($this->view->isRequestLogoutAttempt() && $this->authentication->userIsAuthenticated()) {
            $this->handleLogout();
        } else if ($this->view->isRequestLoginAttempt() && !$this->authentication->userIsAuthenticated()) {
            $this->handleLogin();
        }
    }

    private function handleLogin() {
        try {
            $username = $this->view->getRequestUserName();
            $password = $this->view->getRequestPassword();
            $remember = $this->view->getRequestKeep();
            $this->authentication->loginUserWithCredentials($username, $password, $remember);
            $this->view->message = $remember ? 'Welcome and you will be remembered' : 'Welcome';
        } catch (\Exception $error) {
            $this->view->message = $error->getMessage();
        }
    }
    private function handleLogout() {
        $this->authentication->logoutUser();
        $this->view->message = 'Bye bye!';
    }
}