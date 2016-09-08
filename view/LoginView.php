<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = '';
        $isAuthenticated = false;

        if ($this->isLoginAttempt()) {
            try {
                $this->validateFields();
                $isAuthenticated = true;
            } catch (Exception $error) {
                $message = $error->getMessage();
            }
            $this->getRequestUserName();
        }

        $response = $this->generateLoginFormHTML($message);

        if ($isAuthenticated) {
            $response = $this->generateLogoutButtonHTML($message);
        }

		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  string, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getUsername() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	private function validateFields() {
	    if (empty($this->getRequestUserName())) {
            throw new Exception("Username is missing");
        }

        if (empty($this->getRequestPassword())) {
            throw new Exception("Password is missing");
        }
    }

    private function getUsername() {
        $name = '';
        if ($this->isLoginAttempt()) {
            $name = $this->getRequestUserName();
        }
        return $name;
    }

	private function isLoginAttempt() {
	    return isset($_POST[self::$login]);
    }

	private function getRequestUserName() {
	    return $_POST[self::$name];
	}

    private function getRequestPassword() {
        return $_POST[self::$password];
    }
	
}