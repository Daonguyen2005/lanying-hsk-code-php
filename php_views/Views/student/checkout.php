<?php include 'Views/layouts/header.php'; ?>

<style>
.checkout-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.8);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}
.qr-container {
    background: white;
    padding: 1rem;
    border-radius: 1.25rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    display: inline-block;
    position: relative;
    overflow: hidden;
    border: 1px solid #f1f5f9;
}
.qr-container img {
    max-width: 260px;
    height: auto;
    display: block;
    border-radius: 0.75rem;
}
.payment-tabs {
    background: #f8fafc;
    padding: 0.25rem;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
}
.payment-tabs .nav-link {
    color: #64748b;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.2s ease;
}
.payment-tabs .nav-link.active {
    background-color: #fff;
    color: #2563eb;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.amount-text {
    font-size: 1.75rem;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>

<div class="container mt-5 py-5 min-vh-100 d-flex justify-content-center align-items-center">
    <div class="checkout-card rounded-4 p-4 w-100" style="max-width: 500px;">
        
        <div id="loading-state" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-secondary">Đang khởi tạo giao dịch...</p>
        </div>

        <div id="payment-state" style="display: none;">
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1">Thanh toán Đặt lịch</h4>
                <p class="text-secondary small">Vui lòng hoàn tất thanh toán để nhận lớp</p>
            </div>

            <div class="bg-light rounded-4 p-4 mb-4 border border-light shadow-sm">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <span class="text-secondary fw-medium">Mã đơn hàng:</span>
                    <span class="fw-bold font-monospace bg-white px-2 py-1 rounded border" id="order-code">--</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary fw-medium">Tổng thanh toán:</span>
                    <span class="fw-bold amount-text" id="amount">--</span>
                </div>
            </div>

            <ul class="nav nav-pills payment-tabs nav-fill mb-4 gap-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="pill" type="button" onclick="switchMethod('vietqr')">
                        <i class="bi bi-bank me-2"></i>VietQR
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="pill" type="button" onclick="switchMethod('momo')">
                        <i class="bi bi-wallet2 me-2"></i>MoMo
                    </button>
                </li>
            </ul>

            <div class="text-center mb-4">
                <div class="qr-container">
                    <img id="qr-image" src="" alt="QR Code">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white justify-content-center align-items-center" id="qr-loading" style="display: flex; opacity: 0.9;">
                        <div class="spinner-border text-primary spinner-border-sm"></div>
                    </div>
                </div>
                <p class="text-secondary small mt-3 mb-0" id="qr-instruction">Mở ứng dụng ngân hàng hoặc MoMo để quét mã VietQR này</p>
            </div>

            <div class="mt-3 d-flex flex-column gap-2">
                <!-- Thông báo đang tự động phát hiện -->
                <div id="sepay-auto-note" class="alert alert-info border-0 rounded-3 d-flex align-items-center mb-0">
                    <div class="me-3">
                        <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Đang chờ thanh toán tự động...</div>
                        <div class="small text-secondary">Chuyển khoản xong, trang sẽ <strong>tự động nhảy</strong> mà không cần nhấn gì. Nội dung chuyển khoản: <code class="text-primary" id="auto-order-code"></code></div>
                    </div>
                </div>
                <!-- Nút dự phòng: chỉ hiện khi SePay token chưa có -->
                <button id="confirm-btn" class="btn btn-success btn-lg rounded-pill fw-bold py-3 shadow" onclick="confirmPayment()" style="display:none;">
                    <i class="bi bi-check2-circle me-2 fs-5"></i>Tôi đã chuyển tiền xong ✓
                </button>
            </div>


        </div>

        <div id="success-state" style="display: none;" class="text-center py-4">
            <div style="font-size: 5rem;">🎉</div>
            <h4 class="fw-bold mt-3 mb-2 text-success">Thanh toán thành công!</h4>
            <p class="text-secondary mb-4">Lớp học đã được <strong>mở khóa</strong> cho bạn. Vào thời khóa biểu để xem lịch học ngay!</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/student/schedule" class="btn btn-success rounded-pill px-4 fw-bold">
                    <i class="bi bi-calendar-check me-2"></i>Xem thời khóa biểu
                </a>
                <a href="/chat" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-chat-dots me-2"></i>Nhắn tin Gia sư
                </a>
            </div>
        </div>
        
    </div>
</div>

<!-- Confetti Canvas -->
<canvas id="confetti" style="position:fixed; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:9999; display:none;"></canvas>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const bookingId = urlParams.get('booking_id');
    const amountParam = urlParams.get('amount');
    const amount = (amountParam && amountParam !== 'undefined' && !isNaN(amountParam)) ? parseInt(amountParam) : 100000;
    const orderCode = 'LY' + String(bookingId).padStart(6, '0');
    
    let currentPaymentId = null;
    let pollInterval = null;

    document.addEventListener("DOMContentLoaded", () => {
        if (!bookingId) {
            alert("Lỗi: Không tìm thấy mã đặt lịch");
            window.location.href = '/student/dashboard';
            return;
        }
        
        // INSTANT RENDER
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('payment-state').style.display = 'block';
        document.getElementById('order-code').textContent = orderCode;
        document.getElementById('amount').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        
        renderQR('vietqr');
        
        // Hiện mã trong phần auto-detect
        const autoCodeEl = document.getElementById('auto-order-code');
        if (autoCodeEl) autoCodeEl.textContent = orderCode;
        
        // Gọi API ngầm để lưu DB và bắt đầu polling
        silentCreatePayment('vietqr');
    });

    function renderQR(method) {
        document.getElementById('qr-loading').style.display = 'flex';
        
        let bankId = "MBBank";
        const accountNo = "0374345803";
        
        if (method === 'momo') {
            bankId = "MoMo";
            document.getElementById('qr-instruction').innerHTML = '<strong>Mở ứng dụng MoMo</strong> để quét mã QR này';
        } else {
            document.getElementById('qr-instruction').innerHTML = 'Mở ứng dụng ngân hàng hoặc MoMo để quét mã VietQR này';
        }
        
        const qrUrl = `https://qr.sepay.vn/img?bank=${bankId}&acc=${accountNo}&amount=${amount}&des=${orderCode}`;
        
        // Tự ẩn loading sau 2s
        const fallbackTimeout = setTimeout(() => {
            document.getElementById('qr-loading').style.display = 'none';
        }, 2000);

        const img = document.getElementById('qr-image');
        img.onload = () => {
            clearTimeout(fallbackTimeout);
            document.getElementById('qr-loading').style.display = 'none';
        };
        img.onerror = () => {
            document.getElementById('qr-instruction').innerHTML = '<span class="text-danger small">Lỗi tải ảnh QR</span>';
        };
        img.src = qrUrl;
    }

    async function silentCreatePayment(method) {
        try {
            const res = await apiRequest("/api/payments/create", "POST", { bookingId: parseInt(bookingId), method: method });
            currentPaymentId = res.paymentId;

            // Bắt đầu auto-polling SePay mỗi 5 giây
            if (!pollInterval) {
                pollInterval = setInterval(checkSePayStatus, 5000);
                console.log('🔄 Bắt đầu SePay auto-polling cho payment ID:', currentPaymentId);
            }
        } catch (err) {
            console.error("Lỗi tạo thanh toán ngầm: ", err.message);
        }
    }

    window.switchMethod = function(method) {
        renderQR(method);
    }

    // Kiểm tra tự động qua SePay API
    async function checkSePayStatus() {
        if (!currentPaymentId) return;
        try {
            const res = await apiRequest(`/api/payments/${currentPaymentId}/check-sepay`);
            if (res.paid === true) {
                clearInterval(pollInterval);
                console.log('✅ SePay xác nhận thanh toán tự động!');
                showSuccess();
            } else if (res.error && res.error.includes('chưa được cấu hình')) {
                // Token chưa có → fallback: hiện nút thủ công
                document.getElementById('confirm-btn').style.display = 'block';
                document.getElementById('sepay-auto-note').style.display = 'none';
            }
        } catch (err) {
            console.error("Lỗi check SePay:", err);
        }
    }

    window.confirmPayment = async function() {
        if (!currentPaymentId) {
            // Nếu chưa tạo payment, tạo trước rồi confirm
            try {
                const res = await apiRequest("/api/payments/create", "POST", { bookingId: parseInt(bookingId), method: 'vietqr' });
                currentPaymentId = res.paymentId;
            } catch(e) {
                // Có thể payment đã tồn tại, thử lấy lại từ server
                alert('Không tìm thấy thông tin thanh toán. Vui lòng thử lại.');
                return;
            }
        }

        const btn = document.getElementById('confirm-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xác nhận...';

        try {
            await apiRequest(`/api/payments/${currentPaymentId}/student-confirm`, 'POST');
            clearInterval(pollInterval);
            showSuccess();
        } catch(err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check2-circle me-2 fs-5"></i>Tôi đã chuyển tiền xong ✓';
            alert('Lỗi xác nhận: ' + err.message);
        }
    };

    function showSuccess() {
        document.getElementById('payment-state').style.display = 'none';
        document.getElementById('success-state').style.display = 'block';
        
        // Bắn pháo hoa
        if (typeof confetti !== 'undefined') {
            document.getElementById('confetti').style.display = 'block';
            confetti({ particleCount: 200, spread: 90, origin: { y: 0.5 }, colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'] });
            setTimeout(() => confetti({ particleCount: 100, angle: 60, spread: 55, origin: { x: 0 } }), 500);
            setTimeout(() => confetti({ particleCount: 100, angle: 120, spread: 55, origin: { x: 1 } }), 700);
        }
        
        // Redirect đến thời khóa biểu sau 5 giây
        setTimeout(() => {
            window.location.href = '/student/schedule';
        }, 5000);
    }
</script>

<?php include 'Views/layouts/footer.php'; ?>
