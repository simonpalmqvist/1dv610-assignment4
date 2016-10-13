<?php

namespace view;

class Layout {

  private static $REGISTER = 'register';
  
  public static function render (bool $isAuthenticated, string $currentViewHTML, string $footerHTML) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 4</h1>
          ' . self::renderNavigation($isAuthenticated) .  '
          ' . self::renderIsLoggedInMessage($isAuthenticated) . '
          
          <div class="container">
              ' . $currentViewHTML . '
              
              ' . $footerHTML . '
          </div>
         </body>
      </html>
    ';
  }

  public static function getWantsToRegister () : bool {
      return filter_has_var(INPUT_GET, self::$REGISTER);
  }
  
  private static function renderIsLoggedInMessage (bool $isAuthenticated) : string {
      return $isAuthenticated ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>';
  }

  private static function renderNavigation (bool $isAuthenticated) : string {
      if ($isAuthenticated) {
          return '';
      }

      return self::getWantsToRegister() ?
          '<a href="?">Back to login</a>' :
          '<a href="?' . self::$REGISTER . '">Register a new user</a>';
  }
}
