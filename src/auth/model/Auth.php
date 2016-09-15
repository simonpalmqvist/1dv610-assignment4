<?php

namespace auth\model;

require_once('Users.php');
require_once('UserSession.php');

class Auth {
    private $users;

    public function __construct (\PDO $dbConnection) {
        $this->users = new Users($dbConnection);
        // Create Cookie object
    }

    public function loginUserWithCredentials (string $username, string $password, bool $rememberLogin) {
        $this->validateCredentials($username, $password);
        UserSession::setWith($username, $this->hashPassword($password), $rememberLogin);
    }

    public function tryLoginUserWithCookies () : bool {
        $validCookies = UserSession::hasValidCookies();
        if ($validCookies)
            UserSession::setWithCookies();

        return $validCookies;
    }

    public function logoutUser () {
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

    private function hashPassword (string $password) : string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

}