<?php

namespace auth\view;

require_once('LoginForm.php');

class DefaultLoginForm implements LoginForm  {
    private static $LOGIN_BUTTON= 'LoginView::login';
    private static $NAME_FIELD = 'LoginView::UserName';
    private static $PASSWORD_FIELD = 'LoginView::Password';
    private static $KEEP_FIELD = 'LoginView::KeepMeLoggedIn';
    private static $MESSAGE_PARAGRAPH = 'LoginView::Message';

    private $message;
    private $username = '';

    public function __construct () {
        $this->message = array();
    }

    public function generateHTML () : string {
        return '
            <form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$MESSAGE_PARAGRAPH . '">' . implode('<br/>', $this->message) . '</p>
					
					<label for="' . self::$NAME_FIELD . '">Username :</label>
					<input type="text" id="' . self::$NAME_FIELD . '" name="' . self::$NAME_FIELD . '" value="' . $this->username . '" />

					<label for="' . self::$PASSWORD_FIELD . '">Password :</label>
					<input type="password" id="' . self::$PASSWORD_FIELD . '" name="' . self::$PASSWORD_FIELD . '" />

					<label for="' . self::$KEEP_FIELD . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$KEEP_FIELD . '" name="' . self::$KEEP_FIELD . '" />
					
					<input type="submit" name="' . self::$LOGIN_BUTTON . '" value="login" />
				</fieldset>
			</form>
        ';
    }

    public function isPostRequest () : bool {
        return filter_has_var(INPUT_POST, self::$LOGIN_BUTTON);
    }

    public function setUsername (string $username) {
        $this->username = $username;
    }

    public function getRequestUsername () : string {
        return filter_input(INPUT_POST, self::$NAME_FIELD, FILTER_SANITIZE_STRING, array(
            'options' => array(
                'default' => ''
            )
        ));
    }

    public function getRequestPassword () : string {
        return filter_input(INPUT_POST, self::$PASSWORD_FIELD, FILTER_SANITIZE_STRING);
    }

    public function getRequestKeep() : bool {
        return filter_input(INPUT_POST, self::$KEEP_FIELD, FILTER_VALIDATE_BOOLEAN, array(
            'options' => array (
                'default' => false
            )
        ));
    }

    public function setMessageUsernameIsMissing () {
        $this->message[] = 'Username is missing';
    }

    public function setMessagePasswordIsMissing () {
        $this->message[] = 'Password is missing';
    }

    public function setMessageWrongUsernameOrPassword() {
        $this->message[] = 'Wrong name or password';
    }

    public function setMessageInvalidCookies () {
        $this->message[] = 'Wrong information in cookies';
    }

    public function setMessageRegisteredUser () {
        $this->message[] = 'Registered new user.';
    }

    public function setMessageLogout () {
        $this->message[] = 'Bye bye!';
    }
}