<?php
require_once 'core/Controller.php';

class AuthController extends Core\Controller {
    public function login() {
        $this->view('auth/login');
    }
}
