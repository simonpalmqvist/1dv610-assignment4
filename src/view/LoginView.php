<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

    private $username = '';
	public $message = '';
    public $authenticated = false;

    public function generateHTML() {
        $view = $this->generateLoginFormHTML();

        if ($this->authenticated) {
            $view = $this->generateLogoutButtonHTML();
        }

        return $view;
    }

	public function show() {
        $view = $this->generateLoginFormHTML();

        if ($this->authenticated) {
            $view = $this->generateLogoutButtonHTML();
        }

		return $view;
	}

	private function generateLogoutButtonHTML() {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $this->message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	private function generateLoginFormHTML() {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $this->message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->username . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	public function setUsername (string $username) {
	    $this->username = $username;
    }

	public function getRequestUserName() {
	    return $this->getFormField(self::$name);
	}

    public function getRequestPassword() {
        return $this->getFormField(self::$password);
    }

    public function getRequestKeep() {
        return $this->getFormField(self::$keep);
    }

    public function isRequestLoginAttempt() {
        return isset($_POST[self::$login]);
    }
    
    public function isRequestLogoutAttempt() {
        return isset($_POST[self::$logout]);
    }

    private function getFormField($id) {
        return isset($_POST[$id]) ? $_POST[$id] : false;
    }
}