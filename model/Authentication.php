<?php

namespace model;

class Authentication
{
    private static $userSession = 'logged_in_user';

    public static function loginUser($username, $password) {
        if (!self::matchUsernameAndPassword($username, $password)) {
            throw new \Exception("Wrong name or password");
        }
        $_SESSION[self::$userSession] = $username;
    }

    public static function logoutUser() {
        unset($_SESSION[self::$userSession]);
    }

    public static function userIsAuthenticated() {
        return isset($_SESSION[self::$userSession]);
    }

    private static function matchUsernameAndPassword($username, $password) {
        return $username === "Admin" && $password === "Password";
    }

}