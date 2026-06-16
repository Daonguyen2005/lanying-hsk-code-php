<?php include 'Views/layouts/header.php'; ?>

<style>
    body { background-color: #f8f9fa; }
    .admin-sidebar {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        min-height: calc(100vh - 120px);
    }
    .admin-content {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        min-height: calc(100vh - 120px);
    }
    .nav-link.admin-tab {
        border-radius: 0.5rem;
        padding: 10px 15px;
        margin-bottom: 5px;
        color: #495057;
        font-weight: 500;
        transition: all 0.3s;
    }
    .nav-link.admin-tab.active {
        background: linear-gradient(135deg, #0d6efd, #2563eb) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
    }
    .nav-link.admin-tab:hover:not(.active) {
        background-color: #f1f5f9;
        color: #0d6efd !important;
    }
</style>

<div class="container-fluid py-4" style="margin-top: 80px;">
    <!-- Admin Welcome Banner -->
    <div class="container mb-4">
        <div class="glass-panel p-4 d-flex align-items-center gap-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                 style="width:60px;height:60px;font-size:1.6rem;background:linear-gradient(135deg,#ef4444,#f59e0b)">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Admin Control Panel</h4>
                <p class="text-secondary mb-0 small">Quản lý toàn bộ hệ thống Lanying HSK từ đây.</p>
            </div>
            <div class="d-flex gap-3 text-center">
                <div class="px-3">
                    <div class="fs-4 fw-bold text-primary" id="admin-stat-tutors">--</div>
                    <div class="small text-secondary">Gia sư</div>
                </div>
                <div class="border-start px-3">
                    <div class="fs-4 fw-bold text-success" id="admin-stat-students">--</div>
                    <div class="small text-secondary">Học viên</div>
                </div>
                <div class="border-start px-3">
                    <div class="fs-4 fw-bold text-warning" id="admin-stat-pending">--</div>
                    <div class="small text-secondary">Chờ duyệt</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row px-lg-4">
        <div class="col-md-3 col-lg-2 mb-4 mb-md-0">
            <div class="admin-sidebar">
                <h4 class="fw-bold text-primary-custom mb-4"><i class="bi bi-shield-lock"></i> Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link admin-tab active" onclick="switchTab(this, 'users')"><i class="bi bi-people"></i> Quản lý Học viên</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link admin-tab" onclick="switchTab(this, 'tutors')"><i class="bi bi-person-badge"></i> Quản lý Gia sư</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link admin-tab" onclick="switchTab(this, 'bookings')"><i class="bi bi-calendar-check"></i> Đăng ký (Bookings)</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link admin-tab" onclick="switchTab(this, 'classrooms')"><i class="bi bi-journal-bookmark"></i> Quản lý Lớp học</a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="#" class="nav-link admin-tab d-flex justify-content-between align-items-center" onclick="switchTab(this, 'tutors/pending')">
                            <span><i class="bi bi-check-circle"></i> Duyệt Hồ sơ</span>
                            <span id="pending-badge" class="badge bg-danger rounded-pill d-none">0</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="#" class="nav-link admin-tab d-flex justify-content-between align-items-center" onclick="switchTab(this, 'classrooms/pending-deletion')">
                            <span><i class="bi bi-trash"></i> Duyệt Xóa Lớp</span>
                            <span id="pending-del-badge" class="badge bg-danger rounded-pill d-none">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-9 col-lg-10">
            <div class="admin-content">
                <h2 id="page-title" class="fw-bold mb-4">Quản lý Hệ thống</h2>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light" id="table-head">
                            <!-- Dynamic Head -->
                        </thead>
                        <tbody id="table-body">
                            <!-- Dynamic Body -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Dữ Liệu -->
<div class="modal fade" id="adminEditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-xl overflow-hidden">
            <div style="height:6px;background:var(--gradient-primary)"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold" id="adminEditModalTitle">Chỉnh sửa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4" id="adminEditModalBody">
                <!-- Nội dung form động -->
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" id="adminEditSaveBtn">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>

<script>
    let adminEditModal;
    document.addEventListener("DOMContentLoaded", () => {
        adminEditModal = new bootstrap.Modal(document.getElementById('adminEditModal'));
    });
    async function loadStats() {
        try {
            const tutors = await apiRequest('/api/admin/tutors');
            const students = await apiRequest('/api/admin/users');
            const pending = await apiRequest('/api/admin/tutors/pending');
            document.getElementById('admin-stat-tutors').textContent = (Array.isArray(tutors) ? tutors : tutors.tutors || []).length;
            document.getElementById('admin-stat-students').textContent = Array.isArray(students) ? students.length : '--';
            
            const pendingCount = Array.isArray(pending) ? pending.length : 0;
            document.getElementById('admin-stat-pending').textContent = pendingCount || '--';
            
            const badge = document.getElementById('pending-badge');
            if (pendingCount > 0) {
                badge.textContent = pendingCount;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }

            const pendingDel = await apiRequest('/api/admin/classrooms/pending-deletion');
            const pendingDelCount = Array.isArray(pendingDel) ? pendingDel.length : 0;
            const delBadge = document.getElementById('pending-del-badge');
            if (pendingDelCount > 0) {
                delBadge.textContent = pendingDelCount;
                delBadge.classList.remove('d-none');
            } else {
                delBadge.classList.add('d-none');
            }
        } catch(e) {}
    }

    document.addEventListener("DOMContentLoaded", async () => {
        const user = getUser();
        if (!user || user.role !== 'admin') {
            showToast("Bạn không có quyền truy cập trang này!");
            setTimeout(() => window.location.href = '/auth/login', 1500);
        } else {
            loadData('users');
            loadStats();
        }
    });

    function switchTab(element, type) {
        document.querySelectorAll('.admin-tab').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        loadData(type);
    }

    async function loadData(type) {
        try {
            let title = 'Quản lý Hệ thống';
            if (type === 'users') title = 'Quản lý Học viên';
            if (type === 'tutors') title = 'Quản lý Gia sư';
            if (type === 'bookings') title = 'Quản lý Bookings';
            if (type === 'classrooms') title = 'Quản lý Lớp học';
            if (type === 'tutors/pending') title = 'Xét duyệt Hồ sơ Gia sư';
            if (type === 'classrooms/pending-deletion') title = 'Xét duyệt Xóa Lớp học';

            document.getElementById('page-title').innerText = title;
            const data = await apiRequest(`/api/admin/${type}`);
            renderTable(type, data);
        } catch (err) {
            showToast("Lỗi tải dữ liệu: " + err.message);
        }
    }

    async function approveTutor(id) {
        if (!confirm('Xác nhận duyệt hồ sơ gia sư này?')) return;
        try {
            await apiRequest(`/api/admin/tutors/${id}/approve`, "PUT");
            showToast("Đã duyệt thành công!");
            loadData('tutors/pending');
            loadStats();
        } catch (err) {
            showToast("Lỗi duyệt: " + err.message);
        }
    }

    async function rejectTutor(id) {
        if (!confirm('Xác nhận TỪ CHỐI hồ sơ này? (Gia sư sẽ phải cập nhật lại)')) return;
        try {
            await apiRequest(`/api/admin/tutors/${id}/reject`, "PUT");
            showToast("Đã từ chối hồ sơ!");
            loadData('tutors/pending');
            loadStats();
        } catch (err) {
            showToast("Lỗi từ chối: " + err.message);
        }
    }

    async function approveDeleteClass(id) {
        if (!confirm('Xác nhận XÓA HOÀN TOÀN lớp học này? Mọi dữ liệu học viên, điểm danh sẽ bị xóa!')) return;
        try {
            await apiRequest(`/api/admin/classrooms/${id}/approve-delete`, "DELETE");
            showToast("Đã xóa lớp thành công!");
            loadData('classrooms/pending-deletion');
            loadStats();
        } catch (err) {
            showToast("Lỗi duyệt xóa: " + err.message);
        }
    }

    async function rejectDeleteClass(id) {
        if (!confirm('Xác nhận từ chối yêu cầu xóa lớp này?')) return;
        try {
            await apiRequest(`/api/admin/classrooms/${id}/reject-delete`, "PUT");
            showToast("Đã hủy yêu cầu xóa lớp!");
            loadData('classrooms/pending-deletion');
            loadStats();
        } catch (err) {
            showToast("Lỗi từ chối xóa: " + err.message);
        }
    }

    function renderTable(type, data) {
        const thead = document.getElementById('table-head');
        const tbody = document.getElementById('table-body');
        thead.innerHTML = '';
        tbody.innerHTML = '';

        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center text-secondary py-4">Chưa có dữ liệu</td></tr>';
            return;
        }

        // Custom render for pending tutors
        if (type === 'tutors/pending') {
            const list = Array.isArray(data) ? data : (data.tutors || []);
            thead.innerHTML = `<tr><th>ID</th><th>Gia sư</th><th>Chuyên môn</th><th>Học phí</th><th>Tài liệu</th><th>Hành động</th></tr>`;
            
            if (list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-secondary py-5"><h4>Tuyệt vời!</h4><p>Đã duyệt hết tất cả hồ sơ.</p></td></tr>';
                return;
            }

            list.forEach(t => {
                const avatarStr = t.avatarUrl && t.avatarUrl.length > 5 ? `<img src="${t.avatarUrl}" style="width:40px;height:40px;object-fit:cover;border-radius:50%;box-shadow:0 2px 5px rgba(0,0,0,0.1)">` : '';
                const certStr = t.certificateUrl && t.certificateUrl.length > 5 ? `<a href="${t.certificateUrl}" target="_blank" class="badge bg-info text-decoration-none"><i class="bi bi-award"></i> Xem HSK</a>` : '<span class="badge bg-secondary">Thiếu HSK</span>';
                const rate = t.hourlyRate ? t.hourlyRate.toLocaleString() : '0';
                const spec = t.specialization || 'Chưa có';
                
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-secondary">#${t.userId}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                ${avatarStr}
                                <div>
                                    <div class="fw-bold text-primary-custom">${t.name}</div>
                                    <div class="small text-secondary">${t.gender || 'Chưa rõ'}</div>
                                </div>
                            </div>
                        </td>
                        <td>${spec}</td>
                        <td class="fw-semibold text-danger">${rate}đ</td>
                        <td>${certStr}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" onclick="approveTutor(${t.id})"><i class="bi bi-check2-circle"></i> Duyệt</button>
                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm" onclick="rejectTutor(${t.id})"><i class="bi bi-x-circle"></i> Từ chối</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            return;
        }

        // Custom render for pending class deletions
        if (type === 'classrooms/pending-deletion') {
            const list = Array.isArray(data) ? data : [];
            thead.innerHTML = `<tr><th>ID Lớp</th><th>Tên Lớp</th><th>Khai giảng</th><th>Số buổi</th><th>Gia sư yêu cầu</th><th>Hành động</th></tr>`;
            
            if (list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-secondary py-5"><h4>Tuyệt vời!</h4><p>Không có yêu cầu xóa lớp nào đang chờ xử lý.</p></td></tr>';
                return;
            }

            list.forEach(c => {
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-secondary">#${c.id}</span></td>
                        <td class="fw-bold text-primary-custom">${c.name}</td>
                        <td>${c.startDate || 'Chưa định'}</td>
                        <td>${c.totalSessions}</td>
                        <td class="fw-semibold text-dark">GS-${c.tutorId}: ${c.tutorName}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" onclick="approveDeleteClass(${c.id})"><i class="bi bi-trash"></i> Xóa ngay</button>
                                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm" onclick="rejectDeleteClass(${c.id})"><i class="bi bi-x-circle"></i> Từ chối</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            return;
        }

        const list = Array.isArray(data) ? data : (data.data || data.$values || []);
        
        if (list.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center text-secondary py-5"><h4>Chưa có dữ liệu</h4><p>Danh sách hiện tại đang trống.</p></td></tr>';
            return;
        }

        if (type === 'users') {
            const students = list.filter(u => u.role === 'student' || u.role === 'admin' || u.role === 'tutor');
            thead.innerHTML = `<tr><th>ID</th><th>Tên</th><th>Email</th><th>Vai trò</th><th>Hành động</th></tr>`;
            students.forEach(u => {
                let roleBadge = '<span class="badge bg-secondary">Không rõ</span>';
                if(u.role === 'student') roleBadge = '<span class="badge bg-success">Học viên</span>';
                if(u.role === 'tutor') roleBadge = '<span class="badge bg-primary">Gia sư</span>';
                if(u.role === 'admin') roleBadge = '<span class="badge bg-dark">Admin</span>';
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-light text-dark border">#${u.id}</span></td>
                        <td class="fw-bold text-primary-custom"><i class="bi bi-person-circle me-2 text-secondary"></i>${u.name}</td>
                        <td class="text-secondary">${u.email}</td>
                        <td>${roleBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" onclick='showEditModal("users", ${JSON.stringify(u).replace(/'/g, "&#39;")})'><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="deleteEntity('users', ${u.id})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
            });
        } else if (type === 'tutors') {
            thead.innerHTML = `<tr><th>ID</th><th>Tên Gia sư</th><th>Chuyên môn</th><th>Học phí</th><th>Trạng thái</th><th>Hành động</th></tr>`;
            list.forEach(t => {
                const rate = t.hourlyRate ? t.hourlyRate.toLocaleString() + 'đ/h' : '<span class="text-secondary">Chưa có</span>';
                const status = t.isApproved ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã duyệt</span>' : '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Chờ duyệt</span>';
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-light text-dark border">#${t.id}</span></td>
                        <td class="fw-bold text-primary-custom">${t.name}</td>
                        <td>${t.specialization || '<span class="text-secondary">Chưa có</span>'}</td>
                        <td class="fw-semibold text-danger">${rate}</td>
                        <td>${status}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" onclick='showEditModal("tutors", ${JSON.stringify(t).replace(/'/g, "&#39;")})'><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="deleteEntity('tutors', ${t.id})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
            });
        } else if (type === 'bookings') {
            thead.innerHTML = `<tr><th>ID</th><th>Mã Học viên</th><th>Mã Gia sư</th><th>Trạng thái</th><th>Ghi chú</th><th>Hành động</th></tr>`;
            list.forEach(b => {
                let statusBadge = '<span class="badge bg-warning text-dark">Chờ duyệt</span>';
                if (b.status === 'accepted') statusBadge = '<span class="badge bg-success">Đã chấp nhận</span>';
                if (b.status === 'paid') statusBadge = '<span class="badge bg-info text-dark"><i class="bi bi-cash-coin"></i> Đã thanh toán</span>';
                if (b.status === 'rejected') statusBadge = '<span class="badge bg-danger">Từ chối</span>';
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-light text-dark border">#${b.id}</span></td>
                        <td><span class="fw-bold">HS-${b.studentId}</span></td>
                        <td><span class="fw-bold text-primary-custom">GS-${b.tutorId}</span></td>
                        <td>${statusBadge}</td>
                        <td class="text-secondary small">${b.note || 'Không có'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" onclick='showEditModal("bookings", ${JSON.stringify(b).replace(/'/g, "&#39;")})'><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="deleteEntity('bookings', ${b.id})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
            });
        } else if (type === 'classrooms') {
            thead.innerHTML = `<tr><th>ID</th><th>Tên Lớp</th><th>Khai giảng</th><th>Số buổi</th><th>Mã Gia sư</th><th>Yêu cầu xóa</th><th>Hành động</th></tr>`;
            list.forEach(c => {
                const delReq = c.deleteRequested ? '<span class="badge bg-danger">Có</span>' : '<span class="badge bg-secondary">Không</span>';
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-light text-dark border">#${c.id}</span></td>
                        <td class="fw-bold text-primary-custom">${c.name}</td>
                        <td>${c.startDate || 'Chưa rõ'}</td>
                        <td>${c.totalSessions}</td>
                        <td><span class="fw-bold">GS-${c.tutorId}</span> (${c.tutorName})</td>
                        <td>${delReq}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" onclick='showEditModal("classrooms", ${JSON.stringify(c).replace(/'/g, "&#39;")})'><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="deleteEntity('classrooms', ${c.id})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
            });
        }
    }

    let currentEditType = '';
    let currentEditId = 0;

    function showEditModal(type, data) {
        currentEditType = type;
        currentEditId = data.id;
        const body = document.getElementById('adminEditModalBody');
        const title = document.getElementById('adminEditModalTitle');

        if (type === 'users') {
            title.innerText = `Sửa Học viên #${data.id}`;
            body.innerHTML = `
                <div class="mb-3"><label class="form-label">Tên</label><input type="text" id="edit-name" class="form-control" value="${data.name}"></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" id="edit-email" class="form-control" value="${data.email}"></div>
                <div class="mb-3"><label class="form-label">Vai trò</label>
                    <select id="edit-role" class="form-select">
                        <option value="student" ${data.role === 'student' ? 'selected' : ''}>Học viên</option>
                        <option value="tutor" ${data.role === 'tutor' ? 'selected' : ''}>Gia sư</option>
                        <option value="admin" ${data.role === 'admin' ? 'selected' : ''}>Admin</option>
                    </select>
                </div>
            `;
        } else if (type === 'tutors') {
            title.innerText = `Sửa Gia sư #${data.id}`;
            body.innerHTML = `
                <div class="mb-3"><label class="form-label">Tên Gia sư</label><input type="text" id="edit-name" class="form-control" value="${data.name}"></div>
                <div class="mb-3"><label class="form-label">Chuyên môn</label><input type="text" id="edit-spec" class="form-control" value="${data.specialization || ''}"></div>
                <div class="mb-3"><label class="form-label">Học phí (VND)</label><input type="number" id="edit-rate" class="form-control" value="${data.hourlyRate}"></div>
                <div class="mb-3"><label class="form-label">Trạng thái duyệt</label>
                    <select id="edit-approved" class="form-select">
                        <option value="true" ${data.isApproved ? 'selected' : ''}>Đã duyệt</option>
                        <option value="false" ${!data.isApproved ? 'selected' : ''}>Chờ duyệt / Khóa</option>
                    </select>
                </div>
            `;
        } else if (type === 'bookings') {
            title.innerText = `Sửa Booking #${data.id}`;
            body.innerHTML = `
                <div class="mb-3"><label class="form-label">Trạng thái</label>
                    <select id="edit-status" class="form-select">
                        <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>Chờ duyệt</option>
                        <option value="accepted" ${data.status === 'accepted' ? 'selected' : ''}>Đã chấp nhận</option>
                        <option value="paid" ${data.status === 'paid' ? 'selected' : ''}>Đã thanh toán</option>
                        <option value="rejected" ${data.status === 'rejected' ? 'selected' : ''}>Từ chối</option>
                    </select>
                </div>
            `;
        } else if (type === 'classrooms') {
            title.innerText = `Sửa Lớp học #${data.id}`;
            body.innerHTML = `
                <div class="mb-3"><label class="form-label">Tên lớp</label><input type="text" id="edit-name" class="form-control" value="${data.name}"></div>
                <div class="mb-3"><label class="form-label">Khai giảng</label><input type="text" id="edit-start" class="form-control" value="${data.startDate}"></div>
                <div class="mb-3"><label class="form-label">Số buổi</label><input type="number" id="edit-sessions" class="form-control" value="${data.totalSessions}"></div>
            `;
        }

        adminEditModal.show();
    }

    document.getElementById('adminEditSaveBtn').addEventListener('click', async () => {
        let payload = {};
        let url = `/api/admin/${currentEditType}/${currentEditId}`;
        
        if (currentEditType === 'users') {
            payload = { Name: document.getElementById('edit-name').value, Email: document.getElementById('edit-email').value, Role: document.getElementById('edit-role').value };
        } else if (currentEditType === 'tutors') {
            payload = { Name: document.getElementById('edit-name').value, Specialization: document.getElementById('edit-spec').value, HourlyRate: parseInt(document.getElementById('edit-rate').value), IsApproved: document.getElementById('edit-approved').value === 'true' };
            url = `/api/admin/tutors/${currentEditId}/info`;
        } else if (currentEditType === 'bookings') {
            payload = { Status: document.getElementById('edit-status').value };
        } else if (currentEditType === 'classrooms') {
            payload = { Name: document.getElementById('edit-name').value, StartDate: document.getElementById('edit-start').value, TotalSessions: parseInt(document.getElementById('edit-sessions').value) };
        }

        try {
            await apiRequest(url, "PUT", payload);
            showToast("Đã lưu thay đổi thành công!");
            adminEditModal.hide();
            loadData(currentEditType);
            loadStats();
        } catch(err) {
            showToast("Lỗi lưu dữ liệu: " + err.message);
        }
    });

    async function deleteEntity(type, id) {
        if (!confirm(`Bạn có CHẮC CHẮN muốn XÓA vĩnh viễn mục #${id} này? Dữ liệu không thể khôi phục!`)) return;
        try {
            let url = `/api/admin/${type}/${id}`;
            if(type === 'classrooms') url = `/api/admin/classrooms/${id}/approve-delete`; // Tái sử dụng logic xóa lớp cũ
            
            await apiRequest(url, "DELETE");
            showToast("Đã xóa thành công!");
            loadData(type);
            loadStats();
        } catch(err) {
            showToast("Lỗi xóa: " + err.message);
        }
    }
</script>

<?php include 'Views/layouts/footer.php'; ?>

