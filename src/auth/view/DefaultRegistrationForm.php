<?php

namespace auth\view;

class DefaultRegistrationForm implements RegistrationForm {
    private static $REGISTER_BUTTON= 'RegisterView::register';
    private static $NAME_FIELD = 'RegisterView::UserName';
    private static $PASSWORD_FIELD = 'RegisterView::Password';
    private static $PASSWORD2_FIELD = 'RegisterView::PasswordRepeat';

    private $message;

    public function __construct () {
        $this->message = array();
    }


    public function generateHTML () : string {
        return '
            <h2>Register new user</h2>
			<form action="?register" method="post">
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="message">' . $this->message . '</p>
					
					<label for="' . self::$NAME_FIELD . '">Username :</label>
					<input type="text" id="' . self::$NAME_FIELD . '" name="' . self::$NAME_FIELD . '" value="' . $this->getRequestUsername() . '" />

					<label for="' . self::$PASSWORD_FIELD . '">Password :</label>
					<input type="password" id="' . self::$PASSWORD_FIELD . '" name="' . self::$PASSWORD_FIELD . '" />

					<label for="' . self::$PASSWORD2_FIELD . '">Repeat password :</label>
					<input type="password" id="' . self::$PASSWORD2_FIELD . '" name="' . self::$PASSWORD2_FIELD . '" />
					
					<input type="submit" name="' . self::$REGISTER_BUTTON . '" value="login" />
				</fieldset>
			</form>
        
        ';
    }

    public function isPostRequest () : bool {
        return filter_has_var(INPUT_POST, self::$REGISTER_BUTTON);
    }

    public function getRequestUsername () : string {
        return filter_input(INPUT_POST, self::$NAME_FIELD);
    }

    public function getRequestPassword () : string {
        return filter_input(INPUT_POST, self::$PASSWORD_FIELD, FILTER_SANITIZE_STRING);
    }

    public function getRequestConfirmedPassword () : string {
        // Not sanitized so password won't match if password is funky
        return filter_input(INPUT_POST, self::$PASSWORD2_FIELD);
    }

    public function setMessageUsernameTooShort () {
        $this->message[] = 'Username has too few characters, at least 3 characters.';
    }

    public function setMessageInvalidCharactersInUsername () {
        $this->message[] = 'Username contains invalid characters.';
    }

    public function setMessageUsernameExists () {
        $this->message[] = 'User exists, pick another username.';
    }

    public function setMessagePasswordTooShort () {
        $this->message[] = 'Password has too few characters, at least 6 characters.';
    }

    public function setMessagePasswordsDontMatch () {
        $this->message[] = 'Passwords do not match.';
    }

}