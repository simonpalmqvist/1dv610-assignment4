<?php


class Layout {
  
  public static function render(bool $isAuthenticated, string $currentViewHTML, string $footerHTML) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
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
  
  private static function renderIsLoggedInMessage(bool $isAuthenticated) : string {
      return $isAuthenticated ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>';
  }

  private static function renderNavigation(bool $isAuthenticated) : string {
      if ($isAuthenticated) {
          return '';
      }

      return filter_has_var(INPUT_GET, 'register') ?
          '<a href="?">Back to login</a>' :
          '<a href="?register">Register a new user</a>';
  }
}
