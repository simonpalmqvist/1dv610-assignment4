<?php

namespace auth\view;

interface LoginForm {
    public function generateHTML () : string;

    public function isPostRequest () : bool;

    public function getRequestUsername () : string;

    public function getRequestPassword () : string;

    public function getRequestKeep () : string;

    public function setMessageUsernameIsMissing ();

}