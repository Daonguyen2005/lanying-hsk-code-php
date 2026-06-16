<?php
require_once 'core/Controller.php';

class TutorController extends Core\Controller {
    public function survey() {
        $this->view('tutor/survey');
    }

    public function dashboard() {
        $this->view('tutor/dashboard');
    }

    public function classes() {
        $this->view('tutor/classes');
    }
}
