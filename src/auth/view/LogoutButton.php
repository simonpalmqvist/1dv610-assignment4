<?php

namespace auth\view;

interface LogoutButton {
    public function generateHTML () : string;

    public function isPostRequest () : bool;
}