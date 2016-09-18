<?php

namespace auth\model;

require_once('Users.php');
require_once('UserSession.php');

class Auth {
    private $users;

    public function __construct (\PDO $dbConnection) {
        $this->users = new Users($dbConnection);
    }

    public function loginUserWithCredentials (string $username, string $password, bool $rememberLogin) {
        $this->validateCredentials($username, $password);
        UserSession::setWith($username);
        if ($rememberLogin) {
            $this->setCookieFor($username);
        }
    }

    public function canLoginWithCookies () : bool {
        return UserSession::hasCookiesSet();
    }

    public function LoginUserWithCookies () {
        try {
            $this->users->findUserWithCookie(UserSession::getCookieUsername(), UserSession::getCookiePassword());
            $this->startSession(UserSession::getCookieUsername());
            $this->setCookieFor(UserSession::getCookieUsername());
        } catch (\Exception $exception) {
            throw new \Exception('Wrong information in cookies');
        }
    }

    public function logoutUser () {
        $this->users->removeUserCookie(UserSession::getSessionUsername()); // removes cookie
        UserSession::destroy();
    }

    public function userIsAuthenticated () : bool  {
        return UserSession::isActive();
    }

    private function validateCredentials ($username, $password) {
        if (empty($username))
            throw new \Exception('Username is missing');

        if (empty($password))
            throw new \Exception('Password is missing');

        $this->matchUsernameAndPassword($username, $password);
    }

    private function matchUsernameAndPassword(string $username, string $password) {
        try {
            $user = $this->users->findUser($username);
            $this->validatePassword($password, $user['password']);

        } catch (\Exception $exception) {
            throw new \Exception("Wrong name or password");
        }
    }

    private function validatePassword (string $password, string $candidate) {
        if (!password_verify($password, $candidate))
            throw new \Exception("Password is not correct");
    }

    private function startSession (string $username) {
        UserSession::setWith($username);
    }

    private function setCookieFor (string $username) {
        $secret = bin2hex(random_bytes(60));
        UserSession::setCookies($secret);
        $this->users->updateUserWithCookie($username, $secret);
    }

}