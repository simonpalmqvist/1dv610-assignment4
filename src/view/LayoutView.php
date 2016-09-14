<?php


class LayoutView {
  
  public static function render(bool $isAuthenticated, LoginView $view, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . self::renderIsLoggedInMessage($isAuthenticated) . '
          
          <div class="container">
              ' . $view->show() . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private static function renderIsLoggedInMessage(bool $isAuthenticated) {
      return $isAuthenticated ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>';
  }
}
