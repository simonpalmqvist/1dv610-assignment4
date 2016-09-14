<?php

class LoginController
{
    private $view;

    public function __construct(\LoginView $loginView) {
        $this->view = $loginView;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handlePostRequest();
        }

        $this->view->authenticated = Authentication::userIsAuthenticated();
    }

    private function handlePostRequest() {
        if($this->view->isRequestLogoutAttempt() && Authentication::userIsAuthenticated()) {
            $this->handleLogout();
        } else if ($this->view->isRequestLoginAttempt() && !Authentication::userIsAuthenticated()) {
            $this->handleLogin();
        }
    }

    private function handleLogin() {
        try {
            $username = $this->view->getRequestUserName();
            $password = $this->view->getRequestPassword();
            Authentication::loginUser($username, $password);
            $this->view->message = 'Welcome';
        } catch (\Exception $error) {
            $this->view->message = $error->getMessage();
        }
    }

    private function handleLogout() {
        Authentication::logoutUser();
        $this->view->message = 'Bye bye!';
    }
}