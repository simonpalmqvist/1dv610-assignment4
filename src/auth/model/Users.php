<?php

namespace auth\model;

require_once('User.php');

interface Users {
    public function addUser(string $username, string $password);

    public function userExists(string $username) : bool;

    public function findUser (string $username) : User; // Should throw \Exception if not found

    public function findUserWithCookie (string $username, string $cookie) : User; // Should throw \Exception if not found

    public function updateUserWithCookie (string $username, string $cookie);

    public function removeUserCookie (string $username);

}