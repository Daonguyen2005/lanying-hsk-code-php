import os

file_path = "c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/php_views/Views/admin/dashboard.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace HTML menu item
old_menu = """                    <li class="nav-item mt-2">
                        <a href="#" class="nav-link admin-tab bg-warning bg-opacity-10 text-warning" onclick="switchTab(this, 'tutors/pending')">
                            <i class="bi bi-check-circle"></i> Duyệt Hồ sơ 🔴
                        </a>
                    </li>"""

new_menu = """                    <li class="nav-item mt-2">
                        <a href="#" class="nav-link admin-tab bg-warning bg-opacity-10 text-warning d-flex justify-content-between align-items-center" onclick="switchTab(this, 'tutors/pending')">
                            <span><i class="bi bi-check-circle"></i> Duyệt Hồ sơ</span>
                            <span id="pending-badge" class="badge bg-danger rounded-pill d-none">0</span>
                        </a>
                    </li>"""
content = content.replace(old_menu, new_menu)

# Replace JS blocks
old_js = """    document.addEventListener("DOMContentLoaded", async () => {
        const user = getUser();
        if (!user || user.role !== 'admin') {
            showToast("Bạn không có quyền truy cập trang này!");
            setTimeout(() => window.location.href = '/auth/login', 1500);
        } else {
            loadData('users');
            // Load quick stats
            try {
                const tutors = await apiRequest('/api/admin/tutors');
                const students = await apiRequest('/api/admin/users');
                const pending = await apiRequest('/api/admin/tutors/pending');
                document.getElementById('admin-stat-tutors').textContent = (Array.isArray(tutors) ? tutors : tutors.tutors || []).length;
                document.getElementById('admin-stat-students').textContent = Array.isArray(students) ? students.length : '--';
                document.getElementById('admin-stat-pending').textContent = Array.isArray(pending) ? pending.length : '--';
            } catch(e) {}
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
            if (type === 'tutors/pending') title = 'Xét duyệt Hồ sơ Gia sư';

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
            // Refresh tutor list if it exists
            if (typeof loadTutors === 'function') loadTutors();
        } catch (err) {
            showToast("Lỗi duyệt: " + err.message);
        }
    }"""

new_js = """    async function loadStats() {
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
            if (type === 'tutors/pending') title = 'Xét duyệt Hồ sơ Gia sư';

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
    }"""
content = content.replace(old_js, new_js)

# Replace pending render inside renderTable
old_pending = """        // Custom render for pending tutors
        if (type === 'tutors/pending') {
            const list = Array.isArray(data) ? data : (data.tutors || []);
            thead.innerHTML = `<tr><th>ID Gia sư</th><th>Tên</th><th>Giới tính</th><th>Chuyên môn</th><th>Học phí</th><th>Ảnh đại diện</th><th>Chứng chỉ HSK</th><th>Hành động</th></tr>`;
            
            if (list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-secondary py-4">Chưa có hồ sơ nào cần duyệt</td></tr>';
                return;
            }

            list.forEach(t => {
                const avatarStr = t.avatarUrl && t.avatarUrl.length > 5 ? `<img src="${t.avatarUrl}" style="width:50px;height:50px;object-fit:cover;border-radius:50%;box-shadow:0 2px 5px rgba(0,0,0,0.1)">` : '<span class="text-secondary">Chưa có</span>';
                const certStr = t.certificateUrl && t.certificateUrl.length > 5 ? `<img src="${t.certificateUrl}" style="max-height:80px;cursor:pointer;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1)" onclick="window.open('${t.certificateUrl}')" title="Bấm để xem lớn">` : '<span class="text-secondary">Chưa có</span>';
                const rate = t.hourlyRate ? t.hourlyRate.toLocaleString() : '0';
                const spec = t.specialization || 'Chưa cập nhật';
                
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-secondary">#${t.userId}</span></td>
                        <td class="fw-bold text-primary-custom">${t.name}</td>
                        <td>${t.gender || 'Chưa cập nhật'}</td>
                        <td>${spec}</td>
                        <td class="fw-semibold text-danger">${rate}đ</td>
                        <td>${avatarStr}</td>
                        <td>${certStr}</td>
                        <td><button class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" onclick="approveTutor(${t.id})"><i class="bi bi-check2-circle"></i> Duyệt ngay</button></td>
                    </tr>
                `;
            });
            return;
        }"""

new_pending = """        // Custom render for pending tutors
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
        }"""
content = content.replace(old_pending, new_pending)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated dashboard.php")
