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

    public function getHTMLToPresent () : string {
        return $this->form->generateHTML();
    }

    public function handleRequest () {
        if ($this->isPostRequest()) {
            $this->tryRegisterUser();
        }
    }

    private function isPostRequest () {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST';
    }

    private function tryRegisterUser () {
        try {
            $this->registerUser();
        } catch (\UsernameAndPasswordTooShortException $e) {
            $this->form->setMessageUsernameTooShort();
            $this->form->setMessagePasswordTooShort();

        } catch (\UsernameTooShortException $e) {
            $this->form->setMessageUsernameTooShort();

        } catch (\PasswordTooShortException $e) {
            $this->form->setMessagePasswordTooShort();

        } catch (\UsernameContainsInvalidCharactersException $e) {
            $this->form->setMessageInvalidCharactersInUsername();

        } catch (\PasswordsDontMatchException $e) {
            $this->form->setMessagePasswordsDontMatch();
        }
    }

    private function registerUser () {
        $this->model->registerUser(
            $this->form->getRequestUsername(),
            $this->form->getRequestPassword(),
            $this->form->getRequestConfirmedPassword()
        );
    }
}