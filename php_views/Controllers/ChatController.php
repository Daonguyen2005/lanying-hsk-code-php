<?php
require_once 'core/Controller.php';

class ChatController extends Core\Controller
{
    public function index()
    {
        $this->view('chat/index');
    }
}
