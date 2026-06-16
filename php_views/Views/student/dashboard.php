<?php include 'Views/layouts/header.php'; ?>

<div class="container mt-5 py-5 min-vh-100 animate-fade-up">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-panel p-4 d-flex align-items-center gap-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" id="user-avatar-dash"
                     style="width:64px;height:64px;font-size:1.8rem;background:linear-gradient(135deg,#3b82f6,#8b5cf6);flex-shrink:0;">
                    H
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-1" id="welcome-name">Xin chào, Học viên!</h3>
                    <p class="text-secondary mb-0">Tiếp tục hành trình chinh phục tiếng Trung của bạn hôm nay.</p>
                </div>
                <div class="d-none d-md-flex gap-3">
                    <a href="/student/survey" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-robot me-2"></i>Làm lại khảo sát AI
                    </a>
                    <a href="/chat" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-chat-dots me-2"></i>Nhắn tin Gia sư
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-3 mb-4" id="quick-stats">
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-primary" id="stat-bookings">0</div>
                <div class="text-secondary small">Lớp đã đặt</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-success" id="stat-accepted">0</div>
                <div class="text-secondary small">Được chấp nhận</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-warning" id="stat-pending">0</div>
                <div class="text-secondary small">Chờ duyệt</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-info" id="stat-ai-score">--</div>
                <div class="text-secondary small">Điểm Cosine AI</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Bookings & AI Recommendations -->
        <div class="col-lg-8">
            <!-- AI Recommendations từ Khảo sát -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4" id="ai-recommendations-card">
                <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #eff6ff, #e0f2fe);">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-cpu me-2"></i>Gợi ý Gia sư từ AI (Cosine Similarity)
                    </h5>
                    <p class="text-secondary small mb-0 mt-1">Dựa trên kết quả khảo sát lộ trình của bạn</p>
                </div>
                <div class="card-body p-4" id="ai-recs-body">
                    <div class="text-center text-secondary py-3">
                        <i class="bi bi-robot fs-1 d-block mb-2 text-primary"></i>
                        <p>Chưa có dữ liệu AI. <a href="/student/survey" class="text-primary fw-bold">Làm khảo sát</a> để xem gợi ý gia sư phù hợp nhất!</p>
                    </div>
                </div>
            </div>

            <!-- Danh sách Booking -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header border-0 py-3 px-4 bg-white">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-calendar2-check text-primary me-2"></i>Lịch đặt học của tôi
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Gia sư</th>
                                    <th>Lời nhắn</th>
                                    <th>Ngày gửi</th>
                                    <th>Trạng thái</th>
                                    <th class="pe-4">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="booking-list">
                                <tr><td colspan="5" class="text-center text-secondary py-4">
                                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>Đang tải...
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Quick Actions & Info -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header border-0 py-3 px-4 bg-white">
                    <h6 class="fw-bold mb-0"><i class="bi bi-lightning me-2 text-warning"></i>Thao tác nhanh</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="/#giasu" class="btn btn-primary-custom rounded-pill py-2">
                            <i class="bi bi-people me-2"></i>Tìm Gia sư mới
                        </a>
                        <a href="/student/survey" class="btn btn-outline-primary rounded-pill py-2">
                            <i class="bi bi-robot me-2"></i>Khảo sát AI lộ trình
                        </a>
                        <a href="/chat" class="btn btn-outline-success rounded-pill py-2">
                            <i class="bi bi-chat-dots me-2"></i>Mở hộp thư
                        </a>
                        <a href="/schedule" class="btn btn-outline-secondary rounded-pill py-2">
                            <i class="bi bi-calendar3 me-2"></i>Xem thời khóa biểu
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lộ trình học -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header border-0 py-3 px-4 bg-white">
                    <h6 class="fw-bold mb-0"><i class="bi bi-map me-2 text-info"></i>Lộ trình học tập</h6>
                </div>
                <div class="card-body p-3" id="learning-path-widget">
                    <div class="text-center text-secondary small py-2">
                        <i class="bi bi-clipboard2-check d-block fs-3 mb-2 text-info"></i>
                        Hoàn thành khảo sát để xem lộ trình cá nhân hóa của bạn.
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card border-0 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #fffbeb, #fef3c7);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-lightbulb me-2"></i>Mẹo học tiếng Trung</h6>
                    <div id="daily-tip" class="text-secondary small">Đang tải mẹo hôm nay...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const tips = [
        "🎯 Học 10 từ mới mỗi ngày và ôn lại vào buổi tối. Sau 3 tháng bạn có thể học 900 từ!",
        "📖 Luyện đọc báo tiếng Trung 15 phút mỗi ngày giúp cải thiện từ vựng và ngữ pháp nhanh hơn 40%.",
        "🎤 Nói to khi học phát âm - não sẽ ghi nhớ tốt hơn khi có cả âm thanh và vận động.",
        "✍️ Viết tay chữ Hán giúp ghi nhớ lâu hơn gõ bàn phím. Hãy thực hành mỗi ngày!",
        "🔄 Ôn lại bài cũ trước khi học bài mới. Spaced Repetition là phương pháp hiệu quả nhất.",
        "🎵 Nghe nhạc tiếng Trung hoặc xem phim không phụ đề để làm quen với nhịp điệu ngôn ngữ.",
        "👥 Tìm bạn học cùng - học theo cặp tăng hiệu quả ghi nhớ lên đến 70%!"
    ];

    document.addEventListener("DOMContentLoaded", async () => {
        const user = getUser();
        if (!user || user.role !== 'student') {
            showToast("Chỉ Học viên mới được vào trang này!");
            setTimeout(() => window.location.href = '/auth/login', 1500);
            return;
        }

        if (user.hasCompletedSurvey === false) {
            window.location.href = '/student/survey';
            return;
        }

        // Set user avatar & welcome text
        document.getElementById('welcome-name').textContent = `Xin chào, ${user.name}! 👋`;
        const avatar = document.getElementById('user-avatar-dash');
        avatar.textContent = user.name.charAt(0).toUpperCase();

        // Daily Tip
        const todayTip = tips[new Date().getDay() % tips.length];
        document.getElementById('daily-tip').innerHTML = todayTip;

        // Load AI Recommendations from localStorage (from survey)
        const recs = JSON.parse(localStorage.getItem('lanying_ai_recommendations') || '[]');
        if (recs.length > 0) {
            renderAIRecommendations(recs);
            const topScore = recs[0]?.similarity_score || 0;
            document.getElementById('stat-ai-score').textContent = topScore + '%';
        }

        // Load Bookings
        loadBookings();

        // Load learning path widget
        renderLearningPath(user);
    });

    function renderAIRecommendations(recs) {
        const body = document.getElementById('ai-recs-body');
        if (!recs || recs.length === 0) return;

        body.innerHTML = `<div class="row g-3">` + recs.slice(0, 3).map((t, i) => {
            const score = t.similarity_score || 0;
            const scoreColor = score >= 70 ? 'success' : score >= 40 ? 'warning' : 'secondary';
            const avatarUrl = t.avatarUrl || t.AvatarUrl;
            const name = t.name || t.Name || 'Gia sư';
            
            // Format tags for specialization
            const rawSpec = t.specialization || t.Specialization || '';
            const formattedSpecLabels = typeof formatTags !== 'undefined' ? formatTags(rawSpec) : rawSpec.split(',');
            const spec = formattedSpecLabels.length > 0 ? formattedSpecLabels.join(' & ') : 'Gia sư Tiếng Trung';
            
            const tutorId = t.id || t.Id;
            
            const avatarHtml = avatarUrl && avatarUrl.length > 5
                ? `<img src="${avatarUrl}" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">`
                : `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:48px;height:48px;background:linear-gradient(135deg,#3b82f6,#0ea5e9);">${name.charAt(0).toUpperCase()}</div>`;

            return `
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border bg-white shadow-sm hover-elevate transition-all">
                    <div class="position-relative">
                        ${avatarHtml}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-${scoreColor}" style="font-size:0.65rem;">${score}%</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1" style="font-size: 0.95rem;">#${i+1} ${name}</div>
                        <div class="badge bg-primary-subtle text-primary mb-2" style="font-size:0.75rem; white-space: normal; text-align: left;"><i class="bi bi-stars"></i> ${spec}</div>
                        <div class="progress" style="height:5px; border-radius: 5px;">
                            <div class="progress-bar bg-${scoreColor}" style="width:${score}%; border-radius: 5px;"></div>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end justify-content-between h-100 ps-2" style="min-width: 80px;">
                        <div class="text-end mb-2">
                            <div class="fw-bold text-${scoreColor}">${score}%</div>
                            <div class="text-secondary" style="font-size:0.65rem;">Độ phù hợp</div>
                        </div>
                        <button class="btn btn-sm btn-primary-custom rounded-pill py-1 px-3 w-100 shadow-sm" style="font-size: 0.75rem;" onclick="if(typeof bookTutor === 'function') bookTutor(${tutorId}); else alert('Vui lòng quay lại trang chủ để đặt lịch!')"><i class="bi bi-calendar-check"></i> Đặt ngay</button>
                    </div>
                </div>
            </div>`;
        }).join('') + `</div>
        <div class="text-center mt-3">
            <a href="/#giasu" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                Xem tất cả gia sư <i class="bi bi-arrow-right"></i>
            </a>
        </div>`;
    }

    async function renderLearningPath(user) {
        const widget = document.getElementById('learning-path-widget');
        const levelMap = {
            'beginner': { label: 'Mới bắt đầu', next: 'HSK 1', color: '#6ee7b7', progress: 0 },
            'hsk1': { label: 'HSK 1-2', next: 'HSK 3', color: '#93c5fd', progress: 25 },
            'hsk3': { label: 'HSK 3-4', next: 'HSK 5', color: '#fca5a5', progress: 55 },
            'hsk5': { label: 'HSK 5-6', next: 'Thành thạo', color: '#d8b4fe', progress: 85 },
        };
        const level = user.currentLevel || 'beginner';
        const info = levelMap[level] || levelMap['beginner'];

        let classesHtml = '';
        try {
            const classes = await apiRequest("/api/tutors/my-classes");
            if (classes && classes.length > 0) {
                classesHtml = '<div class="mt-3 pt-3 border-top"><h6 class="fw-bold text-dark small mb-2"><i class="bi bi-journal-text me-1"></i>Lớp đang học:</h6><ul class="list-unstyled mb-0">';
                classes.forEach(c => {
                    classesHtml += `<li class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-book text-primary"></i>
                        <span class="small fw-semibold text-dark text-truncate" style="max-width: 120px;" title="${c.name}">${c.name}</span>
                        <span class="badge bg-light text-secondary border ms-auto text-truncate" style="max-width: 100px;" title="${c.tutorName}">GS: ${c.tutorName}</span>
                    </li>`;
                });
                classesHtml += '</ul></div>';
            } else {
                classesHtml = '<div class="mt-3 pt-3 border-top text-center text-secondary small">Chưa tham gia lớp nào.</div>';
            }
        } catch (err) {
            console.error(err);
        }

        widget.innerHTML = `
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Hiện tại: <span style="color:${info.color}">${info.label}</span></span>
                <span class="text-secondary small">Tiếp theo: ${info.next}</span>
            </div>
            <div class="progress mb-3" style="height:8px;border-radius:50px;">
                <div class="progress-bar" style="width:${info.progress}%;background:linear-gradient(135deg,#3b82f6,#8b5cf6);border-radius:50px;"></div>
            </div>
            <div class="text-secondary small text-center">${info.progress}% tiến độ lộ trình tổng thể</div>
            ${classesHtml}`;
    }

    async function loadBookings() {
        try {
            const res = await apiRequest("/api/tutors/bookings");
            const tbody = document.getElementById("booking-list");
            tbody.innerHTML = '';

            if (!res || res.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-secondary py-4"><i class="bi bi-calendar-x d-block fs-2 mb-2 text-secondary"></i>Chưa có lịch đặt nào. Hãy tìm Gia sư và đặt lịch học ngay!</td></tr>';
                return;
            }

            // Update stats
            document.getElementById('stat-bookings').textContent = res.length;
            document.getElementById('stat-accepted').textContent = res.filter(b => b.status === 'accepted').length;
            document.getElementById('stat-pending').textContent = res.filter(b => b.status === 'pending').length;

            res.forEach(b => {
                let statusBadge = '<span class="badge bg-secondary rounded-pill">Không rõ</span>';
                if (b.status === 'pending') statusBadge = '<span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-hourglass-split me-1"></i>Chờ duyệt</span>';
                else if (b.status === 'accepted') statusBadge = '<span class="badge bg-success rounded-pill"><i class="bi bi-check-circle me-1"></i>Chấp nhận</span>';
                else if (b.status === 'paid') statusBadge = '<span class="badge bg-info text-dark rounded-pill"><i class="bi bi-cash-coin me-1"></i>Đã thanh toán</span>';
                else if (b.status === 'rejected') statusBadge = '<span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i>Từ chối</span>';
                else statusBadge = '<span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i>Từ chối</span>';

                let amountValue = b.amount || b.Amount || 100000;
                let actions = '<span class="text-secondary fst-italic small">Đang chờ</span>';
                if (b.status === 'accepted') {
                    actions = `<a href="/student/checkout?booking_id=${b.id}&amount=${amountValue}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm"><i class="bi bi-credit-card me-1"></i>Thanh toán</a>`;
                } else if (b.status === 'paid') {
                    actions = `<a href="/chat?user_id=${b.tutorId}" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i class="bi bi-chat-dots me-1"></i>Nhắn tin</a>`;
                } else if (b.status === 'rejected') {
                    actions = '<span class="text-danger small"><i class="bi bi-slash-circle me-1"></i>Bị từ chối</span>';
                }

                tbody.innerHTML += `
                    <tr>
                        <td class="ps-4 fw-bold text-primary">GS-${b.tutorId}</td>
                        <td class="text-secondary fst-italic" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${b.note || 'Không có'}</td>
                        <td class="small text-secondary">${new Date(b.createdAt).toLocaleDateString('vi-VN')}</td>
                        <td>${statusBadge}</td>
                        <td class="pe-4">${actions}</td>
                    </tr>
                `;
            });
        } catch (err) {
            console.log("Lỗi tải bookings: " + err.message);
            document.getElementById("booking-list").innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">Lỗi: ${err.message}</td></tr>`;
        }
    }
</script>

<?php include 'Views/layouts/footer.php'; ?>

