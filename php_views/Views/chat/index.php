<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin nhắn - Lanying HSK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .chat-container { 
            height: 80vh; 
            display: flex; 
            border: none; 
            border-radius: 16px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .chat-sidebar { 
            width: 320px; 
            border-right: 1px solid rgba(0,0,0,0.05); 
            background: #ffffff; 
            overflow-y: auto; 
            display: flex;
            flex-direction: column;
        }
        .chat-main { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            background: #f8fafc; 
        }
        .chat-messages { 
            flex: 1; 
            overflow-y: auto; 
            padding: 24px; 
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .chat-input-area { 
            padding: 20px 24px; 
            background: #ffffff; 
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        .msg-bubble { 
            max-width: 75%; 
            padding: 12px 18px; 
            border-radius: 20px; 
            font-size: 0.95rem;
            line-height: 1.5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            word-wrap: break-word;
        }
        .msg-sent { 
            background: linear-gradient(135deg, #3b82f6, #0ea5e9); 
            color: white; 
            align-self: flex-end; 
            border-bottom-right-radius: 4px; 
        }
        .msg-recv { 
            background: #ffffff; 
            color: #334155; 
            align-self: flex-start; 
            border-bottom-left-radius: 4px; 
            border: 1px solid rgba(0,0,0,0.03);
        }
        .contact-item { 
            padding: 16px 20px; 
            border-bottom: 1px solid rgba(0,0,0,0.03); 
            cursor: pointer; 
            transition: all 0.2s ease;
        }
        .contact-item:hover { 
            background: #f1f5f9; 
        }
        .contact-item.active { 
            background: #eff6ff; 
            border-left: 4px solid #3b82f6;
        }
        .contact-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #475569;
            margin-right: 15px;
        }
        #chat-empty {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }
    </style>
</head>
<body>
    <?php include 'Views/layouts/header.php'; ?>

    <div class="container mt-4 mb-5 animate-fade-up">
        <h3 class="fw-bold mb-4 text-gradient"><i class="bi bi-chat-dots-fill me-2"></i>Hộp thư tin nhắn</h3>
        <div class="chat-container">
            <!-- Sidebar: Danh sách người liên hệ -->
            <div class="chat-sidebar" id="contact-list">
                <div class="p-4 text-center text-secondary">
                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                    <div>Đang tải liên hệ...</div>
                </div>
            </div>

            <!-- Khung Chat -->
            <div class="chat-main position-relative" style="display: none;" id="chat-panel">
                <!-- Overlay khóa cho Admin -->
                <div id="admin-lock-overlay" class="position-absolute top-0 start-0 w-100 h-100 flex-column justify-content-center align-items-center" style="background: rgba(15, 23, 42, 0.85); z-index: 10; display: none !important; backdrop-filter: blur(8px);">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-lg mb-4" style="width: 100px; height: 100px; font-size: 3rem; cursor: pointer; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onclick="unlockAdminChat()" onmouseover="this.style.transform='scale(1.1) rotate(-10deg)'" onmouseout="this.style.transform='scale(1) rotate(0)'">
                        <i class="bi bi-lock-fill text-primary"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Trò chuyện bị khóa</h4>
                    <p class="text-light opacity-75">Nhấp vào ổ khóa để xác nhận mở khóa trò chuyện</p>
                </div>

                <div class="bg-white p-3 border-bottom d-flex align-items-center shadow-sm" style="z-index: 5;">
                    <div class="contact-avatar" id="chat-partner-avatar" style="width: 40px; height: 40px; font-size: 1.1rem; margin-right: 12px; cursor: pointer;" onclick="viewTutorProfileByUserId(currentPartnerId, document.getElementById('chat-partner-name').textContent)" title="Xem hồ sơ">?</div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark" id="chat-partner-name" style="cursor: pointer;" onclick="viewTutorProfileByUserId(currentPartnerId, this.textContent)" title="Xem hồ sơ">Người nhận</h5>
                        <small class="text-success"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>Đang hoạt động</small>
                    </div>
                </div>
                
                <div class="chat-messages" id="chat-messages">
                    <!-- Tin nhắn -->
                </div>
                
                <div class="chat-input-area">
                    <div class="input-group bg-light rounded-pill p-1 border">
                        <input type="text" id="msg-input" class="form-control border-0 bg-transparent ps-4" placeholder="Nhập tin nhắn của bạn..." onkeypress="handleEnter(event)" style="box-shadow: none;">
                        <button class="btn btn-primary rounded-pill px-4" onclick="sendMsg()"><i class="bi bi-send-fill me-1"></i> Gửi</button>
                    </div>
                </div>
            </div>
            
            <div class="chat-main align-items-center justify-content-center" id="chat-empty">
                <div class="text-center opacity-50">
                    <i class="bi bi-chat-square-dots display-1 text-primary mb-3 d-block"></i>
                    <h4 class="fw-bold text-secondary">Hộp thư Lanying</h4>
                    <p class="text-muted">Chọn một cuộc trò chuyện từ danh sách để bắt đầu</p>
                </div>
            </div>
        </div>
    </div>

    <script src="/public/js/api.js"></script>
    <script>
        let currentPartnerId = null;
        let pollingInterval = null;

        document.addEventListener("DOMContentLoaded", () => {
            if (!getUser()) {
                window.location.href = '/auth/login';
            } else {
                loadContacts();
            }
        });

        async function loadContacts() {
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const autoUserId = urlParams.get('user_id');
                
                const res = await apiRequest(`/api/messages/recent${autoUserId ? '?user_id=' + autoUserId : ''}`);
                const sidebar = document.getElementById("contact-list");
                sidebar.innerHTML = '';
                
                if (res.length === 0) {
                    sidebar.innerHTML = '<div class="p-3 text-secondary text-center">Chưa có liên hệ</div>';
                    return;
                }

                let autoUserObj = null;
                res.forEach(c => {
                    if (autoUserId && c.id == autoUserId) autoUserObj = c;
                    const div = document.createElement('div');
                    div.className = 'contact-item d-flex justify-content-between align-items-center';
                    div.id = `contact-${c.id}`;
                    
                    const unreadBadge = c.unreadCount > 0 ? `<span class="badge bg-danger rounded-pill shadow-sm">${c.unreadCount}</span>` : '';
                    const avatarChar = c.name.charAt(0).toUpperCase();
                    
                    div.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="contact-avatar shadow-sm">${avatarChar}</div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">${c.name}</h6>
                                <small class="text-secondary opacity-75">${c.role === 'admin' ? '<i class="bi bi-shield-lock-fill text-warning"></i> Quản trị viên' : (c.role === 'tutor' ? 'Gia sư' : 'Học viên')}</small>
                            </div>
                        </div>
                        ${unreadBadge}
                    `;
                    div.onclick = () => openChat(c.id, c.name);
                    sidebar.appendChild(div);
                });
                
                if (autoUserObj) {
                    openChat(autoUserObj.id, autoUserObj.name);
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function openChat(partnerId, partnerName) {
            document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
            const contactEl = document.getElementById(`contact-${partnerId}`);
            if (contactEl) contactEl.classList.add('active');
            
            document.getElementById('chat-empty').style.display = 'none';
            document.getElementById('chat-panel').style.display = 'flex';
            document.getElementById('chat-partner-name').textContent = partnerName;
            document.getElementById('chat-partner-avatar').textContent = partnerName.charAt(0).toUpperCase();
            
            currentPartnerId = partnerId;
            
            const overlay = document.getElementById('admin-lock-overlay');
            if (partnerName === "Super Admin" && getUser().role !== "admin") {
                if (!window.unlockedAdmins) window.unlockedAdmins = {};
                if (!window.unlockedAdmins[partnerId]) {
                    overlay.style.setProperty('display', 'flex', 'important');
                } else {
                    overlay.style.setProperty('display', 'none', 'important');
                }
            } else {
                overlay.style.setProperty('display', 'none', 'important');
            }

            await loadMessages();

            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(loadMessages, 3000); // Poll every 3s
        }

        function unlockAdminChat() {
            if (confirm("Bạn có chắc chắn muốn mở khóa trò chuyện với Admin?")) {
                if (!window.unlockedAdmins) window.unlockedAdmins = {};
                window.unlockedAdmins[currentPartnerId] = true;
                document.getElementById('admin-lock-overlay').style.setProperty('display', 'none', 'important');
            }
        }

        async function loadMessages() {
            if (!currentPartnerId) return;
            try {
                const msgs = await apiRequest(`/api/messages/history/${currentPartnerId}`);
                const container = document.getElementById('chat-messages');
                container.innerHTML = '';
                
                const myId = getUser().id;
                msgs.forEach(m => {
                    const div = document.createElement('div');
                    div.className = `msg-bubble ${m.senderId === myId ? 'msg-sent' : 'msg-recv'}`;
                    div.textContent = m.content;
                    container.appendChild(div);
                });
                container.scrollTop = container.scrollHeight;
            } catch (err) {
                console.error(err);
            }
        }

        async function sendMsg() {
            const input = document.getElementById('msg-input');
            const txt = input.value.trim();
            if (!txt || !currentPartnerId) return;
            
            try {
                input.value = '';
                await apiRequest("/api/messages/send", "POST", { receiverId: currentPartnerId, content: txt });
                loadMessages();
            } catch (err) {
                alert("Lỗi gửi tin: " + err.message);
            }
        }

        function handleEnter(e) {
            if (e.key === 'Enter') sendMsg();
        }
    </script>
    <?php include 'Views/layouts/footer.php'; ?>
