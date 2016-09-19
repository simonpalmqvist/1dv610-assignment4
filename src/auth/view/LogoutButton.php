<?php

namespace auth\view;

interface LogoutButton {
    public function generateHTML() : string;

    public function isPostRequest() : bool;

    public function setWelcomeMessage(bool $rememberUser); //  $remember ? 'Welcome and you will be remembered' : 'Welcome'

    public function setWelcomeWithCookiesMessage(); // Welcome back with cookie
}