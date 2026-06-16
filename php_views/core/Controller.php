<?php
namespace Core;

class Controller {
    protected function view($view, $data = []) {
        require_once 'core/View.php';
        View::render($view, $data);
    }
}
