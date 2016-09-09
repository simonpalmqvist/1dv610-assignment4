<?php


class LayoutView {
  
  public static function render(LoginView $view, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . self::renderIsLoggedInMessage() . '
          
          <div class="container">
              ' . $view->render() . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private static function renderIsLoggedInMessage() {
    if (model\Authentication::userIsAuthenticated()) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}
