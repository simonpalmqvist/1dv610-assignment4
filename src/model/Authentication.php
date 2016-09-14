<?php

class Authentication
{
    private static $userSession = 'logged_in_user';
    private static $userAgentSession = 'HTTP_USER_AGENT';
    private static $userCookie = 'username';
    private static $passwordCookie = 'password';
    private static $findUserQuery = 'SELECT username, password FROM users WHERE username LIKE :username';
    private $db;

    public function __construct (PDO $db) {
        $this->db = $db;
    }

    public function loginUser ($username, $password, $remember) {
        $this->validateUserCredentials($username, $password);
        $this->startUserSession($username);
        $this->maybeAddCookie($username, $password, $remember);
    }

    public function loginWithCookie () {
        $this->startUserSession($_COOKIE[self::$userCookie]);
    }

    public function logoutUser () {
        setcookie(self::$userCookie, '', time() - 1000);
        setcookie(self::$passwordCookie, '', time() - 1000);
        unset($_SESSION[self::$userSession]);
    }

    public function userIsAuthenticated () {
        return isset($_SESSION[self::$userSession]) &&
                isset($_SESSION[self::$userAgentSession]) &&
                $_SESSION[self::$userAgentSession] == $_SERVER[self::$userAgentSession];
    }

    public function validateUserCookie () {
        return isset($_COOKIE[self::$userCookie]) && isset($_COOKIE[self::$passwordCookie]);
    }

    private function startUserSession ($username) {
        $_SESSION[self::$userSession] = $username;
        $_SESSION[self::$userAgentSession] = $_SERVER[self::$userAgentSession];
    }

    private function maybeAddCookie ($username, $password, $remember) {
        if ($remember) {
            setcookie(self::$userCookie, $username, time()+60*60*24*30);
            setcookie(self::$passwordCookie, $this->hashPassword($password), time()+60*60*24*30);
        }
    }


    private function validateUserCredentials ($username, $password) {
        if (empty($username)) {
            throw new Exception('Username is missing');
        }
        if (empty($password)) {
            throw new Exception('Password is missing');
        }
        if (!$this->matchUsernameAndPassword($username, $password)) {
            throw new Exception('Wrong name or password');
        }
    }

    private function matchUsernameAndPassword ($username, $password) {
        $query = $this->db->prepare(self::$findUserQuery);
        $query->execute(array('username' => $username));
        $user = $query->fetch();

        return password_verify($password, $user['password']);
    }

    private function hashPassword ($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

}