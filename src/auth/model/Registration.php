<?php

namespace auth\model;

require_once('Users.php');
require_once('UserSession.php');
require_once(dirname(__FILE__) . '/../exception/UsernameAndPasswordTooShortException.php');
require_once(dirname(__FILE__) . '/../exception/UsernameContainsInvalidCharactersException.php');
require_once(dirname(__FILE__) . '/../exception/UsernameTooShortException.php');
require_once(dirname(__FILE__) . '/../exception/UsernameExistsException.php');
require_once(dirname(__FILE__) . '/../exception/PasswordTooShortException.php');
require_once(dirname(__FILE__) . '/../exception/PasswordsDontMatchException.php');

class Registration {
    private $users;

    public function __construct (\PDO $dbConnection) {
        $this->users = new Users($dbConnection);
    }

    public function registerUser (string $username, string $password, string $passwordRepeat) {
        $this->validateCredentials($username, $password, $passwordRepeat);
        $hashedPassword = $this->hashPassword($password);
        $this->users->addUser($username, $hashedPassword);
    }

    private function validateCredentials (string $username, string $password, string $passwordRepeat) {
        $usernameNotValid = !$this->isUsernameValid($username);
        $passwordNotValid = !$this->isPasswordValid($password);

        if ($usernameNotValid && $passwordNotValid)
            throw new \UsernameAndPasswordTooShortException();

        if ($usernameNotValid)
            throw new \UsernameTooShortException();

        if ($passwordNotValid)
            throw new \PasswordTooShortException();

        if ($this->hasInvalidCharactersInString($username)) {
            throw new \UsernameContainsInvalidCharactersException();
        }

        if ($password !== $passwordRepeat) {
            throw new \PasswordsDontMatchException();
        }

        if ($this->users->userExists($username)) {
            throw new \UsernameExistsException();
        }
    }

    private function isUsernameValid (string $username) : bool {
        return strlen($username) > 2;
    }

    private function isPasswordValid (string $password) : bool {
        return strlen($password) > 5;
    }

    private function hasInvalidCharactersInString (string $string) {
        return filter_var($string, FILTER_SANITIZE_STRING) !== $string;
    }

    private function hashPassword (string $password) : string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}