<?php

namespace auth\model;


class UserSession {
    private static $SESSION_NAME = 'logged_in_user';
    private static $SESSION_NEW_USER = 'registered_user';
    private static $HTTP_USER_AGENT = 'HTTP_USER_AGENT';
    private static $COOKIE_USERNAME = 'LoginView::CookieName';
    private static $COOKIE_PASSWORD = 'LoginView::CookiePassword';
    private static $COOKIE_VALID_FOR = 2592000; // 30 days

    public static function setWith (string $username) {
        session_regenerate_id();
        $_SESSION[self::$SESSION_NAME] = $username;
        $_SESSION[self::$HTTP_USER_AGENT] = self::getUserAgent();
    }

    public static function isActive () : bool {
        return isset($_SESSION[self::$SESSION_NAME]) && self::hasTheSameUserAgent();
    }

    public static function destroy () {
        unset($_SESSION[self::$SESSION_NAME]);
        unset($_SESSION[self::$HTTP_USER_AGENT]);
        self::removeCookie(self::$COOKIE_USERNAME);
        self::removeCookie(self::$COOKIE_PASSWORD);
        session_destroy();
    }

    public static function hasCookiesSet () {
        return isset($_COOKIE[self::$COOKIE_USERNAME]) && isset($_COOKIE[self::$COOKIE_PASSWORD]);
    }

    public static function setCookies (string $secret) {
        self::setCookie(self::$COOKIE_USERNAME, $_SESSION[self::$SESSION_NAME]);
        self::setCookie(self::$COOKIE_PASSWORD, $secret);
    }

    public static function getSessionUsername () : string {
        return $_SESSION[self::$SESSION_NAME];
    }

    public static function getCookieUsername () : string {
        return $_COOKIE[self::$COOKIE_USERNAME];
    }

    public static function getCookiePassword () : string {
        return $_COOKIE[self::$COOKIE_PASSWORD];
    }

    public static function hasNewlyRegisteredUsername () : bool {
        return isset($_SESSION[self::$SESSION_NEW_USER]);
    }

    public static function setNewlyRegisteredUsername ($username) {
        $_SESSION[self::$SESSION_NEW_USER] = $username;
    }

    public static function getNewlyRegisteredUsername () : string {
        $username = $_SESSION[self::$SESSION_NEW_USER];
        unset($_SESSION[self::$SESSION_NEW_USER]);
        return $username;
    }

    private static function setCookie ($name, $value) {
        setcookie($name, $value, time() + self::$COOKIE_VALID_FOR);
    }

    private static function removeCookie (string $name) {
        // Setting cookie date in the past to make it invalid
        setcookie($name, '', time() - self::$COOKIE_VALID_FOR);
    }

    private static function hasTheSameUserAgent () : bool {
        return $_SESSION[self::$HTTP_USER_AGENT] === self::getUserAgent();
    }

    private static function getUserAgent () : string {
        return filter_input(INPUT_SERVER, self::$HTTP_USER_AGENT);
    }
}