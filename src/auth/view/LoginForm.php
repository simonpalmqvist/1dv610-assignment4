<?php

namespace auth\view;

interface LoginForm {
    public function generateHTML () : string;

    public function isPostRequest () : bool;

    public function getRequestUsername () : string;

    public function getRequestPassword () : string;

    public function getRequestKeep () : bool;

    public function setUsername (string $username);

    public function setMessageUsernameIsMissing ();

    public function setMessagePasswordIsMissing ();

    public function setMessageWrongUsernameOrPassword();

    public function setMessageInvalidCookies ();

    public function setMessageRegisteredUser ();

    public function setMessageLogout ();

}