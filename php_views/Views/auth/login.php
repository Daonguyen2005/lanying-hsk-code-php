<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Đăng nhập vào Lanying HSK - Nền tảng gia sư tiếng Trung tích hợp AI">
    <title>Đăng nhập | Lanying HSK</title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/public/css/style.css?v=3">
</head>
<body>
    <div class="bg-blobs"></div>
    <div class="toast-custom" id="toast"></div>

    <div class="container login-wrapper">
        <div class="glass-panel login-card p-4 p-md-5 animate-fade-up">
            
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none">
                    <span class="logo-icon">&#20013;</span>
                    <h2 class="fw-bold text-gradient mt-2">Lanying HSK</h2>
                </a>
                <p class="text-secondary mt-2"><i class="bi bi-robot me-1 text-primary"></i>Nền tảng Gia sư Tiếng Trung AI hàng đầu</p>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-pills nav-justified mb-4 bg-light rounded-pill p-1 shadow-sm border border-white" id="authTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane" type="button" role="tab">Đăng nhập</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-pane" type="button" role="tab">Đăng ký</button>
                </li>
            </ul>

            <div class="tab-content" id="authTabsContent">
                <!-- Login Form -->
                <div class="tab-pane fade show active" id="login-pane" role="tabpanel">
                    <form id="login-form" onsubmit="handleLogin(event)">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark ms-2"><i class="bi bi-person me-1"></i>Tên đăng nhập</label>
                            <input type="text" class="form-control-glass w-100" id="login_username" required placeholder="Nhập tên đăng nhập">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark ms-2"><i class="bi bi-lock me-1"></i>Mật khẩu</label>
                            <input type="password" class="form-control-glass w-100" id="login_password" required placeholder="••••••••">
                        </div>
                        <button class="btn btn-primary-custom w-100 mt-2 fs-5" type="submit" id="btnLogin"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập ngay</button>
                    </form>
                </div>

                <!-- Register Form -->
                <div class="tab-pane fade" id="register-pane" role="tabpanel">
                    <form id="register-form" onsubmit="handleRegister(event)">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark ms-2"><i class="bi bi-person-badge me-1"></i>Họ và tên</label>
                            <input type="text" class="form-control-glass w-100" id="reg_name" required placeholder="Nguyễn Văn A">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark ms-2"><i class="bi bi-gender-ambiguous me-1"></i>Giới tính</label>
                            <select class="form-select form-control-glass w-100" id="reg_gender" required>
                                <option value="" disabled selected>Chọn giới tính</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark ms-2"><i class="bi bi-at me-1"></i>Tên đăng nhập</label>
                            <input type="text" class="form-control-glass w-100" id="reg_username" required placeholder="nguyenvana">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark ms-2"><i class="bi bi-lock me-1"></i>Mật khẩu</label>
                            <input type="password" class="form-control-glass w-100" id="reg_password" required minlength="6" placeholder="Ít nhất 6 ký tự...">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark ms-2"><i class="bi bi-person-fill-gear me-1"></i>Bạn là ai?</label>
                            <select class="form-select form-control-glass w-100" id="reg_role" required>
                                <option value="student">📚 Học viên (Tìm gia sư)</option>
                                <option value="tutor">🏫 Gia sư (Dạy học)</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-custom w-100 mt-2 fs-5" type="submit" id="btnReg"><i class="bi bi-person-plus me-2"></i>Tạo tài khoản</button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="/" class="text-secondary text-decoration-none small hover-primary"><i class="bi bi-arrow-left"></i> Quay lại trang chủ</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/api.js"></script>
    <script>
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 3000);
        }

        async function handleLogin(e) {
            e.preventDefault();
            const btn = document.getElementById('btnLogin');
            btn.textContent = 'Đang xử lý...'; btn.disabled = true;

            const u = document.getElementById('login_username').value;
            const p = document.getElementById('login_password').value;

            try {
                const res = await AuthAPI.login(u, p);
                localStorage.setItem('lanying_token', res.access_token);
                localStorage.setItem('lanying_user', JSON.stringify({
                    id: res.user.id,
                    name: res.user.name,
                    role: res.user.role,
                    hasCompletedSurvey: res.user.hasCompletedSurvey
                }));
                
                showToast('Đăng nhập thành công! Đang chuyển hướng...');
                setTimeout(() => {
                    const role = res.user.role;
                    if (role === 'admin') {
                        window.location.href = '/admin';
                    } else if (role === 'tutor') {
                        window.location.href = '/tutor/dashboard';
                    } else {
                        if (res.user.hasCompletedSurvey === false) {
                            window.location.href = '/student/survey';
                        } else {
                            window.location.href = '/student/dashboard';
                        }
                    }
                }, 1000);
            } catch (err) {
                showToast(`Đăng nhập thất bại: ${err.message}`);
                btn.textContent = 'Đăng nhập ngay'; btn.disabled = false;
            }
        }

        async function handleRegister(e) {
            e.preventDefault();
            const btn = document.getElementById('btnReg');
            btn.textContent = 'Đang xử lý...'; btn.disabled = true;

            const n = document.getElementById('reg_name').value;
            const u = document.getElementById('reg_username').value;
            const p = document.getElementById('reg_password').value;
            const r = document.getElementById('reg_role').value;
            const g = document.getElementById('reg_gender').value;

            try {
                await AuthAPI.register(n, u, p, r, g);
                showToast('Đăng ký thành công! Vui lòng đăng nhập.');
                // Switch to login tab
                const loginTab = new bootstrap.Tab(document.getElementById('login-tab'));
                loginTab.show();
                document.getElementById('login_username').value = u;
                document.getElementById('login_password').value = '';
                document.getElementById('register-form').reset();
            } catch (err) {
                showToast(`Đăng ký thất bại: ${err.message}`);
            } finally {
                btn.textContent = 'Tạo tài khoản'; btn.disabled = false;
            }
        }
    </script>
</body>
</html>
