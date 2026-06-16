<?php
$title = "Lớp học của tôi";
require_once 'Views/layouts/header.php';
?>

<style>
.classroom-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(59,130,246,0.15);
    border-radius: 20px;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    cursor: pointer;
    overflow: hidden;
}
.classroom-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(59,130,246,0.2);
    border-color: rgba(59,130,246,0.4);
}
.classroom-card .card-header-band {
    height: 6px;
    background: var(--gradient-primary);
}
.student-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(59,130,246,0.1);
    border: 1px solid rgba(59,130,246,0.2);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #1d4ed8;
    transition: background 0.2s;
}
.student-chip:hover { background: rgba(59,130,246,0.2); }
.stat-pill {
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    backdrop-filter: blur(8px);
    border-radius: 16px;
    padding: 12px 16px;
    text-align: center;
}
.modal-student-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    background: rgba(59,130,246,0.04);
    border: 1px solid rgba(59,130,246,0.1);
    transition: background 0.2s;
}
.modal-student-row:hover { background: rgba(59,130,246,0.08); }
.avatar-circle {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg,#3b82f6,#0ea5e9);
    color: white; font-weight: 700; font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>

<div class="container py-5 min-vh-100" style="margin-top: 80px">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5 animate-fade-up">
        <div>
            <h2 class="fw-bold m-0"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Lớp học của tôi</h2>
            <p class="text-secondary mt-1 mb-0">Quản lý các lớp học và học viên của bạn</p>
        </div>
        <button class="btn btn-primary-custom rounded-pill px-4 fw-bold" onclick="window.location.href='/schedule'">
            <i class="bi bi-calendar3 me-2"></i>Tạo lớp mới (Thời khóa biểu)
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="row g-4" id="classroomsGrid">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-secondary">Đang tải danh sách lớp...</p>
        </div>
    </div>
</div>

<!-- Modal: Chi tiết lớp học -->
<div class="modal fade" id="classDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-xl overflow-hidden" id="modal-dynamic-bg" style="transition: background-color 0.3s ease;">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h4 class="fw-bold mb-1" id="modal-class-name">Chi tiết lớp</h4>
                    <small class="text-secondary" id="modal-class-meta"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <!-- Stats row -->
                <div class="row g-3 mb-4" id="modal-stats-row">
                    <div class="col-4"><div class="stat-pill"><div class="fw-bold text-primary fs-5" id="modal-stat-students">0</div><div class="small text-secondary">Học viên</div></div></div>
                    <div class="col-4"><div class="stat-pill"><div class="fw-bold text-success fs-5" id="modal-stat-sessions">0</div><div class="small text-secondary">Tổng buổi</div></div></div>
                    <div class="col-4"><div class="stat-pill"><div class="fw-bold text-warning fs-5" id="modal-stat-start">—</div><div class="small text-secondary">Khai giảng</div></div></div>
                </div>

                <!-- Info -->
                <div class="glass-panel p-3 mb-4" id="modal-class-info"></div>

                <!-- Student list -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Danh sách học viên</h6>
                </div>
                <div id="modal-student-list" class="d-flex flex-column gap-2"></div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button class="btn btn-outline-danger rounded-pill px-4" id="modal-delete-btn" onclick="deleteClass()">
                    <i class="bi bi-trash"></i> Xóa lớp
                </button>
                <a class="btn btn-outline-primary rounded-pill px-4" id="modal-chat-btn" href="/chat">
                    <i class="bi bi-chat-dots me-1"></i>Nhắn tin cho cả lớp
                </a>
                <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Tạo lớp mới -->
<div class="modal fade" id="createClassModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-xl overflow-hidden">
            <div style="height:6px;background:var(--gradient-primary)"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold">Tạo lớp học mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên lớp <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-pill" id="new-class-name" placeholder="Vd: Lớp HSK3 Sáng Thứ 2">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea class="form-control" id="new-class-desc" rows="2" placeholder="Mô tả nội dung, mục tiêu lớp học..."></textarea>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Địa điểm / Link</label>
                        <input type="text" class="form-control" id="new-class-loc" placeholder="Vd: meet.google.com/...">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Ngày khai giảng</label>
                        <input type="date" class="form-control" id="new-class-start">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">Tổng số buổi</label>
                    <input type="number" class="form-control" id="new-class-sessions" value="36" min="1">
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-primary-custom rounded-pill px-4 fw-bold" onclick="submitCreateClass()">
                    <i class="bi bi-plus-circle me-1"></i>Tạo lớp
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Thêm học viên từ Booking -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-xl overflow-hidden">
            <div style="height:6px;background:var(--gradient-primary)"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Thêm học viên vào lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="text-secondary small mb-3">Chọn học viên từ danh sách đã chấp nhận booking:</p>
                <div id="add-student-list" class="d-flex flex-column gap-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
let currentClassRoom = null;
let allClassRooms = [];
let createModal, detailModal, addStudentModal;

document.addEventListener('DOMContentLoaded', async () => {
    const user = JSON.parse(localStorage.getItem('lanying_user'));
    if (!user || user.role !== 'tutor') { window.location.href = '/auth/login'; return; }

    createModal = new bootstrap.Modal(document.getElementById('createClassModal'));
    detailModal = new bootstrap.Modal(document.getElementById('classDetailModal'));
    addStudentModal = new bootstrap.Modal(document.getElementById('addStudentModal'));

    await loadClassRooms();
});

async function loadClassRooms() {
    const grid = document.getElementById('classroomsGrid');
    try {
        const rooms = await TutorAPI.getClassRooms();
        allClassRooms = rooms;
        grid.innerHTML = '';

        if (!rooms || rooms.length === 0) {
            grid.innerHTML = `
                <div class="col-12">
                    <div class="glass-panel p-5 text-center text-secondary">
                        <i class="bi bi-journal-plus fs-1 d-block mb-3 text-primary opacity-50"></i>
                        <h5 class="fw-semibold">Bạn chưa có lớp học nào</h5>
                        <p>Vào <strong>Thời khóa biểu</strong> để tạo lịch và mở lớp học của bạn.</p>
                        <a href="/schedule" class="btn btn-primary-custom rounded-pill px-4 mt-2">
                            <i class="bi bi-calendar3 me-2"></i>Đi đến Thời khóa biểu
                        </a>
                    </div>
                </div>`;
            return;
        }

        rooms.forEach((r, i) => {
            const delay = `delay-${(i % 3 + 1) * 100}`;
            const initials = r.name.substring(0,2).toUpperCase();
            const studentChips = r.students.slice(0, 3).map(s =>
                `<span class="student-chip"><i class="bi bi-person-fill"></i>${s.name}</span>`
            ).join('');
            const more = r.students.length > 3 ? `<span class="student-chip">+${r.students.length - 3} khác</span>` : '';
            const boxColor = r.color || '#3b82f6'; // Mặc định màu xanh
            
            let statusBadge = '';
            if (!r.startDate) {
                statusBadge = '<span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Chưa khai giảng</span>';
            } else if (new Date(r.startDate) > new Date()) {
                statusBadge = '<span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Dự kiến dạy</span>';
            } else {
                statusBadge = '<span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Đang dạy</span>';
            }

            grid.innerHTML += `
                <div class="col-md-6 col-lg-4 animate-fade-up ${delay}">
                    <div class="classroom-card h-100" onclick="openClassDetail(${r.id})" style="background-color: color-mix(in srgb, ${boxColor} 8%, #ffffff); border: 1px solid color-mix(in srgb, ${boxColor} 20%, transparent);">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle" style="width:48px;height:48px;font-size:1rem;background:${boxColor}">${initials}</div>
                                    <div>
                                        <h5 class="fw-bold mb-0 text-dark">${r.name}</h5>
                                        <small class="text-secondary">${r.startDate ? 'Từ ' + new Date(r.startDate).toLocaleDateString('vi-VN') : 'Chưa khai giảng'}</small>
                                    </div>
                                </div>
                            </div>

                            ${r.description ? `<p class="text-secondary small mb-3 lh-base">${r.description}</p>` : ''}

                            <div class="d-flex gap-3 mb-3">
                                <div class="d-flex align-items-center gap-1 text-secondary small">
                                    <i class="bi bi-people-fill text-primary"></i>
                                    <strong class="text-dark">${r.studentCount}</strong> học viên
                                </div>
                                <div class="d-flex align-items-center gap-1 text-secondary small">
                                    <i class="bi bi-calendar-week text-success"></i>
                                    <strong class="text-dark">${r.totalSessions}</strong> buổi
                                </div>
                                ${r.location ? `<div class="d-flex align-items-center gap-1 text-secondary small"><i class="bi bi-geo-alt text-warning"></i><span class="text-truncate" style="max-width:80px" title="${r.location}">Phòng học</span></div>` : ''}
                            </div>

                            <div class="d-flex flex-wrap gap-1">
                                ${studentChips}${more}
                                ${r.students.length === 0 ? '<span class="text-secondary small fst-italic">Chưa có học viên</span>' : ''}
                            </div>
                        </div>
                    </div>
                </div>`;
        });
    } catch (err) {
        grid.innerHTML = `<div class="col-12 text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">Lỗi: ${err.message}</p></div>`;
    }
}

function openClassDetail(id) {
    currentClassRoom = allClassRooms.find(r => r.id === id);
    if (!currentClassRoom) return;
    const r = currentClassRoom;

    document.getElementById('modal-class-name').textContent = r.name;
    document.getElementById('modal-class-meta').textContent = `Tạo ngày ${new Date(r.createdAt).toLocaleDateString('vi-VN')}`;
    document.getElementById('modal-stat-students').textContent = r.studentCount;
    document.getElementById('modal-stat-sessions').textContent = r.totalSessions;
    document.getElementById('modal-stat-start').textContent = r.startDate || '—';

    // Update colors dynamically
    const boxColor = r.color || '#3b82f6';
    document.getElementById('modal-dynamic-bg').style.backgroundColor = `color-mix(in srgb, ${boxColor} 8%, #ffffff)`;
    document.getElementById('modal-class-name').style.color = `color-mix(in srgb, ${boxColor} 80%, #000000)`;

    document.getElementById('modal-class-info').innerHTML = `
        ${r.description ? `<div class="mb-2"><i class="bi bi-card-text me-2 text-secondary"></i>${r.description}</div>` : ''}
        ${r.location ? `<div><i class="bi bi-geo-alt me-2 text-secondary"></i><a href="${r.location}" target="_blank" class="text-primary">${r.location}</a></div>` : '<div class="text-secondary fst-italic small">Chưa có địa điểm</div>'}
    `;

    renderStudentList(r.students);
    document.getElementById('modal-chat-btn').href = '/chat';
    
    const deleteBtn = document.getElementById('modal-delete-btn');
    deleteBtn.innerHTML = '<i class="bi bi-trash"></i> Xóa lớp';
    deleteBtn.classList.add('btn-outline-danger');
    deleteBtn.classList.remove('btn-secondary');
    deleteBtn.disabled = false;

    detailModal.show();
}

function renderStudentList(students) {
    const list = document.getElementById('modal-student-list');
    if (!students || students.length === 0) {
        list.innerHTML = '<div class="text-center text-secondary py-3 fst-italic">Chưa có học viên nào trong lớp</div>';
        return;
    }
    list.innerHTML = students.map(s => `
        <div class="modal-student-row">
            <div class="avatar-circle">${s.name.charAt(0).toUpperCase()}</div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-dark">${s.name}</div>
                <div class="d-flex gap-3 small text-secondary mt-1">
                    ${s.phone ? `<span><i class="bi bi-phone me-1"></i>${s.phone}</span>` : ''}
                    ${s.currentLevel ? `<span><i class="bi bi-bar-chart me-1"></i>${s.currentLevel}</span>` : ''}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="/chat?user_id=${s.id}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Nhắn tin">
                    <i class="bi bi-chat-dots"></i>
                </a>
            </div>
        </div>
    `).join('');
}

function showCreateModal() { createModal.show(); }

async function submitCreateClass() {
    const name = document.getElementById('new-class-name').value.trim();
    if (!name) { showToast('Vui lòng nhập tên lớp!'); return; }

    try {
        await TutorAPI.createClassRoom({
            name,
            description: document.getElementById('new-class-desc').value,
            location: document.getElementById('new-class-loc').value,
            startDate: document.getElementById('new-class-start').value,
            totalSessions: parseInt(document.getElementById('new-class-sessions').value) || 36
        });
        showToast('Tạo lớp học thành công!');
        createModal.hide();
        document.getElementById('new-class-name').value = '';
        document.getElementById('new-class-desc').value = '';
        await loadClassRooms();
    } catch (err) { showToast('Lỗi: ' + err.message); }
}

async function deleteCurrentClass() {
    if (!currentClassRoom) return;
    if (!confirm(`Bạn có chắc muốn xóa lớp "${currentClassRoom.name}"?`)) return;
    try {
        await TutorAPI.deleteClassRoom(currentClassRoom.id);
        showToast('Đã xóa lớp học!');
        detailModal.hide();
        await loadClassRooms();
    } catch (err) { showToast('Lỗi: ' + err.message); }
}

async function removeStudentFromClass(classId, studentId, name) {
    if (!confirm(`Xóa học viên "${name}" khỏi lớp?`)) return;
    try {
        await TutorAPI.removeStudentFromClass(classId, studentId);
        showToast(`Đã xóa ${name} khỏi lớp!`);
        const rooms = await TutorAPI.getClassRooms();
        allClassRooms = rooms;
        currentClassRoom = rooms.find(r => r.id === classId);
        if (currentClassRoom) renderStudentList(currentClassRoom.students);
        await loadClassRooms();
    } catch (err) { showToast('Lỗi: ' + err.message); }
}

async function addStudentToCurrentClass() {
    if (!currentClassRoom) return;
    const listEl = document.getElementById('add-student-list');
    listEl.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm"></div></div>';
    addStudentModal.show();

    try {
        const bookings = await apiRequest('/api/tutors/bookings');
        const accepted = bookings.filter(b => b.status === 'accepted');
        const existingIds = currentClassRoom.students.map(s => s.id);
        const toAdd = accepted.filter(b => !existingIds.includes(b.studentId));

        if (!toAdd.length) {
            listEl.innerHTML = '<div class="text-center text-secondary py-3">Tất cả học viên đã có trong lớp hoặc chưa có booking được chấp nhận.</div>';
            return;
        }

        listEl.innerHTML = toAdd.map(b => `
            <div class="modal-student-row">
                <div class="avatar-circle">${(b.studentName || 'H').charAt(0).toUpperCase()}</div>
                <div class="flex-grow-1">
                    <div class="fw-semibold">${b.studentName || 'HS-' + b.studentId}</div>
                    <div class="small text-secondary">Booking #${b.id}</div>
                </div>
                <button class="btn btn-sm btn-primary-custom rounded-pill px-3" onclick="confirmAddStudent(${b.studentId}, '${b.studentName || 'HS-' + b.studentId}')">
                    <i class="bi bi-plus me-1"></i>Thêm
                </button>
            </div>
        `).join('');
    } catch (err) {
        listEl.innerHTML = `<div class="text-danger">Lỗi: ${err.message}</div>`;
    }
}

async function confirmAddStudent(studentId, name) {
    try {
        await TutorAPI.addStudentToClass(currentClassRoom.id, studentId);
        showToast(`Đã thêm ${name} vào lớp!`);
        addStudentModal.hide();
        const rooms = await TutorAPI.getClassRooms();
        allClassRooms = rooms;
        currentClassRoom = rooms.find(r => r.id === currentClassRoom.id);
        if (currentClassRoom) renderStudentList(currentClassRoom.students);
        document.getElementById('modal-stat-students').textContent = currentClassRoom.studentCount;
        await loadClassRooms();
    } catch (err) { showToast('Lỗi: ' + err.message); }
}

async function deleteClass() {
    if (!currentClassRoom) return;
    if (!confirm(`Bạn có chắc chắn muốn xóa lớp "${currentClassRoom.name}" không? Hành động này không thể hoàn tác.`)) return;
    
    try {
        await apiRequest(`/api/tutors/classrooms/${currentClassRoom.id}`, "DELETE");
        showToast("Đã xóa lớp học thành công.");
        detailModal.hide();
        loadClassRooms();
    } catch(err) {
        showToast("Lỗi khi xóa lớp: " + err.message);
    }
}
</script>

<?php require_once 'Views/layouts/footer.php'; ?>

