<?php

namespace auth\model;


class Users {
    private static $FIND_USER_QUERY = 'SELECT username, password FROM users WHERE username LIKE :username';
    private static $USERNAME_PARAM = 'username';
    private $db;

    public function __construct (\PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    public function findUser (string $username) : array {
        $query = $this->db->prepare(self::$FIND_USER_QUERY);
        $query->execute(array(self::$USERNAME_PARAM => $username));
        $user = $query->fetch();

        if (!$user)
            throw new \Exception("User not found");

        return $user;
    }
}