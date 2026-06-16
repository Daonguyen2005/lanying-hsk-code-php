<?php
require_once 'core/Controller.php';

class AdminController extends Core\Controller
{
    public function index()
    {
        $this->view('admin/dashboard');
    }
}
