<?php

namespace auth\controller;

require_once(dirname(__FILE__) . '/../model/Registration.php');

use auth\view\RegistrationForm;
use auth\model\Registration;

class Register {
    private $model;
    private $form;

    public function __construct (\PDO $dbConnection, RegistrationForm $form) {
        $this->model = new Registration($dbConnection);
        $this->form = $form;
    }

    public function handleRequest () {

    }

    public function getHTMLToPresent () : string {
        return $this->form->generateHTML();
    }
}