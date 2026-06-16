<?php include 'Views/layouts/header.php'; ?>

<style>
    .survey-option {
        transition: all 0.2s ease;
        border: 2px solid #e5e7eb;
        background: #ffffff;
        cursor: pointer;
    }
    .survey-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-color: #3b82f6; 
    }
    .survey-option input {
        display: none;
    }
    
    /* Active State for Radio */
    .survey-option:has(input[type="radio"]:checked) {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .survey-option:has(input[type="radio"]:checked) .circle-check {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    .survey-option:has(input[type="radio"]:checked) .circle-check::after {
        content: '';
        display: block;
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        margin: auto;
        margin-top: 4px;
    }
    .circle-check {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        display: inline-block;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    /* Active State for Checkbox */
    .survey-option:has(input[type="checkbox"]:checked) {
        border-color: #10b981; /* Emerald for multi-select */
        background-color: #ecfdf5;
    }
    .survey-option:has(input[type="checkbox"]:checked) .square-check {
        background-color: #10b981;
        border-color: #10b981;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd'/%3E%3C/svg%3E");
    }
    .square-check {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        display: inline-block;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    /* Hover match outline */
    .survey-option:hover .circle-check { border-color: #3b82f6; }
    .survey-option:hover .square-check { border-color: #10b981; }

    .btn-disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>

<div class="container mt-5 py-5 min-vh-100 animate-fade-up">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-soft border-0 rounded-4 overflow-hidden mb-4" id="surveyContainer">
                <div class="card-body p-4 p-md-5">
                    <h2 class="fw-bold text-gradient mb-3 text-center">Khảo sát lộ trình học tập</h2>
                    <p class="text-secondary text-center mb-4">Hệ thống AI sẽ phân tích nhu cầu của bạn để gợi ý lộ trình và Gia sư phù hợp nhất.</p>

                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 10px; border-radius: 10px; background-color: #f3f4f6;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" id="surveyProgress" role="progressbar" style="width: 0%; border-radius: 10px;"></div>
                    </div>
                    <div class="text-end mb-4 fw-bold text-primary" id="progressText">0%</div>

                    <form id="studentSurveyForm">
                        
                        <!-- Step 1: Trình độ -->
                        <div class="survey-step" data-step="1">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">1. Trình độ tiếng Trung hiện tại của bạn?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="current_level" value="beginner">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Mới bắt đầu</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="current_level" value="hsk1">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">HSK 1 - 2</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="current_level" value="hsk3">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">HSK 3 - 4</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="current_level" value="hsk5">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">HSK 5 - 6</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 2: Mục tiêu học tập -->
                        <div class="survey-step d-none" data-step="2">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">2. Mục tiêu học tập chính của bạn là gì? <span class="text-secondary fs-6 fw-normal">(Có thể chọn nhiều)</span></h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="goals" value="hsk_exam">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Luyện thi HSK/TOCFL</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="goals" value="communication">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Giao tiếp phản xạ</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="goals" value="business">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Tiếng Trung Thương mại</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="goals" value="kids">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Dành cho trẻ em</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 3: Kỹ năng -->
                        <div class="survey-step d-none" data-step="3">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">3. Kỹ năng nào bạn muốn cải thiện nhất? <span class="text-secondary fs-6 fw-normal">(Có thể chọn nhiều)</span></h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="skills" value="listening_speaking">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Nghe - Nói</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="skills" value="pronunciation">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Phát âm chuẩn</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="skills" value="reading_writing">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Đọc - Viết (Chữ Hán)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="skills" value="grammar">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Ngữ pháp</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 4: Điểm yếu -->
                        <div class="survey-step d-none" data-step="4">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">4. Điểm yếu lớn nhất hiện tại của bạn là gì? <span class="text-secondary fs-6 fw-normal">(Có thể chọn nhiều)</span></h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="weaknesses" value="vocab">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Nhớ chữ Hán chậm / Kém từ vựng</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="weaknesses" value="communication">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Sợ giao tiếp / Phản xạ chậm</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="weaknesses" value="grammar">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Sai cấu trúc ngữ pháp</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="weaknesses" value="pronunciation">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Sai thanh điệu / Phát âm</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 5: Hình thức học -->
                        <div class="survey-step d-none" data-step="5">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">5. Hình thức học mong muốn? <span class="text-secondary fs-6 fw-normal">(Có thể chọn nhiều)</span></h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="modes" value="online">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Học Online (Meet, Zoom)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="modes" value="offline">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Học Trực tiếp (Offline)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 6: Khung giờ -->
                        <div class="survey-step d-none" data-step="6">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">6. Khung giờ bạn thường rảnh để học? <span class="text-secondary fs-6 fw-normal">(Có thể chọn nhiều)</span></h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="schedule" value="weekdays">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Giờ hành chính (Sáng/Chiều T2-T6)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="schedule" value="evenings">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Buổi tối (T2-T6)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="schedule" value="weekends">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Cuối tuần (Thứ 7 - CN)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 7: Thời gian rảnh -->
                        <div class="survey-step d-none" data-step="7">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">7. Trung bình mỗi ngày bạn tự học được bao lâu?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="study_time" value="low">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Dưới 30 phút (Ít thời gian)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="study_time" value="medium">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">1 - 2 tiếng</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="study_time" value="high">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Trên 3 tiếng (Cày cuốc)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 8: Độ tuổi -->
                        <div class="survey-step d-none" data-step="8">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">8. Độ tuổi hiện tại của bạn?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="age" value="student">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Học sinh / Sinh viên</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="age" value="adult">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Người đi làm</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="age" value="kids">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Phụ huynh tìm cho con</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 9: Ngân sách -->
                        <div class="survey-step d-none" data-step="9">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">9. Ngân sách dự kiến cho 1 buổi học cùng gia sư?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="budget" value="low">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Dưới 150k/giờ <span class="text-secondary fs-6 d-block">(~1.2 triệu/tháng)</span></span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="budget" value="medium">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">150k - 300k/giờ <span class="text-secondary fs-6 d-block">(~1.2 - 2.4 triệu/tháng)</span></span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="budget" value="high">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Trên 300k/giờ <span class="text-secondary fs-6 d-block">(Trên 2.4 triệu/tháng)</span></span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 10: Phong cách dạy -->
                        <div class="survey-step d-none" data-step="10">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">10. Bạn thích gia sư có phong cách giảng dạy thế nào? <span class="text-secondary fs-6 fw-normal">(Có thể chọn nhiều)</span></h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="teaching_style" value="fun">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Vui vẻ / Năng động / Hài hước</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="teaching_style" value="strict">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Nghiêm khắc / Kỷ luật cao</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="teaching_style" value="patient">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Nhẹ nhàng / Kiên nhẫn / Tỉ mỉ</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="checkbox" name="teaching_style" value="inspiring">
                                    <div class="square-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Truyền cảm hứng / Mở rộng kiến thức</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 11: Giới tính gia sư -->
                        <div class="survey-step d-none" data-step="11">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">11. Bạn có yêu cầu về giới tính Gia sư không?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="tutor_gender" value="any">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Không quan trọng</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="tutor_gender" value="female">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Ưu tiên Nữ</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="tutor_gender" value="male">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Ưu tiên Nam</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 12: Thời hạn -->
                        <div class="survey-step d-none" data-step="12">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">12. Bạn dự kiến hoàn thành mục tiêu trong bao lâu?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="timeframe" value="3months">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Dưới 3 Tháng (Cấp tốc)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="timeframe" value="6months">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">3 - 6 Tháng (Tiêu chuẩn)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="timeframe" value="12months">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Trên 6 Tháng (Từ từ vững chắc)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 13: Mục tiêu dài hạn -->
                        <div class="survey-step d-none" data-step="13">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">13. Mục tiêu dài hạn của bạn sau khi thành thạo là gì?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="long_term_goal" value="study_abroad">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Du học Trung Quốc/Đài Loan</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="long_term_goal" value="career">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Làm việc trong môi trường tiếng Trung</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="long_term_goal" value="hobby">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Sở thích cá nhân / Xem phim / Du lịch</span>
                                </label>
                            </div>
                        </div>

                        <!-- Step 14: Chứng chỉ khác -->
                        <div class="survey-step d-none" data-step="14">
                            <h5 class="fw-bold mb-4 fs-4 text-dark">14. Bạn có dự định thi chứng chỉ quốc tế ngoài HSK không?</h5>
                            <div class="row g-3">
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="other_certs" value="none">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Chỉ quan tâm HSK / Không cần chứng chỉ</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="other_certs" value="tocfl">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Thi TOCFL (Phồn thể - Đài Loan)</span>
                                </label>
                                <label class="survey-option rounded-4 p-3 text-start d-flex align-items-center gap-3 col-12 col-md-6">
                                    <input type="radio" name="other_certs" value="hskk">
                                    <div class="circle-check"></div>
                                    <span class="fs-5 fw-medium text-dark">Thi HSKK (Khẩu ngữ)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                            <button type="button" class="btn btn-light rounded-pill px-5 py-2 fw-bold shadow-sm" id="btnPrev" style="display: none; border: 1px solid #e5e7eb;">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </button>
                            <button type="button" class="btn btn-primary-custom rounded-pill px-5 py-2 fw-bold shadow-sm ms-auto btn-disabled" id="btnNext">
                                Tiếp tục <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                            <button type="button" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow-lg ms-auto btn-disabled" id="btnSubmit" style="display: none;" onclick="submitStudentSurvey()">
                                Hoàn tất & Nhận lộ trình <i class="bi bi-magic ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingAnalysis" class="text-center py-5 d-none animate-fade-up">
                <div class="spinner-border text-primary mb-4" style="width: 4rem; height: 4rem;" role="status"></div>
                <h3 class="fw-bold text-dark">AI đang xử lý Cosine Similarity...</h3>
                <p class="text-secondary fs-5">Hệ thống đang chạy thuật toán 36 chiều để tìm gia sư phù hợp nhất với bạn</p>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 14;

    document.addEventListener("DOMContentLoaded", () => {
        const user = getUser();
        if (!user) {
            showToast("Vui lòng đăng nhập với tư cách học viên!");
            setTimeout(() => window.location.href = '/auth/login', 1500);
        } else if (user.role === 'tutor') {
            showToast("Tài khoản Gia sư không thể làm bài khảo sát này!");
            setTimeout(() => window.location.href = '/tutor/dashboard', 1500);
        } else if (user.role !== 'student') {
            showToast("Trang này chỉ dành cho Học viên!");
            setTimeout(() => window.location.href = '/', 1500);
        }
        
        updateProgress();
        checkCurrentStepSelection();
        
        // Bắt sự kiện thay đổi trên các input để kích hoạt nút Next/Submit
        document.querySelectorAll('.survey-option input').forEach(input => {
            input.addEventListener('change', checkCurrentStepSelection);
        });
        
        document.getElementById('btnNext').addEventListener('click', () => {
            if (currentStep < totalSteps && isStepValid(currentStep)) {
                document.querySelector(`.survey-step[data-step="${currentStep}"]`).classList.add('d-none');
                currentStep++;
                document.querySelector(`.survey-step[data-step="${currentStep}"]`).classList.remove('d-none');
                updateProgress();
                checkCurrentStepSelection();
            }
        });

        document.getElementById('btnPrev').addEventListener('click', () => {
            if (currentStep > 1) {
                document.querySelector(`.survey-step[data-step="${currentStep}"]`).classList.add('d-none');
                currentStep--;
                document.querySelector(`.survey-step[data-step="${currentStep}"]`).classList.remove('d-none');
                updateProgress();
                checkCurrentStepSelection();
            }
        });
    });

    function isStepValid(step) {
        const stepContainer = document.querySelector(`.survey-step[data-step="${step}"]`);
        if (!stepContainer) return false;
        const checkedInputs = stepContainer.querySelectorAll('input:checked');
        return checkedInputs.length > 0;
    }

    function checkCurrentStepSelection() {
        const valid = isStepValid(currentStep);
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        
        if (valid) {
            btnNext.classList.remove('btn-disabled');
            btnSubmit.classList.remove('btn-disabled');
        } else {
            btnNext.classList.add('btn-disabled');
            btnSubmit.classList.add('btn-disabled');
        }
    }

    function updateProgress() {
        const percent = Math.round(((currentStep - 1) / totalSteps) * 100);
        const progressBar = document.getElementById('surveyProgress');
        const progressText = document.getElementById('progressText');
        
        progressBar.style.width = percent + '%';
        progressText.innerText = percent + '% Hoàn thành';

        document.getElementById('btnPrev').style.display = currentStep === 1 ? 'none' : 'block';
        
        if (currentStep === totalSteps) {
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnSubmit').style.display = 'block';
            progressBar.style.width = '100%';
            progressBar.classList.remove('bg-primary');
            progressBar.classList.add('bg-success');
            progressText.innerText = '100% Hoàn thành';
            progressText.classList.remove('text-primary');
            progressText.classList.add('text-success');
        } else {
            document.getElementById('btnNext').style.display = 'block';
            document.getElementById('btnSubmit').style.display = 'none';
            progressBar.classList.remove('bg-success');
            progressBar.classList.add('bg-primary');
            progressText.classList.remove('text-success');
            progressText.classList.add('text-primary');
        }
    }

    async function submitStudentSurvey() {
        if (!isStepValid(currentStep)) return;

        const form = document.getElementById('studentSurveyForm');
        
        const getVal = (name) => form.querySelector(`input[name="${name}"]:checked`)?.value || '';
        const getArr = (name) => Array.from(form.querySelectorAll(`input[name="${name}"]:checked`)).map(cb => cb.value);

        const current_level = getVal('current_level');
        const age = getVal('age');
        const budget = getVal('budget');
        const timeframe = getVal('timeframe');
        const tutor_gender = getVal('tutor_gender');
        const study_time = getVal('study_time');
        const long_term_goal = getVal('long_term_goal');
        const other_certs = getVal('other_certs');
        
        // Mặc định cho câu hỏi AI (đã bị xoá) là high (Rất muốn)
        const ai_preference = 'high'; 
        
        const goals = getArr('goals');
        const skills = getArr('skills');
        const modes = getArr('modes');
        const schedule = getArr('schedule');
        const teaching_style = getArr('teaching_style');
        const weaknesses = getArr('weaknesses');

        document.getElementById('surveyContainer').classList.add('d-none');
        document.getElementById('loadingAnalysis').classList.remove('d-none');

        try {
            const payload = {
                current_level, age, budget, timeframe, ai_preference, 
                goals, skills, modes, schedule,
                teaching_style, tutor_gender, weaknesses, study_time, long_term_goal, other_certs
            };
            
            const data = await apiRequest('/api/Survey', 'POST', payload);
            
            // Luu vao localStorage tam thoi ket qua match
            localStorage.setItem('lanying_ai_recommendations', JSON.stringify(data.recommendations));
            
            const user = getUser();
            if (user) {
                user.hasCompletedSurvey = true;
                localStorage.setItem('lanying_user', JSON.stringify(user));
            }
            
            showToast("Phân tích thành công! Đang chuyển hướng...");
            setTimeout(() => {
                window.location.href = '/student/dashboard'; 
            }, 1500);
            
        } catch (e) {
            showToast("Lỗi: " + e.message);
            document.getElementById('loadingAnalysis').classList.add('d-none');
            document.getElementById('surveyContainer').classList.remove('d-none');
        }
    }
</script>

<?php include 'Views/layouts/footer.php'; ?>
