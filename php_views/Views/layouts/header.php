<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lanying HSK - Nền tảng gia sư tiếng Trung thông minh. AI gợi ý lộ trình học tập bằng Cosine Similarity, Chatbot RAG 24/7.">
    <title><?= isset($title) ? $title . ' | Lanying HSK' : 'Lanying HSK - Gia sư Tiếng Trung AI' ?></title>
    <!-- Modern Premium Font for Vietnamese -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/css/style.css?v=3">
</head>
<body>
    <!-- Background Blobs -->
    <div class="bg-blobs"></div>

    <!-- Toast -->
    <div class="toast-custom" id="toast"></div>

    <!-- Glass Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light glass-nav sticky-top py-3 animate-fade-up">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <span class="logo-icon">&#20013;</span>
                <h1 class="m-0 fs-4 fw-bold text-gradient">Lanying HSK</h1>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarNav">
                <ul class="navbar-nav ms-auto fw-semibold gap-3 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#" onclick="openSchedule(event)"><i class="bi bi-calendar3 me-1"></i>Thời khóa biểu</a></li>
                    <li class="nav-item" id="nav-tutors-item"><a class="nav-link" href="/#giasu"><i class="bi bi-people me-1"></i>Danh sách Gia sư</a></li>
                    <li class="nav-item" id="nav-my-classes-item" style="display:none;"><a class="nav-link" href="/tutor/classes"><i class="bi bi-journal-bookmark me-1"></i>Lớp học của tôi</a></li>
                    <li class="nav-item" id="nav-ai-item"><a class="nav-link" href="/student/survey"><i class="bi bi-robot me-1"></i>Khảo sát AI</a></li>
                    <li class="nav-item ms-lg-3" id="nav-login-btn">
                        <a class="btn btn-primary-custom rounded-pill px-4" href="/auth/login"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
