<?php

namespace auth;

class Table {
    private static $SCHEMA = '
        CREATE TABLE users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) BINARY NOT NULL UNIQUE,
            password VARCHAR(255) BINARY NOT NULL,
            cookie VARCHAR(255) BINARY,
            create_date TIMESTAMP
        );
    ';

    public static function setup (\PDO $db) {
        $db->exec(self::$SCHEMA);
    }
}
