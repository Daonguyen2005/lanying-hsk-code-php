<?php include 'Views/layouts/header.php'; ?>

<style>
    .profile-header {
        background: linear-gradient(135deg, #0ea5e9, #3b82f6);
        color: white;
        padding: 3rem 0;
        margin-top: 60px;
        border-radius: 0 0 2rem 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: -60px auto 20px;
        border-radius: 50%;
        background: white;
        padding: 5px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        background-color: #f8fafc;
        border: 2px solid #e2e8f0;
    }
    .avatar-upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        transition: transform 0.2s;
    }
    .avatar-upload-btn:hover {
        transform: scale(1.1);
    }
    .profile-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        padding: 2rem;
    }
    .tutor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #dcfce7;
        color: #166534;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>

<div class="profile-header text-center">
    <div class="container">
        <h1 class="fw-bold mb-2">Hồ sơ cá nhân</h1>
        <p class="mb-0 opacity-75">Quản lý thông tin và cài đặt của bạn</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-card">
                
                <div class="text-center mb-4">
                    <!-- Avatar Area -->
                    <div class="avatar-wrapper" id="avatarSection" style="display: none;">
                        <img src="https://ui-avatars.com/api/?name=User&background=random" id="profileAvatar" class="avatar-img" alt="Avatar">
                        <label for="avatarInput" class="avatar-upload-btn" title="Đổi ảnh đại diện">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" id="avatarInput" class="d-none" accept="image/*" onchange="handleFileUpload(this, 'avatar')">
                    </div>

                    <h4 class="fw-bold mb-1" id="displayName">Đang tải...</h4>
                    <p class="text-secondary mb-2" id="displayRole">Người dùng</p>
                    <div id="verifiedBadge" style="display: none;">
                        <span class="tutor-badge"><i class="bi bi-patch-check-fill text-success"></i> Đã được xác minh</span>
                    </div>
                </div>

                <form id="profileForm" onsubmit="saveProfile(event)">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Thông tin cơ bản</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inputName" required placeholder="Nhập họ và tên">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold">Số điện thoại</label>
                            <input type="tel" class="form-control" id="inputPhone" placeholder="Nhập số điện thoại">
                        </div>
                        <div id="studentSection" class="col-12 row g-3 m-0 p-0">
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Trình độ hiện tại</label>
                                <select class="form-select" id="inputLevel">
                                    <option value="">Chọn trình độ</option>
                                    <option value="Chưa biết gì">Chưa biết gì</option>
                                    <option value="HSK 1-2">HSK 1-2</option>
                                    <option value="HSK 3-4">HSK 3-4</option>
                                    <option value="HSK 5-6">HSK 5-6</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Mục tiêu học tập</label>
                                <input type="text" class="form-control" id="inputGoal" placeholder="Giao tiếp, thi HSK, du học...">
                            </div>
                        </div>
                    </div>

                    <!-- Khu vực dành riêng cho Gia sư -->
                    <div id="tutorSection" style="display: none;">
                        <h5 class="fw-bold mb-3 border-bottom pb-2 mt-4 text-primary"><i class="bi bi-briefcase me-2"></i>Hồ sơ Giảng dạy (Dành cho Gia sư)</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label text-secondary fw-semibold">Giới thiệu bản thân (Bio)</label>
                                <textarea class="form-control" id="inputBio" rows="3" placeholder="Viết vài dòng giới thiệu về kinh nghiệm giảng dạy của bạn..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Chuyên môn</label>
                                <input type="text" class="form-control" id="inputSpecialization" placeholder="Luyện thi HSK, Giao tiếp thực chiến...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Cấp độ giảng dạy</label>
                                <input type="text" class="form-control" id="inputTeachingLevels" placeholder="Sơ cấp, Trung cấp, HSK 1-6...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Học phí đề xuất (VND/giờ)</label>
                                <input type="number" class="form-control" id="inputHourlyRate" placeholder="Ví dụ: 150000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Chứng chỉ HSK / Sư phạm</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="inputCertificateUrl" placeholder="Chưa có file nào" readonly>
                                    <label class="btn btn-outline-primary" for="certInput">
                                        <i class="bi bi-upload"></i> Tải lên
                                    </label>
                                    <input type="file" id="certInput" class="d-none" accept="image/*,.pdf" onchange="handleFileUpload(this, 'certificate')">
                                </div>
                                <div id="certPreview" class="mt-2" style="display: none;">
                                    <a href="#" target="_blank" id="certLink" class="text-success small"><i class="bi bi-check-circle-fill"></i> Đã tải lên chứng chỉ (Xem)</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-light px-4 me-2" onclick="loadProfileData()">Hủy thay đổi</button>
                        <button type="submit" class="btn btn-primary-custom px-5" id="btnSaveProfile">
                            <span class="spinner-border spinner-border-sm d-none" id="saveSpinner" role="status" aria-hidden="true"></span>
                            Lưu hồ sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let currentAvatarUrl = '';
    let currentCertUrl = '';
    let currentUserRole = '';

    document.addEventListener("DOMContentLoaded", () => {
        const user = JSON.parse(localStorage.getItem('lanying_user') || 'null');
        if(!user) {
            window.location.href = '/';
            return;
        }
        loadProfileData();
    });

    async function loadProfileData() {
        try {
            const data = await apiRequest('/api/auth/profile');
            populateForm(data);
        } catch (e) {
            console.error(e);
            showToast("Lỗi kết nối server: " + e.message, "danger");
        }
    }

    function populateForm(data) {
        currentUserRole = data.role;
        document.getElementById('displayName').innerText = data.name;
        document.getElementById('inputName').value = data.name || '';
        document.getElementById('inputPhone').value = data.phone || '';
        document.getElementById('inputLevel').value = data.currentLevel || '';
        document.getElementById('inputGoal').value = data.learningGoal || '';
        
        const roleText = data.role === 'admin' ? 'Quản trị viên' : (data.role === 'tutor' ? 'Gia sư' : 'Học viên');
        document.getElementById('displayRole').innerText = roleText;

        if (data.role === 'tutor') {
            document.getElementById('tutorSection').style.display = 'block';
            document.getElementById('avatarSection').style.display = 'block';
            document.getElementById('studentSection').style.display = 'none';
            
            document.getElementById('inputBio').value = data.bio || '';
            document.getElementById('inputSpecialization').value = data.specialization || '';
            document.getElementById('inputTeachingLevels').value = data.teachingLevels || '';
            document.getElementById('inputHourlyRate').value = data.hourlyRate || '';
            
            if (data.avatarUrl) {
                currentAvatarUrl = data.avatarUrl;
                document.getElementById('profileAvatar').src = data.avatarUrl;
            } else {
                document.getElementById('profileAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}&background=random`;
            }

            if (data.certificateUrl) {
                currentCertUrl = data.certificateUrl;
                document.getElementById('inputCertificateUrl').value = 'Đã tải lên tệp đính kèm';
                document.getElementById('certPreview').style.display = 'block';
                document.getElementById('certLink').href = data.certificateUrl;
            }

            if (data.isApproved) {
                document.getElementById('verifiedBadge').style.display = 'block';
            }
        } else {
            // Student avatar using UI avatars
            document.getElementById('avatarSection').style.display = 'block';
            document.getElementById('profileAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}&background=random`;
            document.querySelector('.avatar-upload-btn').style.display = 'none'; // Students don't upload custom avatars yet
        }
    }

    async function handleFileUpload(inputElement, type) {
        const file = inputElement.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        try {
            showToast("Đang tải file lên...", "info");

            const res = await fetch(`${API_BASE}/api/upload`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('lanying_token')
                },
                body: formData
            });

            if (res.ok) {
                const data = await res.json();
                const url = data.url;

                if (type === 'avatar') {
                    currentAvatarUrl = url;
                    document.getElementById('profileAvatar').src = url;
                    showToast("Tải ảnh đại diện thành công!", "success");
                } else if (type === 'certificate') {
                    currentCertUrl = url;
                    document.getElementById('inputCertificateUrl').value = file.name;
                    document.getElementById('certPreview').style.display = 'block';
                    document.getElementById('certLink').href = url;
                    showToast("Tải chứng chỉ thành công!", "success");
                }
            } else {
                const err = await res.json();
                showToast(err.detail || "Upload thất bại", "danger");
            }
        } catch (e) {
            console.error(e);
            showToast("Lỗi kết nối khi upload", "danger");
        }
    }

    async function saveProfile(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveProfile');
        const spinner = document.getElementById('saveSpinner');
        btn.disabled = true;
        spinner.classList.remove('d-none');

        const payload = {
            name: document.getElementById('inputName').value.trim(),
            phone: document.getElementById('inputPhone').value.trim(),
            currentLevel: document.getElementById('inputLevel').value,
            learningGoal: document.getElementById('inputGoal').value.trim(),
        };

        if (currentUserRole === 'tutor') {
            payload.bio = document.getElementById('inputBio').value.trim();
            payload.specialization = document.getElementById('inputSpecialization').value.trim();
            payload.teachingLevels = document.getElementById('inputTeachingLevels').value.trim();
            
            const rateStr = document.getElementById('inputHourlyRate').value;
            if (rateStr) payload.hourlyRate = parseInt(rateStr);
            
            if (currentAvatarUrl) payload.avatarUrl = currentAvatarUrl;
            if (currentCertUrl) payload.certificateUrl = currentCertUrl;
        }

        try {
            const data = await apiRequest('/api/auth/profile', 'PUT', payload);
            
            showToast("Cập nhật hồ sơ thành công!", "success");
            
            // Cập nhật lại localStorage
            let user = JSON.parse(localStorage.getItem('lanying_user'));
            user.name = payload.name;
            localStorage.setItem('lanying_user', JSON.stringify(user));
            
            document.getElementById('displayName').innerText = payload.name;
            const nameToggles = document.querySelectorAll('.dropdown-toggle');
            nameToggles.forEach(t => {
                let roleLabel = 'Học viên';
                if (user.role === 'tutor') roleLabel = 'Giáo viên';
                if (user.role === 'admin') roleLabel = 'Admin';
                if(t.innerText.includes("Học sinh") || t.innerText.includes("Giáo viên") || t.innerText.includes("Admin") || t.innerText.includes("Học viên")) {
                    t.innerText = `${roleLabel} ${payload.name}`;
                }
            });
            
        } catch (err) {
            console.error(err);
            showToast("Cập nhật thất bại: " + err.message, "danger");
        } finally {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    }
</script>

<?php include 'Views/layouts/footer.php'; ?>
