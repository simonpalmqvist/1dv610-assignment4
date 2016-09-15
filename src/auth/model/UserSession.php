<?php

namespace auth\model;


class UserSession {
    private static $SESSION_NAME = 'logged_in_user';
    private static $COOKIE_USERNAME = 'LoginView::CookieName';
    private static $COOKIE_PASSWORD = 'LoginView::CookiePassword';
    private static $COOKIE_VALID_FOR = 2592000; // 30 days

    public static function setWith (string $username, string $hashedPassword, bool $shouldSetCookie) {
        $_SESSION[self::$SESSION_NAME] = $username;
        $_SESSION['HTTP_USER_AGENT'] = self::getUserAgent();

        if ($shouldSetCookie) {
            self::setCookie(self::$COOKIE_USERNAME, $username);
            self::setCookie(self::$COOKIE_PASSWORD, $hashedPassword);
        }
    }

    public static function setWithCookies () {
        self::setWith($_COOKIE[self::$COOKIE_USERNAME], $_COOKIE[self::$COOKIE_PASSWORD], false);
    }

    public static function destroy () {
        unset($_SESSION[self::$SESSION_NAME]);
        unset($_SESSION['HTTP_USER_AGENT']);
        self::removeCookie(self::$COOKIE_USERNAME);
        self::removeCookie(self::$COOKIE_PASSWORD);
    }

    public static function isActive () : bool {
        return isset($_SESSION[self::$SESSION_NAME]) && self::hasTheSameUserAgent();
    }

    public static function hasValidCookies () {
        return isset($_COOKIE[self::$COOKIE_USERNAME]) && isset($_COOKIE[self::$COOKIE_PASSWORD]);
    }

    private static function setCookie ($name, $value) {
        setcookie($name, $value, time() + self::$COOKIE_VALID_FOR);
    }

    private static function removeCookie (string $name) {
        // Setting cookie date in the past to make it invalid
        setcookie($name, '', time() - self::$COOKIE_VALID_FOR);
    }

    private static function hasTheSameUserAgent () : bool {
        return $_SESSION['HTTP_USER_AGENT'] === self::getUserAgent();
    }

    private static function getUserAgent () : string {
        return filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
    }
}