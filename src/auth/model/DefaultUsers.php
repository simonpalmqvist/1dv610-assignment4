<?php

namespace auth\model;

require_once('Users.php');
require_once('User.php');

class DefaultUsers implements Users {
    private static $USERNAME_PARAM = 'username';
    private static $PASSWORD_PARAM = 'password';
    private static $COOKIE_PARAM = 'cookie';

    private $setupQuery;
    private $addUserQuery;
    private $findUserQuery;
    private $findCookieQuery;
    private $updateCookieQuery;

    private $db;

    public function __construct (\PDO $db) {
        $this->db = $db;
        $this->initQueries();
    }

    private function initQueries () {
        $this->setupQuery = '
            CREATE TABLE users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                ' . self::$USERNAME_PARAM . ' VARCHAR(30) BINARY NOT NULL UNIQUE,
                ' . self::$PASSWORD_PARAM . ' VARCHAR(255) BINARY NOT NULL,
                ' . self::$COOKIE_PARAM . ' VARCHAR(255) BINARY,
                create_date TIMESTAMP
            );
        ';

        $this->addUserQuery = '
            INSERT INTO users (' . self::$USERNAME_PARAM . ', ' . self::$PASSWORD_PARAM . ') 
            VALUES (:' . self::$USERNAME_PARAM . ', :' . self::$PASSWORD_PARAM . ')
        ';

        $this->findUserQuery = '
            SELECT 
                ' . self::$USERNAME_PARAM . ', ' . self::$PASSWORD_PARAM . ' 
            FROM 
                users 
            WHERE 
                ' . self::$USERNAME_PARAM . ' LIKE :' . self::$USERNAME_PARAM . '
        ';

        $this->findCookieQuery = '
            SELECT 
                ' . self::$USERNAME_PARAM . ', ' . self::$PASSWORD_PARAM . ' 
            FROM 
                users 
            WHERE 
                ' . self::$USERNAME_PARAM . ' LIKE :' . self::$USERNAME_PARAM . ' AND 
                ' . self::$COOKIE_PARAM . ' LIKE :' . self::$COOKIE_PARAM . '
        ';

        $this->updateCookieQuery = '
            UPDATE 
                users 
            SET 
                ' . self::$COOKIE_PARAM . ' = :' . self::$COOKIE_PARAM . ' 
            WHERE 
                ' . self::$USERNAME_PARAM . ' LIKE :' . self::$USERNAME_PARAM . '
        ';
    }

    public function addUser (string $username, string $password) {
        $params = array(
            self::$USERNAME_PARAM => $username,
            self::$PASSWORD_PARAM => $password
        );
        $this->db->prepare($this->addUserQuery)->execute($params);
    }

    public function userExists (string $username) : bool {
        $userExists = false;
        try {
            $this->findUser($username);
            $userExists = true;
        } catch (\Exception $e) {
        }

        return $userExists;
    }

    public function findUser (string $username) : User {
        return $this->fetchFromDbWith($this->findUserQuery, array(self::$USERNAME_PARAM => $username));
    }

    public function findUserWithCookie (string $username, string $cookie) : User {
        return $this->fetchFromDbWith($this->findCookieQuery, array(
            self::$USERNAME_PARAM => $username,
            self::$COOKIE_PARAM => $cookie
        ));
    }

    public function updateUserWithCookie (string $username, string $cookie) {
        $params = array(
            self::$USERNAME_PARAM => $username,
            self::$COOKIE_PARAM => $cookie
        );
        $this->db->prepare($this->updateCookieQuery)->execute($params);
    }

    public function removeUserCookie (string $username) {
        $params = array(
            self::$USERNAME_PARAM => $username,
            self::$COOKIE_PARAM => null
        );
        $this->db->prepare($this->updateCookieQuery)->execute($params);
    }

    private function fetchFromDbWith ($query, $parameters) : User {
        $query = $this->db->prepare($query);
        $query->execute($parameters);
        $result = $query->fetch();

        if (!$result)
            throw new \Exception('Not found in DB');

        return new User($result[self::$USERNAME_PARAM], $result[self::$PASSWORD_PARAM]);
    }

    public function setup () {
        $this->db->exec($this->setupQuery);
    }

}