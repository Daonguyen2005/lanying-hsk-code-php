<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khảo sát trình độ - Lanying HSK</title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css?v=2">
</head>
<body>
    <div class="bg-blobs"></div>
    <div class="toast-custom" id="toast"></div>

    <div class="survey-container container">
        <div class="glass-panel survey-card p-4 p-md-5">
            <!-- Progress Bar -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-end mb-2">
                    <span class="text-secondary fw-semibold">Tiến độ khảo sát</span>
                    <span class="text-gradient fw-bold fs-5" id="progress-text">0%</span>
                </div>
                <div class="progress-glass">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
            </div>

            <!-- Steps Container -->
            <div id="survey-content">
                <!-- Dynamic Steps will be injected here -->
                <div id="dynamic-steps-container"></div>

            </div>

            <!-- Loading State -->
            <div id="loading" class="text-center py-5 d-none animate-fade-up">
                <div class="spinner-border text-primary-custom mb-4" style="width: 4rem; height: 4rem;" role="status"></div>
                <h3 class="fw-bold text-dark">AI đang xử lý...</h3>
                <p class="text-secondary fs-5">Hệ thống đang đối chiếu dữ liệu để tìm gia sư phù hợp nhất với bạn</p>
            </div>

            <!-- Results -->
            <div id="results" class="d-none animate-fade-up">
                <div class="text-center mb-5">
                    <h2 class="fw-bold display-6 mb-3">Kết quả <span class="text-gradient">Phân Tích</span></h2>
                    <p class="text-secondary fs-5" id="analysis-text"></p>
                </div>
                <h4 class="fw-bold mb-4 border-bottom pb-2">Top Gia sư phù hợp (Gợi ý bởi AI)</h4>
                <div class="row g-4" id="matched-tutors"></div>
                
                <div class="text-center mt-5 pt-4">
                    <a href="/" class="btn btn-outline-custom btn-lg rounded-pill px-5">Về trang chủ</a>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/api.js"></script>
    <script>
        let currentStep = 1;
        let totalSteps = 0;
        let questionsData = [];
        const answers = {};

        document.addEventListener('DOMContentLoaded', loadQuestions);

        async function loadQuestions() {
            try {
                questionsData = await SurveyAPI.getQuestions();
                totalSteps = questionsData.length;
                renderSteps();
            } catch (err) {
                showToast("Lỗi tải câu hỏi: " + err.message);
            }
        }

        function renderSteps() {
            const container = document.getElementById('dynamic-steps-container');
            let html = '';
            
            questionsData.forEach((q, index) => {
                const stepNum = index + 1;
                const isFirst = stepNum === 1;
                const isLast = stepNum === totalSteps;
                
                html += `
                <div class="step ${isFirst ? 'active' : ''}" id="step-${stepNum}">
                    <h2 class="fw-bold mb-4">${q.text}</h2>
                `;
                
                q.options.forEach(opt => {
                    html += `
                    <div class="survey-option" onclick="selectOption(${stepNum}, '${opt.value}', '${q.stepKey}')">
                        <div class="radio-circle"></div> ${opt.text}
                    </div>
                    `;
                });
                
                html += `<div class="mt-4 d-flex justify-content-${isFirst ? 'end' : 'between'}">`;
                if (!isFirst) {
                    html += `<button class="btn btn-outline-custom" onclick="prevStep(${stepNum})">Quay lại</button>`;
                }
                
                if (isLast) {
                    html += `<button class="btn btn-primary-custom px-5 fs-5 shadow-glow" onclick="submitSurvey()">✨ Phân Tích Bằng AI ✨</button>`;
                } else {
                    html += `<button class="btn btn-primary-custom" onclick="nextStep(${stepNum})">Tiếp tục <i class="bi bi-arrow-right"></i></button>`;
                }
                
                html += `</div></div>`;
            });
            
            container.innerHTML = html;
            updateProgress();
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 3000);
        }

        function updateProgress() {
            const percent = ((currentStep - 1) / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = percent + '%';
            document.getElementById('progress-text').textContent = Math.round(percent) + '%';
        }

        function selectOption(step, value, stepKey) {
            answers[stepKey] = value;
            answers[`q${step}`] = value; // for validation
            const options = document.querySelectorAll(`#step-${step} .survey-option`);
            options.forEach(opt => opt.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
        }

        function nextStep(step) {
            if (!answers[`q${step}`]) {
                showToast("Vui lòng chọn 1 phương án!");
                return;
            }
            document.getElementById(`step-${step}`).classList.remove('active');
            currentStep++;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            updateProgress();
        }

        function prevStep(step) {
            document.getElementById(`step-${step}`).classList.remove('active');
            currentStep--;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            updateProgress();
        }

        async function submitSurvey() {
            if (!answers[`q${totalSteps}`]) {
                showToast("Vui lòng hoàn thành câu cuối!");
                return;
            }
            
            document.getElementById('progress-bar').style.width = '100%';
            document.getElementById('progress-text').textContent = '100%';
            document.getElementById('survey-content').classList.add('d-none');
            document.getElementById('loading').classList.remove('d-none');

            // Use answers from stepKeys mapped directly
            const payload = {
                current_level: answers.current_level || 'beginner',
                goals: [answers.goals].filter(Boolean),
                skills: [answers.skills].filter(Boolean),
                modes: [answers.modes].filter(Boolean),
                budget: answers.budget || 'medium',
                schedule: [answers.schedule].filter(Boolean),
                age: answers.age || 'adult'
            };


            const user = JSON.parse(localStorage.getItem('lanying_user') || 'null');
            if (user && user.id) {
                payload.user_id = user.id;
            }

            try {
                const data = await SurveyAPI.submit(payload);
                
                document.getElementById('loading').classList.add('d-none');
                document.getElementById('results').classList.remove('d-none');
                
                document.getElementById('analysis-text').textContent = "Dựa trên hồ sơ của bạn, hệ thống AI đã tính toán độ tương đồng Cosine Similarity để đưa ra kết quả gia sư phù hợp nhất.";

                const container = document.getElementById('matched-tutors');
                if (!data.recommendations || data.recommendations.length === 0) {
                    container.innerHTML = `<div class="col-12"><div class="alert alert-info text-center rounded-4 border-0">Chưa tìm thấy gia sư phù hợp hoàn toàn. Bạn có thể xem danh sách ở trang chủ.</div></div>`;
                    return;
                }

                data.recommendations.forEach((match, index) => {
                    const delayClass = `delay-${(index + 1) * 100}`;
                    const score = match.similarity_score;
                    const card = document.createElement('div');
                    card.className = `col-md-6 animate-fade-up ${delayClass}`;
                    card.innerHTML = `
                        <div class="glass-panel h-100 p-4 border-start border-4 border-primary">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="fw-bold mb-1">${match.Name}</h4>
                                    <span class="badge bg-light text-dark border">Phí: ${match.HourlyRate ? match.HourlyRate.toLocaleString('vi-VN') + 'đ' : 'Thỏa thuận'}</span>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-gradient-custom rounded-pill fs-6 px-3 py-2 shadow-sm">${score}% Phù hợp</span>
                                </div>
                            </div>
                            <p class="text-secondary mb-4 small line-clamp-3">
                                ${match.Bio || 'Chưa có thông tin giới thiệu'}
                            </p>
                            <button class="btn btn-outline-custom w-100 rounded-pill" onclick="bookTutor(${match.Id})">Chọn Gia Sư Này</button>
                        </div>
                    `;
                    container.appendChild(card);
                });

            } catch (err) {
                showToast(`Lỗi: ${err.message}`);
                document.getElementById('loading').classList.add('d-none');
                document.getElementById('survey-content').classList.remove('d-none');
            }
        }

        async function bookTutor(tutorId) {
            const user = JSON.parse(localStorage.getItem('lanying_user') || 'null');
            const token = localStorage.getItem('lanying_token');
            
            if (!user || !token) {
                showToast('Bạn cần đăng nhập học viên để đặt lịch!');
                setTimeout(() => window.location.href = '/auth/login', 1500);
                return;
            }
            if (user.role !== 'student') {
                showToast('Chỉ tài khoản Học viên mới có thể đặt lịch học!');
                return;
            }

            try {
                await TutorAPI.book(tutorId);
                showToast('Đăng ký lớp thành công! Bạn có thể xem ở Dashboard.');
            } catch (err) {
                showToast(`Lỗi đặt lịch: ${err.message}`);
            }
        }

        updateProgress();
    </script>
</body>
</html>
