<?php

namespace auth\model;

require_once('Users.php');
require_once('UserSession.php');

class Registration {
    private $users;

    public function __construct (\PDO $dbConnection) {
        $this->users = new Users($dbConnection);
    }

    public function registerUser () {

    }

    private function validateCredentials (string $username, string $password, string $passwordRepeat) {

    }
}