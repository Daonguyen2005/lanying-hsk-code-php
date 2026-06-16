<?php
require_once 'core/Controller.php';

class ScheduleController extends Core\Controller {
    public function index() {
        $this->view('schedule/index', ['title' => 'Thời khóa biểu - Lanying HSK']);
    }
}
