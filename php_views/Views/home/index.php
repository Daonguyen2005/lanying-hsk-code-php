    <!-- Hero Section -->
    <section class="hero-section container-fluid px-0">
        <div class="hero-inner container py-5">
            <div class="row align-items-center min-vh-80 gy-5">
                <div class="col-lg-6 text-center text-lg-start animate-fade-up delay-100">
                    <span class="badge hero-badge rounded-pill px-4 py-2 mb-4 d-inline-flex align-items-center gap-2">
                        <span class="badge-dot"></span>
                        #1 Nền tảng Gia sư Tiếng Trung AI tại Việt Nam
                    </span>
                    <h1 class="hero-title display-4 fw-bold mb-4 text-dark lh-sm">
                        Chinh phục HSK<br>Cùng Gia sư 
                        <span class="text-gradient">Đỉnh Cao</span>
                        <br><span class="text-gradient">AI Gợi ý</span> Lộ trình
                    </h1>
                    <p class="lead text-secondary mb-5 fs-5">
                        Hệ thống AI thông minh dùng <strong>Cosine Similarity</strong> phân tích hồ sơ và gợi ý gia sư chính xác nhất.
                        Tích hợp <strong>Chatbot RAG</strong> hỗ trợ 24/7.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="/student/survey" class="btn btn-primary-custom btn-lg px-5 shadow-lg" id="cta-survey">
                            <i class="bi bi-robot me-2"></i>Khảo sát & Tìm Gia sư ngay
                        </a>
                        <a href="#giasu" class="btn btn-outline-custom btn-lg px-5" id="cta-tutors">
                            <i class="bi bi-people me-2"></i>Xem danh sách Gia sư
                        </a>
                    </div>
                    <!-- Trust Indicators -->
                    <div class="d-flex flex-wrap gap-4 mt-5 justify-content-center justify-content-lg-start">
                        <div class="d-flex align-items-center gap-2">
                            <div class="trust-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <span class="fw-semibold text-dark">20+ Gia sư HSK 5-6</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="trust-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px">
                                <i class="bi bi-cpu"></i>
                            </div>
                            <span class="fw-semibold text-dark">AI Cosine Similarity</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="trust-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px">
                                <i class="bi bi-chat-dots"></i>
                            </div>
                            <span class="fw-semibold text-dark">Chatbot RAG 24/7</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center animate-fade-up delay-300">
                    <div class="hero-image-wrapper position-relative d-inline-block">
                        <!-- Floating Cards -->
                        <div class="floating-card glass-panel-sm p-3 position-absolute top-0 start-0 translate-middle shadow d-none d-lg-flex align-items-center gap-2" style="z-index:2; border-radius:16px;">
                            <div class="bg-success rounded-circle flex-shrink-0" style="width:10px;height:10px;"></div>
                            <span class="fw-bold small">150+ Gia sư online</span>
                        </div>
                        <div class="floating-card glass-panel-sm p-3 position-absolute bottom-0 end-0 translate-middle-end shadow d-none d-lg-flex align-items-center gap-2" style="z-index:2; border-radius:16px;">
                            <i class="bi bi-star-fill text-warning"></i>
                            <span class="fw-bold small">4.9/5 Học viên hài lòng</span>
                        </div>
                        <div class="floating-card glass-panel-sm p-3 position-absolute top-50 end-0 translate-middle-y shadow d-none d-lg-flex flex-column align-items-center" style="z-index:2; border-radius:16px; right: -20px !important;">
                            <span class="fw-bold text-primary fs-4">93%</span>
                            <span class="small text-secondary">Vượt HSK</span>
                        </div>
                        <img src="/public/images/hero.png?v=<?= time() + 1 ?>"
                             alt="Học tiếng Trung cùng gia sư Lanying HSK"
                             class="hero-img img-fluid rounded-5 shadow-xl"
                             style="object-fit:contain; background-color:#ffffff; height:480px; width:100%; border:4px solid white; padding: 10px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="glass-panel p-4 p-md-5">
                <div class="row g-4 text-center">
                    <div class="col-6 col-md-3">
                        <div class="stat-num text-gradient fw-bold" style="font-size:2.5rem;" id="stat-tutors">20+</div>
                        <div class="text-secondary fw-semibold">Gia sư HSK 5-6</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-num text-gradient fw-bold" style="font-size:2.5rem;" id="stat-students">500+</div>
                        <div class="text-secondary fw-semibold">Học viên tin dùng</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-num text-gradient fw-bold" style="font-size:2.5rem;">93%</div>
                        <div class="text-secondary fw-semibold">Vượt HSK lần đầu</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-num text-gradient fw-bold" style="font-size:2.5rem;">4.9 ⭐</div>
                        <div class="text-secondary fw-semibold">Đánh giá trung bình</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-section container py-5 mt-2">
        <div class="text-center mb-5 animate-fade-up">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-4 py-2 fw-semibold mb-3 d-inline-block">Quy trình</span>
            <h2 class="fw-bold display-6 mb-3">Cách hoạt động <span class="text-gradient">AI thông minh</span></h2>
            <p class="text-secondary fs-5 col-lg-7 mx-auto">Chỉ 3 bước đơn giản để tìm ra gia sư tiếng Trung phù hợp nhất với bạn</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4 animate-fade-up delay-100">
                <div class="glass-panel p-4 text-center h-100">
                    <div class="how-icon mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:linear-gradient(135deg,#3b82f6,#0ea5e9);font-size:2rem;color:white;">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <div class="badge bg-primary rounded-pill mb-2">Bước 1</div>
                    <h4 class="fw-bold mb-3">Làm Khảo sát Lộ trình</h4>
                    <p class="text-secondary">Trả lời 9 câu hỏi thông minh về trình độ, mục tiêu HSK, ngân sách và thời gian học của bạn.</p>
                </div>
            </div>
            <div class="col-md-4 animate-fade-up delay-200">
                <div class="glass-panel p-4 text-center h-100">
                    <div class="how-icon mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:linear-gradient(135deg,#8b5cf6,#6366f1);font-size:2rem;color:white;">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <div class="badge bg-primary rounded-pill mb-2">Bước 2</div>
                    <h4 class="fw-bold mb-3">AI Phân tích Cosine Similarity</h4>
                    <p class="text-secondary">Hệ thống tính toán 28 chiều vector để tìm gia sư khớp nhất với hồ sơ học viên (độ chính xác 99%).</p>
                </div>
            </div>
            <div class="col-md-4 animate-fade-up delay-300">
                <div class="glass-panel p-4 text-center h-100">
                    <div class="how-icon mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:linear-gradient(135deg,#f59e0b,#ef4444);font-size:2rem;color:white;">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="badge bg-primary rounded-pill mb-2">Bước 3</div>
                    <h4 class="fw-bold mb-3">Kết nối & Bắt đầu học</h4>
                    <p class="text-secondary">Nhận gợi ý TOP 3 gia sư phù hợp nhất, đặt lịch và bắt đầu hành trình chinh phục HSK ngay hôm nay!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tutors Section -->
    <section id="giasu" class="container py-5 mt-2">
        <div class="text-center mb-5 animate-fade-up delay-100">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-4 py-2 fw-semibold mb-3 d-inline-block">Gia sư nổi bật</span>
            <h2 class="fw-bold display-6 mb-3">Gia sư <span class="text-gradient">Tiêu biểu</span></h2>
            <p class="text-secondary fs-5 col-lg-6 mx-auto">Lựa chọn từ mạng lưới gia sư chất lượng cao, đều đạt HSK 5-6 của Lanying HSK</p>
        </div>
        <!-- Filter Bar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex flex-wrap justify-content-center gap-2 flex-grow-1">
                <button class="btn btn-primary rounded-pill px-4 tutor-filter active" data-filter="all">Tất cả</button>
                <button class="btn btn-outline-primary rounded-pill px-4 tutor-filter" data-filter="hsk">Luyện thi HSK</button>
                <button class="btn btn-outline-primary rounded-pill px-4 tutor-filter" data-filter="giao tiep">Giao tiếp</button>
                <button class="btn btn-outline-primary rounded-pill px-4 tutor-filter" data-filter="thuong mai">Thương mại</button>
                <button class="btn btn-outline-primary rounded-pill px-4 tutor-filter" data-filter="tre em">Trẻ em</button>
            </div>
            <div class="search-box" style="min-width: 250px;">
                <div class="input-group shadow-sm rounded-pill overflow-hidden">
                    <span class="input-group-text bg-white border-0 text-primary ps-3"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0 bg-white shadow-none" id="tutorSearchInput" placeholder="Tìm tên gia sư..." onkeyup="searchTutor(this.value)">
                </div>
            </div>
        </div>
        <div class="row g-4" id="tutorList">
            <!-- Tutor cards will be injected here -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                <p class="text-secondary mt-3">Đang tải danh sách gia sư...</p>
            </div>
        </div>
        <div id="tutorPagination" class="d-flex justify-content-center mt-4 gap-2 flex-wrap"></div>
        
        <div class="text-center mt-5">
            <a href="/student/survey" class="btn btn-primary-custom btn-lg px-5">
                <i class="bi bi-robot me-2"></i>Để AI gợi ý gia sư phù hợp nhất →
            </a>
        </div>
    </section>

    <!-- Learning Path Section (Lộ trình) -->
    <section class="path-section py-5 mt-2" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-4 py-2 fw-semibold mb-3 d-inline-block">Lộ trình</span>
                <h2 class="fw-bold display-6 mb-3">Lộ trình học <span class="text-gradient">Tiếng Trung HSK</span></h2>
                <p class="text-secondary fs-5 col-lg-6 mx-auto">Từ người mới bắt đầu đến trình độ chuyên sâu HSK 6 - Mỗi cấp độ đều có gia sư chuyên biệt</p>
            </div>
            <div class="row g-3 align-items-stretch">
                <div class="col-md-2 col-4">
                    <div class="glass-panel p-3 text-center h-100" style="border-top: 4px solid #6ee7b7;">
                        <div class="fw-bold fs-5 text-success">HSK 1-2</div>
                        <div class="text-secondary small mt-1">Cơ bản<br>3-6 tháng</div>
                        <div class="mt-2"><span class="badge bg-success-subtle text-success rounded-pill">Mới bắt đầu</span></div>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="glass-panel p-3 text-center h-100" style="border-top: 4px solid #93c5fd;">
                        <div class="fw-bold fs-5 text-primary">HSK 3-4</div>
                        <div class="text-secondary small mt-1">Trung cấp<br>6-12 tháng</div>
                        <div class="mt-2"><span class="badge bg-primary-subtle text-primary rounded-pill">Phổ biến</span></div>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="glass-panel p-3 text-center h-100" style="border-top: 4px solid #fca5a5;">
                        <div class="fw-bold fs-5 text-danger">HSK 5</div>
                        <div class="text-secondary small mt-1">Nâng cao<br>12-18 tháng</div>
                        <div class="mt-2"><span class="badge bg-danger-subtle text-danger rounded-pill">Chuyên sâu</span></div>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="glass-panel p-3 text-center h-100" style="border-top: 4px solid #d8b4fe;">
                        <div class="fw-bold fs-5" style="color:#8b5cf6">HSK 6</div>
                        <div class="text-secondary small mt-1">Thành thạo<br>18-24 tháng</div>
                        <div class="mt-2"><span class="badge rounded-pill" style="background:#f3e8ff;color:#8b5cf6">Đỉnh cao</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-panel p-4 h-100 text-center d-flex flex-column align-items-center justify-content-center" style="border-top: 4px solid #fbbf24; background: linear-gradient(135deg, rgba(251,191,36,0.1), rgba(245,158,11,0.1))">
                        <i class="bi bi-robot fs-1 text-warning mb-3"></i>
                        <h5 class="fw-bold mb-2">AI gợi ý lộ trình cá nhân hóa</h5>
                        <p class="text-secondary small mb-3">Chatbot RAG phân tích và tạo ra lộ trình học tập phù hợp 100% với bạn</p>
                        <a href="/student/survey" class="btn btn-warning fw-bold rounded-pill px-4">Khảo sát ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section container py-5 mt-2">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-4 py-2 fw-semibold mb-3 d-inline-block">Tính năng</span>
            <h2 class="fw-bold display-6 mb-3">Tại sao chọn <span class="text-gradient">Lanying HSK?</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="glass-panel p-4 h-100 text-center">
                    <div class="feature-icon mb-3 mx-auto" style="width:64px;height:64px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem">
                        🤖
                    </div>
                    <h5 class="fw-bold mb-2">Chatbot RAG AI</h5>
                    <p class="text-secondary small">Hỏi đáp tiếng Trung 24/7, tư vấn lộ trình bằng AI thông minh được huấn luyện chuyên sâu</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="glass-panel p-4 h-100 text-center">
                    <div class="feature-icon mb-3 mx-auto" style="width:64px;height:64px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem">
                        🎯
                    </div>
                    <h5 class="fw-bold mb-2">Cosine Similarity</h5>
                    <p class="text-secondary small">Gợi ý gia sư chính xác dựa trên 28 chiều vector từ hồ sơ học viên và gia sư</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="glass-panel p-4 h-100 text-center">
                    <div class="feature-icon mb-3 mx-auto" style="width:64px;height:64px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem">
                        📅
                    </div>
                    <h5 class="fw-bold mb-2">Lịch học Trực quan</h5>
                    <p class="text-secondary small">Thời khóa biểu đẹp mắt, dễ quản lý với màu sắc phân biệt từng khóa học</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="glass-panel p-4 h-100 text-center">
                    <div class="feature-icon mb-3 mx-auto" style="width:64px;height:64px;background:linear-gradient(135deg,#fef9c3,#fef08a);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem">
                        💬
                    </div>
                    <h5 class="fw-bold mb-2">Nhắn tin Realtime</h5>
                    <p class="text-secondary small">Kết nối trực tiếp học viên và gia sư qua hệ thống chat nội bộ an toàn, bảo mật</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5 mt-2" style="background: linear-gradient(135deg, #faf5ff 0%, #ede9fe 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-4 py-2 fw-semibold mb-3 d-inline-block">Phản hồi</span>
                <h2 class="fw-bold display-6 mb-3">Học viên <span class="text-gradient">nói gì?</span></h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="glass-panel p-4 h-100">
                        <div class="d-flex gap-1 mb-3">⭐⭐⭐⭐⭐</div>
                        <p class="text-secondary fst-italic mb-4">"AI gợi ý gia sư cực kỳ chính xác! Sau 3 tháng học với gia sư Lanying HSK, tôi đã vượt HSK 4 lần đầu tiên."</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:44px;height:44px;">N</div>
                            <div>
                                <div class="fw-bold">Nguyễn Thị Hoa</div>
                                <div class="text-secondary small">Sinh viên, TP.HCM</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-panel p-4 h-100">
                        <div class="d-flex gap-1 mb-3">⭐⭐⭐⭐⭐</div>
                        <p class="text-secondary fst-italic mb-4">"Chatbot AI giải đáp ngữ pháp nhanh lắm, không cần đợi gia sư. Lộ trình học được cá nhân hóa hoàn toàn!"</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width:44px;height:44px;">T</div>
                            <div>
                                <div class="fw-bold">Trần Minh Tuấn</div>
                                <div class="text-secondary small">Kỹ sư, Hà Nội</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-panel p-4 h-100">
                        <div class="d-flex gap-1 mb-3">⭐⭐⭐⭐⭐</div>
                        <p class="text-secondary fst-italic mb-4">"Hệ thống đặt lịch rất tiện, quản lý thời khóa biểu đẹp. Gia sư chuyên nghiệp, tận tâm."</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center fw-bold" style="width:44px;height:44px;">L</div>
                            <div>
                                <div class="fw-bold">Lê Thu Hương</div>
                                <div class="text-secondary small">Người đi làm, Đà Nẵng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section container py-5 mt-2">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-4 py-2 fw-semibold mb-3 d-inline-block">FAQ</span>
            <h2 class="fw-bold display-6 mb-3">Câu hỏi <span class="text-gradient">thường gặp</span></h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-flush" id="faqAccordion">
                    <div class="accordion-item glass-panel mb-3 overflow-hidden border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Làm thế nào AI gợi ý gia sư phù hợp?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Hệ thống dùng thuật toán <strong>Cosine Similarity</strong> để tính toán độ tương đồng giữa 28 chiều vector hồ sơ học viên (trình độ, mục tiêu, ngân sách, lịch...) và gia sư, sau đó gợi ý TOP 3 phù hợp nhất.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item glass-panel mb-3 overflow-hidden border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Chatbot RAG hoạt động như thế nào?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                <strong>RAG (Retrieval-Augmented Generation)</strong> là kỹ thuật kết hợp tra cứu cơ sở dữ liệu gia sư thực tế với AI tạo sinh. Chatbot có thể trả lời câu hỏi ngữ pháp, tìm gia sư, và tư vấn lộ trình học tập theo thời gian thực.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item glass-panel mb-3 overflow-hidden border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Gia sư trên Lanying HSK có uy tín không?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Tất cả gia sư đều <strong>bắt buộc phải có chứng chỉ HSK 5 hoặc HSK 6</strong> (tương đương C1-C2). Hồ sơ được Admin xét duyệt nghiêm ngặt trước khi được hiển thị công khai.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item glass-panel mb-3 overflow-hidden border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Học phí trung bình là bao nhiêu?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Học phí dao động từ <strong>150.000đ - 350.000đ/buổi</strong> tùy gia sư và hình thức học. Học online thường rẻ hơn 20-30% so với offline.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 mt-2 mb-5">
        <div class="container">
            <div class="glass-panel p-5 text-center" style="background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(139,92,246,0.1));">
                <div class="mb-4 fs-1">🚀</div>
                <h2 class="fw-bold display-6 mb-3">Sẵn sàng chinh phục HSK?</h2>
                <p class="text-secondary fs-5 col-lg-6 mx-auto mb-5">Tham gia ngay hôm nay và để AI tìm gia sư hoàn hảo cho bạn trong vòng 60 giây!</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="/auth/login" class="btn btn-primary-custom btn-lg px-5" id="cta-register">
                        <i class="bi bi-person-plus me-2"></i>Đăng ký miễn phí
                    </a>
                    <a href="/student/survey" class="btn btn-outline-custom btn-lg px-5" id="cta-survey-2">
                        <i class="bi bi-robot me-2"></i>Khảo sát AI ngay
                    </a>
                </div>
            </div>
        </div>
    </section>

<script>
    // Tutor filter functionality
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns = document.querySelectorAll('.tutor-filter');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'btn-primary');
                    b.classList.add('btn-outline-primary');
                });
                btn.classList.add('active', 'btn-primary');
                btn.classList.remove('btn-outline-primary');
                
                const filter = btn.dataset.filter;
                filterTutors(filter);
            });
        });
    });

    function filterTutors(filter) {
        if (typeof window.renderTutorsPage === 'function') {
            window.currentTutorFilter = filter === 'all' ? 'all' : filter;
            window.currentTutorPage = 1;
            window.renderTutorsPage();
        }
    }

    function searchTutor(value) {
        if (typeof window.renderTutorsPage === 'function') {
            window.currentTutorSearch = value.trim().toLowerCase();
            window.currentTutorPage = 1;
            window.renderTutorsPage();
        }
    }

    // Animated counter
    function animateCounter(el, target) {
        let start = 0;
        const step = target / 50;
        const interval = setInterval(() => {
            start = Math.min(start + step, target);
            el.textContent = Math.round(start) + '+';
            if (start >= target) clearInterval(interval);
        }, 30);
    }
</script>

