<?php include 'Views/layouts/header.php'; ?>

<div class="container-fluid py-4" style="margin-top: 80px; min-height: 80vh;">
<div class="container">
    <!-- Welcome Banner -->
    <div class="glass-panel p-4 mb-4 d-flex flex-wrap align-items-center gap-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
             id="tutor-avatar-icon"
             style="width:64px;height:64px;font-size:1.8rem;background:linear-gradient(135deg,#0ea5e9,#3b82f6);">
            G
        </div>
        <div class="flex-grow-1">
            <h3 class="fw-bold mb-1" id="tutor-welcome-name">Không gian Gia sư</h3>
            <p class="text-secondary mb-0 small">Quản lý lịch học, hồ sơ và học viên của bạn từ đây.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="/chat" class="btn btn-outline-primary rounded-pill px-4"><i class="bi bi-chat-dots me-1"></i>Nhắn tin</a>
            <a href="/schedule" class="btn btn-primary-custom rounded-pill px-4"><i class="bi bi-calendar3 me-1"></i>Lịch dạy</a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-warning" id="t-stat-pending">0</div>
                <div class="text-secondary small">Chờ duyệt</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-success" id="t-stat-accepted">0</div>
                <div class="text-secondary small">Đã chấp nhận</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-primary" id="t-stat-total">0</div>
                <div class="text-secondary small">Tổng Booking</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-panel p-3 text-center">
                <div class="fs-2 fw-bold text-danger" id="t-stat-status"><i class="bi bi-hourglass-split"></i></div>
                <div class="text-secondary small" id="t-stat-status-label">Trạng thái hồ sơ</div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-4" id="tutorTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 me-2" id="bookings-tab" data-bs-toggle="pill" data-bs-target="#bookings" type="button" role="tab">
                <i class="bi bi-bell me-1"></i>Thông báo Booking
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab">
                <i class="bi bi-person-badge me-1"></i>Hồ sơ Giảng dạy
            </button>
        </li>
    </ul>

    <div class="tab-content" id="tutorTabsContent">
        <!-- Thông báo -->
        <div class="tab-pane fade show active" id="bookings" role="tabpanel">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-calendar2-check text-primary"></i> Thông báo Yêu cầu Đặt lịch</h5>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="deleteAllBookingsConfirm()">
                            <i class="bi bi-trash me-1"></i>Xóa tất cả booking
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã Booking</th>
                                    <th>Học viên</th>
                                    <th>Lời nhắn</th>
                                    <th>Ngày gửi</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="booking-list">
                                <!-- Dữ liệu booking -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Phần Bài tập -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-journal-text text-success"></i> Thông báo Bài tập của Học viên</h5>
                    <div id="homework-list" class="text-center text-secondary py-4">Tính năng bài tập đang được hoàn thiện...</div>
                </div>
            </div>
        </div>

        <!-- Hồ sơ Của tôi -->
        <div class="tab-pane fade" id="profile" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold">Hồ sơ Giảng dạy</h4>
                                <p class="text-secondary small">Hồ sơ của bạn sẽ được Admin xét duyệt để nhận tích xanh uy tín hiển thị trên trang chủ.</p>
                            </div>
                            
                            <form id="profileForm" onsubmit="submitProfile(event)">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Giới thiệu bản thân (Bio)</label>
                                    <textarea class="form-control bg-light" id="tutorBio" rows="4" required placeholder="Ví dụ: Cử nhân ĐH Bắc Kinh, 5 năm kinh nghiệm dạy HSK..."></textarea>
                                </div>

                                <!-- CV Builder Section -->
                                <div class="mb-4 p-4 border rounded bg-light">
                                    <h5 class="fw-bold mb-3"><i class="bi bi-list-check text-primary"></i> Lĩnh vực giảng dạy (Goals)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="luyen thi"> Ôn thi HSK</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="giao tiep"> Giao tiếp</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="thuong mai"> Thương mại</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="tre em"> Trẻ em</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-star text-warning"></i> Kỹ năng chuyên sâu (Skills)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="nghe noi"> Nghe - Nói</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="phat am"> Phát âm chuẩn</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="doc viet"> Đọc - Viết</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="ngu phap"> Ngữ pháp</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart text-success"></i> Trình độ nhận dạy (Levels)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="co ban"> HSK 1-2 (Cơ bản)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="trung cap"> HSK 3-4 (Trung cấp)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="nang cao"> HSK 5-6 (Nâng cao)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="sieu cap"> HSK 7-9 (Master)</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-laptop text-info"></i> Hình thức dạy (Modes)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="online"> Học Online (Meet/Zoom)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="offline"> Học Offline (Trực tiếp)</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-people text-danger"></i> Đối tượng học viên (Ages)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="tre em"> Trẻ em</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="sinh vien"> Học sinh/Sinh viên</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="nguoi di lam"> Người đi làm</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-clock text-secondary"></i> Khung giờ rảnh (Schedule)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="weekdays"> Sáng/Chiều (T2-T6)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="evenings"> Buổi tối (T2-T6)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="weekends"> Cuối tuần (T7-CN)</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-heart text-danger"></i> Phong cách dạy (Style)</h5>
                                    <div class="row g-2 mb-2">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="vui ve"> Vui vẻ/Hài hước</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="nghiem khac"> Nghiêm khắc/Kỷ luật</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="kien nhan"> Nhẹ nhàng/Kiên nhẫn</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="truyen cam hung"> Truyền cảm hứng</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Học phí (VNĐ/Giờ)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-light" id="tutorRate" required placeholder="200000">
                                            <span class="input-group-text bg-white">đ</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold"><i class="bi bi-person-circle text-primary"></i> Ảnh đại diện (Avatar)</label>
                                        <input class="form-control" type="file" id="tutorAvatar" accept="image/*">
                                        <small class="text-secondary d-block mt-1">Cập nhật ảnh đại diện chuyên nghiệp.</small>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold"><i class="bi bi-award text-warning"></i> Ảnh chụp chứng chỉ HSK</label>
                                        <input class="form-control" type="file" id="tutorCert" accept="image/*">
                                        <small class="text-secondary d-block mt-1">Để được nhận tích xanh xác thực.</small>
                                    </div>
                                </div>
                                
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary-custom btn-lg rounded-pill fw-bold shadow-sm" id="btnSubmitProfile">
                                        <i class="bi bi-cloud-arrow-up"></i> Lưu & Gửi Xét duyệt
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal Chọn Lớp Học (Cho Gia sư) -->
<div class="modal fade" id="selectClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary-custom text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-calendar-check"></i> Xếp lớp cho Học viên</h5>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary mb-3">Vui lòng chọn một lịch học trống của bạn để xếp học viên này vào. Nếu bạn chưa có lịch học nào trống, vui lòng tạo lịch ở phần <a href="/schedule">Thời khóa biểu</a>.</p>
                <input type="hidden" id="pendingBookingId">
                <div class="mb-3">
                    <label class="form-label fw-bold">Lớp học / Lịch rảnh</label>
                    <select class="form-select bg-light" id="availableSchedulesSelect">
                        <option value="">Đang tải...</option>
                    </select>
                </div>
                <div id="noScheduleWarning" class="alert alert-warning d-none">
                    <i class="bi bi-exclamation-triangle"></i> Bạn chưa có lịch rảnh nào. Hãy <a href="/schedule">tạo lịch ngay</a>.
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary-custom rounded-pill px-4 shadow-sm" id="btnConfirmAccept" onclick="confirmAcceptBooking()">Xác nhận Nhận lớp</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const user = getUser();
        if (!user || user.role !== 'tutor') {
            showToast("Chỉ Gia sư mới được vào trang này!");
            setTimeout(() => window.location.href = '/auth/login', 1500);
        } else {
            document.getElementById('tutor-welcome-name').textContent = `Xin chào, ${user.name}!`;
            document.getElementById('tutor-avatar-icon').textContent = user.name.charAt(0).toUpperCase();
            loadProfile();
            loadBookings();
        }
    });

    async function loadProfile() {
        try {
            const profile = await apiRequest("/api/tutors/me/profile");
            if (profile) {
                if (profile.bio) document.getElementById('tutorBio').value = profile.bio;
                if (profile.hourlyRate) document.getElementById('tutorRate').value = profile.hourlyRate;
                
                // Prefill checkboxes cho tags
                const currentTags = (profile.tagsVector || '').split(',').map(s => s.trim());
                document.querySelectorAll('input[name="tutor_tags"]').forEach(cb => {
                    if (currentTags.includes(cb.value)) cb.checked = true;
                });
                
                // Prefill checkboxes cho levels
                const currentLevels = (profile.teachingLevels || '').split(',').map(s => s.trim());
                document.querySelectorAll('input[name="tutor_levels"]').forEach(cb => {
                    if (currentLevels.includes(cb.value)) cb.checked = true;
                });

                let statusHtml = '';
                let statusIcon = '<i class="bi bi-x-circle"></i>';
                let statusLabel = 'Chưa nộp';
                if (profile.isApproved) {
                    statusHtml = '<div class="alert alert-success mt-3"><i class="bi bi-patch-check-fill me-2"></i>Hồ sơ đã được duyệt! Đang hiển thị trên trang chủ.</div>';
                    statusIcon = '<i class="bi bi-patch-check-fill text-success"></i>';
                    statusLabel = 'Đã duyệt';
                } else if (profile.isSubmittedForReview) {
                    statusHtml = '<div class="alert alert-warning text-dark mt-3"><i class="bi bi-hourglass-split me-2"></i>Hồ sơ đang chờ Admin xét duyệt.</div>';
                    statusIcon = '<i class="bi bi-hourglass-split text-warning"></i>';
                    statusLabel = 'Chờ duyệt';
                }

                document.getElementById('t-stat-status').innerHTML = statusIcon;
                document.getElementById('t-stat-status-label').textContent = statusLabel;
                
                const formCard = document.querySelector('.card-body form');
                if (statusHtml && formCard) {
                    formCard.insertAdjacentHTML('beforebegin', statusHtml);
                }
            }
        } catch (err) {
            console.log("Chưa có hồ sơ hoặc lỗi: " + err.message);
        }
    }

    async function loadBookings() {
        try {
            const res = await apiRequest("/api/tutors/bookings");
            const tbody = document.getElementById("booking-list");
            tbody.innerHTML = '';

            if (res.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-secondary py-4"><i class="bi bi-inbox d-block fs-2 mb-2"></i>Chưa có yêu cầu nào</td></tr>';
                return;
            }

            // Update stats
            document.getElementById('t-stat-total').textContent = res.length;
            document.getElementById('t-stat-pending').textContent = res.filter(b => b.status === 'pending').length;
            document.getElementById('t-stat-accepted').textContent = res.filter(b => b.status === 'accepted').length;

            res.forEach(b => {
                let statusBadge = '<span class="badge bg-secondary">Không rõ</span>';
                if (b.status === 'pending') statusBadge = '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Chờ duyệt</span>';
                else if (b.status === 'accepted') statusBadge = '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã chấp nhận</span>';
                else if (b.status === 'paid') statusBadge = '<span class="badge bg-info text-dark"><i class="bi bi-cash-coin"></i> Đã thanh toán</span>';
                else if (b.status === 'rejected') statusBadge = '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Từ chối</span>';
                else statusBadge = '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Từ chối</span>';

                let actions = '';
                if (b.status === 'pending') {
                    actions = `
                        <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm me-1 mb-1" onclick="updateStatus(${b.id}, 'accepted', decodeURIComponent('${encodeURIComponent(b.note || '')}'))">Chấp nhận</button>
                        <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm mb-1" onclick="updateStatus(${b.id}, 'rejected')">Từ chối</button>
                        <a href="/chat?user_id=${b.studentId}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm mt-1 d-block" style="width: fit-content;"><i class="bi bi-chat-dots"></i> Nhắn tin</a>
                    `;
                } else if (b.status === 'accepted' || b.status === 'paid') {
                    actions = `
                        <a href="/chat?user_id=${b.studentId}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm"><i class="bi bi-chat-dots"></i> Nhắn tin</a>
                    `;
                }

                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-secondary">#${b.id}</span></td>
                        <td class="fw-bold">${b.studentName || 'HS-' + b.studentId}</td>
                        <td class="text-secondary fst-italic">${b.note || 'Không có'}</td>
                        <td>${new Date(b.createdAt).toLocaleString('vi-VN')}</td>
                        <td>${statusBadge}</td>
                        <td>
                            ${actions}
                            <button class="btn btn-sm btn-outline-danger rounded-pill ms-1" onclick="deleteBookingById(${b.id}, '${b.studentName || 'HS-' + b.studentId}')" title="Xóa học viên">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        } catch(e) {
            console.error(e);
            showToast("Lỗi: " + e.message);
        }
    }

    async function deleteBookingById(id, studentName) {
        if (!confirm('Bạn có chắc chắn muốn xóa học viên ' + studentName + ' này không?')) return;
        try {
            await TutorAPI.deleteBooking(id);
            showToast('Đã xóa học viên thành công!');
            loadBookings();
        } catch (err) { showToast('Lỗi: ' + err.message); }
    }

    async function deleteAllBookingsConfirm() {
        if (!confirm('Bạn có chắc muốn XÓA TẤT CẢ yêu cầu đặt lịch không? Hành động này không thể hoàn tác!')) return;
        try {
            const res = await TutorAPI.deleteAllBookings();
            showToast(res.message || 'Đã xóa tất cả booking!');
            loadBookings();
        } catch (err) { showToast('Lỗi: ' + err.message); }
    }

    let selectClassModal = null;

    async function updateStatus(id, status, note = '') {
        if (status === 'accepted') {
            let requestedClass = null;
            const match = note.match(/\[Đăng ký (.*?)\]/);
            if (match) {
                requestedClass = match[1].trim();
            }
            document.getElementById('pendingBookingId').value = id;
            if (!selectClassModal) selectClassModal = new bootstrap.Modal(document.getElementById('selectClassModal'));
            
            // Tải danh sách lịch rảnh
            const select = document.getElementById('availableSchedulesSelect');
            const warning = document.getElementById('noScheduleWarning');
            const btnConfirm = document.getElementById('btnConfirmAccept');
            
            select.innerHTML = '<option value="">Đang tải...</option>';
            select.disabled = true;
            btnConfirm.disabled = true;
            warning.classList.add('d-none');
            
            selectClassModal.show();
            
            try {
                const schedules = await TutorAPI.getSchedule();
                
                // Lọc lịch rảnh: Lớp học (có label) luôn hiện, Ca lẻ (không label) chỉ hiện nếu chưa có học viên
                const available = schedules.filter(s => s.label || (!s.studentId && !s.StudentId));
                
                if (available.length === 0) {
                    select.innerHTML = '<option value="">(Không có lịch rảnh nào)</option>';
                    warning.classList.remove('d-none');
                } else {
                    select.innerHTML = '<option value="">-- Chọn một lớp học --</option>';
                    
                    // Nhóm theo label
                    const groups = {};
                    available.forEach(s => {
                        const key = s.label || ('single_' + (s.id || s.Id));
                        if (!groups[key]) {
                            groups[key] = { 
                                items: new Set(), 
                                id: (s.id || s.Id), 
                                label: s.label, 
                                firstDate: s.scheduleDate, 
                                schedule: s 
                            };
                        }
                        if (s.label) {
                            groups[key].items.add(s.scheduleDate + '_' + s.startPeriod);
                        } else {
                            groups[key].items.add(s.id || s.Id);
                        }
                    });

                    Object.values(groups).forEach(g => {
                        if (g.label) {
                            const isSelected = (requestedClass && g.label === requestedClass) ? 'selected' : '';
                            select.innerHTML += `<option value="${g.id}" ${isSelected}>Lớp: ${g.label} (${g.items.size} buổi, khai giảng ${g.firstDate})</option>`;
                        } else {
                            const dayLabel = g.schedule.dayOfWeek === 8 ? "Chủ nhật" : "Thứ " + g.schedule.dayOfWeek;
                            select.innerHTML += `<option value="${g.id}">${g.schedule.scheduleDate} (${dayLabel}), Ca: ${g.schedule.startPeriod}-${g.schedule.endPeriod}</option>`;
                        }
                    });

                    select.disabled = false;
                    btnConfirm.disabled = false;
                }
            } catch (e) {
                select.innerHTML = '<option value="">Lỗi tải lịch</option>';
                showToast("Lỗi: " + e.message);
            }
        } else {
            // Rejected
            try {
                await TutorAPI.updateBookingStatus(id, status);
                showToast("Đã từ chối học viên!");
                loadBookings();
            } catch (err) {
                showToast("Lỗi: " + err.message);
            }
        }
    }

    async function confirmAcceptBooking() {
        const id = document.getElementById('pendingBookingId').value;
        const scheduleId = document.getElementById('availableSchedulesSelect').value;
        
        if (!scheduleId) {
            showToast("Vui lòng chọn một lớp học!");
            return;
        }
        
        const btn = document.getElementById('btnConfirmAccept');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
        
        try {
            await TutorAPI.updateBookingStatus(id, 'accepted', scheduleId);
            showToast("Nhận học viên và xếp lớp thành công!");
            selectClassModal.hide();
            loadBookings();
        } catch (err) {
            showToast("Lỗi: " + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Xác nhận Nhận lớp';
        }
    }

    const fileToBase64 = file => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });

    async function submitProfile(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitProfile');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';

        try {
            const avatarFile = document.getElementById('tutorAvatar').files[0];
            const certFile = document.getElementById('tutorCert').files[0];
            
            let avatarBase64 = '';
            let certBase64 = '';

            if (avatarFile) avatarBase64 = await fileToBase64(avatarFile);
            if (certFile) certBase64 = await fileToBase64(certFile);

            // Lấy thông tin từ các Checkbox
            const tags = Array.from(document.querySelectorAll('input[name="tutor_tags"]:checked')).map(cb => cb.value);
            const tagsVector = tags.join(', ');
            
            const levels = Array.from(document.querySelectorAll('input[name="tutor_levels"]:checked')).map(cb => cb.value);
            const teachingLevels = levels.join(', ');
            
            const specLabels = tags.slice(0, 3);
            const specialization = specLabels.length > 0 ? specLabels.join(', ') : 'Chưa cập nhật';

            const data = {
                bio: document.getElementById('tutorBio').value,
                specialization: specialization,
                tagsVector: tagsVector,
                teachingLevels: teachingLevels,
                hourlyRate: parseInt(document.getElementById('tutorRate').value) || 0,
                avatarBase64: avatarBase64,
                certificateBase64: certBase64
            };

            const res = await apiRequest("/api/tutors/me/profile", "PUT", data);
            showToast(res.message || "Đã gửi hồ sơ xét duyệt thành công!");
            
            // Reload lại trang sau 1.5s để reset form và trạng thái UI
            setTimeout(() => window.location.reload(), 1500);
        } catch (err) {
            showToast("Lỗi: " + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cloud-arrow-up"></i> Lưu & Gửi Xét duyệt';
        }
    }
</script>

<?php include 'Views/layouts/footer.php'; ?>
