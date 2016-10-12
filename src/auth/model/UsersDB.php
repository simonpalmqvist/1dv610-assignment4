<?php

namespace auth\model;

require_once(dirname(__FILE__) . '/../../config.php');
require_once('Users.php');

class UsersDB implements Users {
    private static $USERNAME_PARAM = 'username';
    private static $PASSWORD_PARAM = 'password';
    private static $COOKIE_PARAM = 'cookie';
    private static $ADD_USER_QUERY = 'INSERT INTO users (username, password) VALUES (:username, :password)';
    private static $FIND_USER_QUERY = 'SELECT username, password FROM users WHERE username LIKE :username';
    private static $FIND_COOKIE_QUERY = 'SELECT username, cookie FROM users WHERE username LIKE :username and cookie LIKE :cookie';
    private static $UPDATE_COOKIE_QUERY = 'UPDATE users SET cookie = :cookie WHERE username LIKE :username';
    private static $REMOVE_COOKIE_QUERY = 'UPDATE users SET cookie = NULL WHERE username LIKE :username';
    private static $SCHEMA = '
    CREATE TABLE users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) BINARY NOT NULL UNIQUE,
            password VARCHAR(255) BINARY NOT NULL,
            cookie VARCHAR(255) BINARY,
            create_date TIMESTAMP
        );
    ';
    private $db;

    public function __construct () {
        try {
            $this->db = new \PDO('mysql:host=' . \Config::getHost() . ';dbname=' . \Config::getDbName() . '',
                \Config::getUser(),
                \Config::getPass());

        } catch (\PDOException $exception) {
            echo "Couldn't connect to database";
        }
    }

    public function addUser(string $username, string $password) {
        $params = array(
            self::$USERNAME_PARAM => $username,
            self::$PASSWORD_PARAM => $password
        );
        $this->db->prepare(self::$ADD_USER_QUERY)->execute($params);
    }

    public function userExists(string $username) : bool {
        $userExists = false;
        try {
            $this->findUser($username);
            $userExists = true;
        } catch (\Exception $e) {
        }

        return $userExists;
    }

    public function findUser (string $username) : array {
        return $this->fetchFromDbWith(self::$FIND_USER_QUERY, array(self::$USERNAME_PARAM => $username));
    }

    public function findUserWithCookie (string $username, string $cookie) : array {
        return $this->fetchFromDbWith(self::$FIND_COOKIE_QUERY, array(
            self::$USERNAME_PARAM => $username,
            self::$COOKIE_PARAM => $cookie
        ));
    }

    public function updateUserWithCookie (string $username, string $cookie) {
        $params = array(
            self::$USERNAME_PARAM => $username,
            self::$COOKIE_PARAM => $cookie
        );
        $this->db->prepare(self::$UPDATE_COOKIE_QUERY)->execute($params);
    }

    public function removeUserCookie (string $username) {
        $this->db->prepare(self::$REMOVE_COOKIE_QUERY)->execute(array(self::$USERNAME_PARAM => $username));
    }

    private function fetchFromDbWith ($query, $parameters) : array {
        $query = $this->db->prepare($query);
        $query->execute($parameters);
        $result = $query->fetch();

        if (!$result)
            throw new \Exception('Not found in DB');

        return $result;
    }

    public function setup () {
        $this->db->exec(self::$SCHEMA);
    }
}