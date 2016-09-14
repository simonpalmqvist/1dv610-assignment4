<?php

class Authentication
{
    private static $userSession = 'logged_in_user';
    private static $findUserQuery = 'SELECT username, password FROM users WHERE username=:username';
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function loginUser($username, $password) {
        $this->validateUserCredentials($username, $password);

        $_SESSION[self::$userSession] = $username;
    }

    public static function logoutUser() {
        unset($_SESSION[self::$userSession]);
    }

    public function userIsAuthenticated() {
        return isset($_SESSION[self::$userSession]);
    }

    private function validateUserCredentials($username, $password) {
        if (empty($username)) {
            throw new \Exception('Username is missing');
        }
        if (empty($password)) {
            throw new \Exception('Password is missing');
        }
        if (!$this->matchUsernameAndPassword($username, $password)) {
            throw new \Exception('Wrong name or password');
        }
    }

    private function matchUsernameAndPassword($username, $password) {
        $query = $this->db->prepare(self::$findUserQuery);
        $query->execute(array('username' => $username));
        $user = $query->fetch();

        return password_verify($password, $user['password']);
    }

}