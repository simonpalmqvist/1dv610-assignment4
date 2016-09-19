<?php

namespace auth\view;

require_once('LogoutButton.php');

class DefaultLogoutButton implements LogoutButton  {
    private static $LOGOUT_BUTTON = 'LoginView::Logout';
    private static $MESSAGE_PARAGRAPH = 'LoginView::Message';

    private $message;

    public function __construct () {
        $this->message = array();
    }

    public function generateHTML () : string {
        return '
			<form  method="post" >
				<p id="' . self::$MESSAGE_PARAGRAPH . '">' . implode('<br/>', $this->message) .'</p>
				<input type="submit" name="' . self::$LOGOUT_BUTTON . '" value="logout"/>
			</form>
        ';
    }

    public function isPostRequest () : bool {
        return filter_has_var(INPUT_POST, self::$LOGOUT_BUTTON);
    }

    public function setWelcomeMessage(bool $rememberUser) {
        $this->message[] = $rememberUser ? 'Welcome and you will be remembered' : 'Welcome';
    }

    public function setWelcomeWithCookiesMessage() {
        $this->message[] = 'Welcome back with cookie';
    }

}