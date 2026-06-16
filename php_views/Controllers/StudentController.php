<?php
require_once 'core/Controller.php';

class StudentController extends Core\Controller {
    public function dashboard() {
        $this->view('student/dashboard', ['title' => 'Dashboard Học viên']);
    }

    public function survey() {
        $this->view('student/survey', ['title' => 'Khảo sát lộ trình']);
    }

    public function checkout() {
        $this->view('student/checkout', ['title' => 'Thanh toán đặt lịch']);
    }

    public function schedule() {
        $this->view('student/schedule', ['title' => 'Thời khóa biểu']);
    }
}
