<?php include 'Views/layouts/header.php'; ?>

<style>
.schedule-hero {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 2rem 2rem;
    box-shadow: 0 10px 30px rgba(124, 58, 237, 0.2);
}

.class-card {
    background: white;
    border-radius: 1rem;
    border: none;
    box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
}

.class-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}

.class-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    background: #f8fafc;
}

.class-body {
    padding: 1.5rem;
}

.tutor-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
    color: #475569;
}

.info-item i {
    width: 24px;
    color: #64748b;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.03);
}

.empty-state-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.shimmer {
    background: #f6f7f8;
    background-image: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
    background-repeat: no-repeat;
    background-size: 800px 100%; 
    animation-duration: 1s;
    animation-fill-mode: forwards; 
    animation-iteration-count: infinite;
    animation-name: placeholderShimmer;
    animation-timing-function: linear;
}

@keyframes placeholderShimmer {
    0% { background-position: -468px 0; }
    100% { background-position: 468px 0; }
}
</style>

<div class="schedule-hero">
    <div class="container">
        <h1 class="fw-bold mb-2">🎓 Lớp học của tôi</h1>
        <p class="mb-0 opacity-75 fs-5">Theo dõi lịch học và các khóa học bạn đã đăng ký</p>
    </div>
</div>

<div class="container mb-5 pb-5">
    <div id="loading-container" class="row g-4">
        <!-- Skeleton loading -->
        <?php for($i=0; $i<3; $i++): ?>
        <div class="col-md-6 col-lg-4">
            <div class="class-card">
                <div class="class-header d-flex justify-content-between align-items-center">
                    <div class="shimmer rounded" style="width: 150px; height: 24px;"></div>
                    <div class="shimmer rounded-circle" style="width: 48px; height: 48px;"></div>
                </div>
                <div class="class-body">
                    <div class="shimmer rounded mb-3" style="width: 100%; height: 20px;"></div>
                    <div class="shimmer rounded mb-3" style="width: 80%; height: 20px;"></div>
                    <div class="shimmer rounded" style="width: 60%; height: 20px;"></div>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <div id="classes-container" class="row g-4" style="display: none;">
        <!-- Data will be rendered here -->
    </div>

    <div id="empty-state" class="empty-state" style="display: none;">
        <div class="empty-state-icon">
            <i class="bi bi-calendar-x"></i>
        </div>
        <h3 class="fw-bold text-dark">Chưa có lớp học nào</h3>
        <p class="text-secondary mb-4">Bạn chưa đăng ký lớp học nào hoặc giao dịch chưa hoàn tất.</p>
        <a href="/student/dashboard" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
            <i class="bi bi-search me-2"></i>Tìm gia sư ngay
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await apiRequest('/api/tutors/my-classes', 'GET');
        
        document.getElementById('loading-container').style.display = 'none';
        
        if (!res || res.length === 0) {
            document.getElementById('empty-state').style.display = 'block';
            return;
        }

        const container = document.getElementById('classes-container');
        container.style.display = 'flex';
        
        res.forEach(c => {
            const startDate = new Date(c.startDate).toLocaleDateString('vi-VN');
            const defaultAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(c.tutorName) + '&background=random';
            
            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-4';
            card.innerHTML = `
                <div class="class-card">
                    <div class="class-header d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-primary">${c.name}</h5>
                        <img src="${c.tutorAvatar || defaultAvatar}" alt="Tutor" class="tutor-avatar" onerror="this.src='${defaultAvatar}'">
                    </div>
                    <div class="class-body">
                        <div class="info-item">
                            <i class="bi bi-person-badge fs-5"></i>
                            <span>Gia sư: <strong>${c.tutorName}</strong></span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-calendar2-check fs-5"></i>
                            <span>Ngày bắt đầu: <strong>${startDate}</strong></span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-journal-bookmark fs-5"></i>
                            <span>Tổng số buổi: <strong>${c.totalSessions || 'Không giới hạn'}</strong></span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-geo-alt fs-5"></i>
                            <span>Hình thức: <strong>${c.location === 'online' ? 'Online (Zoom/Meet)' : 'Tại nhà'}</strong></span>
                        </div>
                        
                        <hr class="text-muted">
                        <div class="d-grid gap-2">
                            <a href="/chat?tutor=${encodeURIComponent(c.tutorName)}" class="btn btn-outline-primary rounded-pill fw-bold">
                                <i class="bi bi-chat-dots me-2"></i>Nhắn tin gia sư
                            </a>
                            <button class="btn btn-light rounded-pill text-secondary fw-bold" onclick="window.location.href='/schedule'">
                                <i class="bi bi-calendar3 me-2"></i>Xem thời khóa biểu
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

    } catch (error) {
        console.error("Lỗi khi tải danh sách lớp:", error);
        document.getElementById('loading-container').style.display = 'none';
        document.getElementById('empty-state').style.display = 'block';
        document.getElementById('empty-state').innerHTML = `
            <div class="empty-state-icon text-danger"><i class="bi bi-exclamation-triangle"></i></div>
            <h3 class="fw-bold text-dark">Đã có lỗi xảy ra</h3>
            <p class="text-secondary">${error.message}</p>
        `;
    }
});
</script>

<?php include 'Views/layouts/footer.php'; ?>
