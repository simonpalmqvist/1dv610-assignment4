<?php

namespace auth\view;

interface RegistrationForm {

    public function generateHTML () : string;

    public function isPostRequest () : bool;

    public function getRequestUserName () : string;

    public function getRequestPassword () : string;

    public function getRequestConfirmedPassword () : string;

    public function setMessageUsernameTooShort ();

    public function setMessageInvalidCharactersInUsername ();

    public function setMessageUsernameExists ();

    public function setMessagePasswordTooShort ();

    public function setMessagePasswordsDontMatch ();

}