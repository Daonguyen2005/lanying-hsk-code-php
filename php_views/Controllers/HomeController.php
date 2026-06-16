<?php
require_once 'core/Controller.php';

class HomeController extends Core\Controller {
    public function index() {
        $this->view('layouts/header', ['title' => 'Lanying HSK - Nền tảng gia sư thông minh']);
        $this->view('home/index');
        $this->view('layouts/footer');
    }
}
