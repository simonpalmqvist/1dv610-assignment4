<?php

namespace auth\model;

interface Users {
    public function addUser(string $username, string $password);

    public function userExists(string $username) : bool;

    public function findUser (string $username) : array; // Should throw \Exception if not found

    public function findUserWithCookie (string $username, string $cookie) : array; // Should throw \Exception if not found

    public function updateUserWithCookie (string $username, string $cookie);

    public function removeUserCookie (string $username);

}