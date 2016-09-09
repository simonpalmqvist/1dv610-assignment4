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

	private $message = '';

	public function render() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handlePost();
        }

        $response = $this->generateLoginFormHTML();

        if (\model\Authentication::userIsAuthenticated()) {
            $response = $this->generateLogoutButtonHTML();
        }

		return $response;
	}

	private function handlePost() {
	    if(\model\Authentication::userIsAuthenticated()) {
            \model\Authentication::logoutUser();
        } else {
            try {
                $this->validateFields();
                \model\Authentication::loginUser($this->getRequestUserName(), $this->getRequestPassword());
                $this->message = 'Welcome';
            } catch (\Exception $error) {
                $this->message = $error->getMessage();
            }
        }
    }

    private function validateFields() {
        if (empty($this->getRequestUserName())) {
            throw new \Exception("Username is missing");
        }

        if (empty($this->getRequestPassword())) {
            throw new \Exception("Password is missing");
        }
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
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	private function getRequestUserName() {
	    return isset($_POST[self::$name]) ? $_POST[self::$name] : '';
	}

    private function getRequestPassword() {
        return $_POST[self::$password];
    }
}