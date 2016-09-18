<?php


class LayoutView {
  
  public static function render(bool $isAuthenticated, string $currentViewHTML, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . self::renderNavigation() .  '
          ' . self::renderIsLoggedInMessage($isAuthenticated) . '
          
          <div class="container">
              ' . $currentViewHTML . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private static function renderIsLoggedInMessage(bool $isAuthenticated) {
      return $isAuthenticated ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>';
  }

  private static function renderNavigation() {
      return '<a href="?register">Register a new user</a>';
  }
}
