    <!-- Footer Section -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row gy-4">
                <!-- Column 1: Info -->
                <div class="col-lg-4 col-md-6">
                    <a class="d-flex align-items-center gap-2 text-decoration-none mb-3" href="/">
                        <span class="logo-icon" style="font-size:2rem;background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">&#20013;</span>
                        <h3 class="m-0 fs-4 fw-bold text-gradient">Lanying HSK</h3>
                    </a>
                    <p class="text-secondary">Nền tảng kết nối gia sư tiếng Trung thông minh sử dụng AI Cosine Similarity, giúp bạn chinh phục HSK với lộ trình cá nhân hóa.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle p-2" style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle p-2" style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle p-2" style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle p-2" style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3 text-white">Liên kết nhanh</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="/" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-house me-2"></i>Trang chủ</a></li>
                        <li><a href="/#giasu" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-people me-2"></i>Tìm gia sư</a></li>
                        <li><a href="/student/survey" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-clipboard2-check me-2"></i>Khảo sát lộ trình</a></li>
                        <li><a href="/auth/login" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập / Đăng ký</a></li>
                    </ul>
                </div>

                <!-- Column 3: For Tutors -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3 text-white">Dành cho Gia sư</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="/auth/login" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-person-badge me-2"></i>Đăng ký làm Gia sư</a></li>
                        <li><a href="/tutor/dashboard" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-layout-dashboard me-2"></i>Dashboard Gia sư</a></li>
                        <li><a href="/schedule" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-calendar3 me-2"></i>Quản lý lịch dạy</a></li>
                        <li><a href="/chat" class="text-secondary text-decoration-none hover-primary"><i class="bi bi-chat-dots me-2"></i>Nhắn tin học viên</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3 text-white">Liên hệ</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-secondary">
                        <li><i class="bi bi-geo-alt-fill me-2 text-primary"></i>TP. Hồ Chí Minh, Việt Nam</li>
                        <li><i class="bi bi-envelope-fill me-2 text-primary"></i>support@lanying-hsk.vn</li>
                        <li><i class="bi bi-telephone-fill me-2 text-primary"></i>0900 123 456</li>
                        <li class="mt-3">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                <i class="bi bi-clock me-1"></i>Hỗ trợ 24/7 qua Chatbot AI
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4" style="border-color:rgba(255,255,255,0.1);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-secondary small">
                    &copy; 2026 <strong class="text-white">Lanying HSK</strong>. Đồ án tốt nghiệp - Xây dựng website gia sư tiếng Trung tích hợp RAG & Cosine Similarity.
                </div>
                <div class="d-flex gap-3">
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill"><i class="bi bi-shield-check me-1"></i>Secure</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill"><i class="bi bi-cpu me-1"></i>AI Powered</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chatbot Floating Widget -->
    <div class="chatbot-widget animate-fade-up delay-300" onclick="toggleChat()">
        <span class="fs-4">🤖</span>
        <span>Hỏi AI tư vấn</span>
    </div>

    <!-- AI Chat Window (Glassmorphism) -->
    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <div>
                <h4>Lanying AI Assistant</h4>
                <small>Luôn sẵn sàng hỗ trợ bạn 24/7</small>
            </div>
            <button class="close-chat" onclick="toggleChat()">&times;</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-msg ai">Xin chào! Tôi là trợ lý AI của Lanying HSK. Bạn đang quan tâm đến khóa học tiếng Trung hay cần tư vấn ngữ pháp?</div>
            <div class="d-flex flex-wrap gap-2 mt-3" id="chat-suggestions">
                <span class="badge bg-light text-primary border p-2 cursor-pointer" onclick="sendSuggestion('Tìm gia sư ôn thi HSK 5')">Tìm gia sư ôn thi HSK 5</span>
                <span class="badge bg-light text-primary border p-2 cursor-pointer" onclick="sendSuggestion('Học phí gia sư giao tiếp khoảng bao nhiêu?')">Học phí gia sư giao tiếp khoảng bao nhiêu?</span>
                <span class="badge bg-light text-primary border p-2 cursor-pointer" onclick="sendSuggestion('Có gia sư nào dạy buổi tối không?')">Có gia sư nào dạy buổi tối không?</span>
            </div>
        </div>
        <div class="chat-input-row">
            <input type="text" id="chatInput" placeholder="Nhập câu hỏi của bạn..." onkeypress="handleChatKey(event)">
            <button onclick="sendMessage()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Update path to public/js -->
    <script src="/public/js/api.js"></script>
    <script>
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 3000);
        }

        // Toggle Chat
        function toggleChat() {
            const chat = document.getElementById('chatWindow');
            chat.style.display = chat.style.display === 'flex' ? 'none' : 'flex';
        }

        function handleChatKey(e) {
            if (e.key === 'Enter') sendMessage();
        }

        let chatHistory = [];

        function sendSuggestion(text) {
            document.getElementById('chatInput').value = text;
            sendMessage();
        }

        async function sendMessage() {
            const input = document.getElementById('chatInput');
            const msg = input.value.trim();
            if(!msg) return;

            const body = document.getElementById('chatBody');
            const suggestions = document.getElementById('chat-suggestions');
            if(suggestions) suggestions.style.display = 'none'; // Ẩn gợi ý sau khi bắt đầu chat
            
            // Add user message
            const userDiv = document.createElement('div');
            userDiv.className = 'chat-msg user animate-fade-up';
            userDiv.textContent = msg;
            body.appendChild(userDiv);
            
            chatHistory.push({ role: 'user', content: msg });

            input.value = '';
            body.scrollTop = body.scrollHeight;

            // Add typing indicator
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chat-msg typing animate-fade-up';
            typingDiv.textContent = 'AI đang suy nghĩ...';
            body.appendChild(typingDiv);
            body.scrollTop = body.scrollHeight;

            try {
                // Truyền thêm mảng history
                const data = await ChatAPI.send(msg, chatHistory);
                typingDiv.remove();

                const aiDiv = document.createElement('div');
                aiDiv.className = 'chat-msg ai animate-fade-up';
                aiDiv.innerHTML = data.reply.replace(/\n/g, '<br>');
                body.appendChild(aiDiv);
                
                chatHistory.push({ role: 'ai', content: data.reply });
            } catch (err) {
                typingDiv.remove();
                const errDiv = document.createElement('div');
                errDiv.className = 'chat-msg ai text-danger animate-fade-up';
                errDiv.textContent = `Lỗi: ${err.message}`;
                body.appendChild(errDiv);
            }
            body.scrollTop = body.scrollHeight;
        }

        window.allRealTutors = [];
        window.currentTutorFilter = 'all';
        window.currentTutorSearch = '';
        window.currentTutorPage = 1;
        window.tutorsPerPage = 6;

        async function loadTutors() {
            try {
                const data = await TutorAPI.getAll();
                const container = document.getElementById('tutorList');
                if(!container) return; // Only load on pages with tutorList
                
                const tutorsData = Array.isArray(data) ? data : (data.tutors || []);
                window.allRealTutors = tutorsData.filter(t => t.isApproved);

                if (window.allRealTutors.length === 0) {
                    container.innerHTML = '<div class="col-12 text-center text-secondary py-5"><h4>Hiện tại chưa có gia sư nào được duyệt hiển thị.</h4><p>Danh sách gia sư sẽ được cập nhật sau khi Admin xét duyệt hồ sơ!</p></div>';
                    return;
                }

                renderTutorsPage();
            } catch (err) {
                showToast(`Không thể tải danh sách gia sư: ${err.message}`);
            }
        }

        const tagLabels = {
            "luyen thi": "Ôn thi HSK", "giao tiep": "Giao tiếp", "thuong mai": "Thương mại", "tre em": "Trẻ em",
            "nghe noi": "Nghe - Nói", "phat am": "Phát âm chuẩn", "doc viet": "Đọc - Viết", "ngu phap": "Ngữ pháp",
            "online": "Học Online", "offline": "Học Offline", 
            "sinh vien": "Học sinh/Sinh viên", "nguoi di lam": "Người đi làm",
            "weekdays": "Giờ hành chính", "evenings": "Buổi tối", "weekends": "Cuối tuần",
            "vui ve": "Vui vẻ", "nghiem khac": "Nghiêm khắc", "kien nhan": "Kiên nhẫn", "truyen cam hung": "Truyền cảm hứng",
            "co ban": "HSK 1-2", "trung cap": "HSK 3-4", "nang cao": "HSK 5-6", "sieu cap": "HSK 7-9"
        };
        
        function formatTags(tagsStr) {
            if (!tagsStr) return [];
            return tagsStr.split(',').map(t => t.trim()).filter(t => t).map(t => tagLabels[t] || t);
        }
        
        window.renderTutorsPage = function() {
            const container = document.getElementById('tutorList');
            const paginator = document.getElementById('tutorPagination');
            if(!container) return;

            let filtered = window.allRealTutors;
            if (window.currentTutorFilter !== 'all') {
                filtered = filtered.filter(t => (t.tagsVector || '').toLowerCase().includes(window.currentTutorFilter));
            }
            if (window.currentTutorSearch) {
                filtered = filtered.filter(t => t.name.toLowerCase().includes(window.currentTutorSearch));
            }

            const totalPages = Math.ceil(filtered.length / window.tutorsPerPage);
            if (window.currentTutorPage > totalPages) window.currentTutorPage = 1;
            
            const startIdx = (window.currentTutorPage - 1) * window.tutorsPerPage;
            const pageData = filtered.slice(startIdx, startIdx + window.tutorsPerPage);

            container.innerHTML = '';
            if (pageData.length === 0) {
                container.innerHTML = '<div class="col-12 text-center text-secondary py-5"><p>Không tìm thấy gia sư phù hợp với bộ lọc.</p></div>';
            }

            pageData.forEach((tutor, index) => {
                const delayClass = `delay-${(index % 3 + 1) * 100}`;
                
                const levelLabels = formatTags(tutor.teachingLevels);
                const levels = levelLabels.length > 0 ? levelLabels.join(', ') : 'HSK 1-6';
                
                const rate = tutor.hourlyRate ? tutor.hourlyRate.toLocaleString('vi-VN') + 'đ/giờ' : 'Thỏa thuận';
                const tagsArr = formatTags(tutor.tagsVector);
                
                const avatarHtml = tutor.avatarUrl && tutor.avatarUrl.length > 5 
                    ? `<img src="${tutor.avatarUrl}" style="width:72px;height:72px;object-fit:cover;border-radius:50%;box-shadow:0 4px 12px rgba(0,0,0,0.12)" alt="${tutor.name}">` 
                    : `<div class="tutor-avatar d-flex align-items-center justify-content-center rounded-circle fw-bold" style="width:72px;height:72px;font-size:1.8rem;background:linear-gradient(135deg,#3b82f6,#0ea5e9);color:white;box-shadow:0 4px 12px rgba(59,130,246,0.35);">${tutor.name.charAt(0).toUpperCase()}</div>`;
                
                const approvedTick = tutor.isApproved ? `<span class="badge bg-success-subtle text-success rounded-pill ms-1" title="Đã được Admin xác minh"><i class="bi bi-patch-check-fill"></i> Đã được xác minh</span>` : '';
                
                const tagBadges = tagsArr.slice(0, 4).map(tag => `<span class="badge bg-info-subtle text-dark border border-info px-2 py-1 small">${tag}</span>`).join('');
                
                const specLabels = formatTags(tutor.specialization);
                const specStr = specLabels.length > 0 ? specLabels.join(' & ') : 'Gia sư Tiếng Trung';

                const card = document.createElement('div');
                card.className = `col-md-6 col-lg-4 animate-fade-up ${delayClass} tutor-card-col`;
                card.dataset.tags = tutor.tagsVector || '';
                card.dataset.tutorId = tutor.id;
                card.innerHTML = `
                    <div class="glass-panel h-100 p-4" id="tutor-card-${tutor.id}">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            ${avatarHtml}
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">${tutor.name}</h5>
                                <div>${approvedTick}</div>
                                <div class="mt-2"><span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2"><i class="bi bi-stars"></i> ${specStr}</span></div>
                            </div>
                        </div>
                        <p class="text-secondary mb-3 line-clamp-3" style="min-height: 66px; font-size: 0.9rem;">
                            ${tutor.bio || 'Gia sư tận tâm, chuyên nghiệp. Cam kết chất lượng dạy học và giúp học viên đạt mục tiêu mong muốn.'}
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-award text-warning"></i> Dạy: ${levels}</span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-currency-dollar text-success"></i> ${rate}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">${tagBadges}</div>
                        <div id="tutor-schedule-status-${tutor.id}" class="mb-3">
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3"><i class="bi bi-hourglass-split me-1"></i>Đang kiểm tra lịch...</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary-custom flex-grow-1 py-2 rounded-pill tutor-book-btn"
                                id="book-btn-${tutor.id}" data-tutor-id="${tutor.id}" onclick="bookTutor(${tutor.id})">
                                <i class="bi bi-calendar2-check me-1"></i> Đặt lịch học
                            </button>
                            <button class="btn btn-outline-secondary rounded-pill py-2 px-3" title="Xem hồ sơ" onclick="viewTutorProfile(${tutor.id}, '${tutor.name}')">
                                <i class="bi bi-person"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(card);

                // Async check schedule availability
                (async (tid) => {
                    try {
                        const scheds = await TutorAPI.getSchedule(tid);
                        const statusEl = document.getElementById(`tutor-schedule-status-${tid}`);
                        const bookBtn = document.getElementById(`book-btn-${tid}`);
                        if (!statusEl) return;
                        
                        const hasSlots = scheds && scheds.length > 0;
                        if (hasSlots) {
                            const today = new Date();
                            const nextWeek = new Date(today); nextWeek.setDate(today.getDate() + 7);
                            const fmt = d => d.toISOString().slice(0,10);
                            const upcoming = scheds.filter(s => s.scheduleDate >= fmt(today) && s.scheduleDate <= fmt(nextWeek));
                            const upcomingText = upcoming.length > 0 
                                ? `<span class="badge bg-success-subtle text-success rounded-pill px-3"><i class="bi bi-calendar-check me-1"></i>Có ${upcoming.length} buổi dự kiến tuần tới</span>`
                                : `<span class="badge bg-warning-subtle text-warning rounded-pill px-3"><i class="bi bi-calendar-event me-1"></i>Đang mở lớp</span>`;
                            statusEl.innerHTML = upcomingText;
                        } else {
                            statusEl.innerHTML = `<span class="badge bg-danger-subtle text-danger rounded-pill px-3"><i class="bi bi-x-circle me-1"></i>Chưa mở lớp</span>`;
                            if (bookBtn) {
                                bookBtn.disabled = true;
                                bookBtn.classList.remove('btn-primary-custom');
                                bookBtn.classList.add('btn-secondary');
                                bookBtn.innerHTML = '<i class="bi bi-lock me-1"></i>Chưa mở lớp';
                                bookBtn.onclick = () => showToast('⚠️ Giáo viên này chưa mở lớp! Vui lòng chọn giáo viên khác.');
                            }
                        }
                    } catch(e) {
                        const statusEl = document.getElementById(`tutor-schedule-status-${tid}`);
                        if (statusEl) statusEl.innerHTML = '';
                    }
                })(tutor.id);
            });

            if(paginator) {
                paginator.innerHTML = '';
                if(totalPages > 1) {
                    for(let i = 1; i <= totalPages; i++) {
                        const btn = document.createElement('button');
                        btn.className = `btn rounded-circle fw-bold shadow-sm ${i === window.currentTutorPage ? 'btn-primary' : 'btn-outline-primary'}`;
                        btn.style.width = '42px';
                        btn.style.height = '42px';
                        btn.textContent = i;
                        btn.onclick = () => {
                            window.currentTutorPage = i;
                            window.renderTutorsPage();
                            document.getElementById('giasu').scrollIntoView({ behavior: 'smooth' });
                        };
                        paginator.appendChild(btn);
                    }
                }
            }
        };

        window.bookTutor = async function(tutorId, defaultNote = '') {
            const user = JSON.parse(localStorage.getItem('lanying_user') || 'null');
            const token = localStorage.getItem('lanying_token');
            if (!user || !token) {
                showToast('Bạn cần đăng nhập học viên để đặt lịch!');
                setTimeout(() => window.location.href = '/auth/login', 1500);
                return;
            }
            if (user.role !== 'student') {
                showToast('Chỉ tài khoản Học viên mới có thể đặt lịch học!');
                return;
            }

            try {
                const schedules = await TutorAPI.getSchedule(tutorId);
                if (!schedules || schedules.length === 0) {
                    showToast('⚠️ Giáo viên này chưa mở lớp! Vui lòng chọn giáo viên khác.');
                    return;
                }

                // Nhóm các lịch học theo lớp (Label)
                const classes = {};
                schedules.forEach(s => {
                    const label = s.label || 'Lớp chưa đặt tên';
                    if (!classes[label]) classes[label] = [];
                    classes[label].push(s);
                });

                let classOptions = '';
                for (const [label, slots] of Object.entries(classes)) {
                    classOptions += `<option value="${label}">Lớp: ${label} (${slots.length} buổi dự kiến)</option>`;
                }

                const modalHtml = `
                    <div class="modal fade" id="dynamicBookingModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="modal-header bg-primary text-white border-0 p-4">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar2-check me-2"></i>Xác nhận đặt lịch</h5>
                                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Chọn lớp học bạn muốn tham gia <span class="text-danger">*</span></label>
                                        <select class="form-select rounded-pill" id="bookingClassSelect">
                                            ${classOptions}
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Lời nhắn cho gia sư</label>
                                        <textarea class="form-control" id="bookingNote" rows="3" placeholder="Ví dụ: Em muốn đăng ký lớp học này ạ...">${defaultNote || 'Chào thầy/cô, em muốn đăng ký học lớp này ạ.'}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                                    <button type="button" class="btn btn-primary-custom rounded-pill px-5" onclick="submitDynamicBooking(${tutorId})">
                                        <i class="bi bi-send me-2"></i>Gửi yêu cầu
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                const existing = document.getElementById('dynamicBookingModal');
                if (existing) existing.remove();
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                new bootstrap.Modal(document.getElementById('dynamicBookingModal')).show();

            } catch (err) {
                showToast(`Lỗi đặt lịch: ${err.message}`);
            }
        };

        window.submitDynamicBooking = async function(tutorId) {
            const className = document.getElementById('bookingClassSelect').value;
            let note = document.getElementById('bookingNote').value.trim();
            if (className) {
                note = `[Đăng ký ${className}] - ${note}`;
            }
            try {
                await TutorAPI.book(tutorId, note);
                bootstrap.Modal.getInstance(document.getElementById('dynamicBookingModal')).hide();
                showToast('Đăng ký lớp thành công! Gia sư sẽ sớm liên hệ với bạn.');
            } catch (err) {
                showToast(`Lỗi đặt lịch: ${err.message}`);
            }
        };
        window.viewTutorProfileByUserId = async function(userId, tutorName) {
            try {
                const tutor = await TutorAPI.getByUserId(userId);
                const t = Array.isArray(tutor) ? tutor[0] : tutor;
                const rate = t.hourlyRate ? t.hourlyRate.toLocaleString('vi-VN') + 'đ/buổi' : 'Thỏa thuận';
                const tags = t.tagsVector || '';
                const avatarHtml = t.avatarUrl && t.avatarUrl.length > 5
                    ? `<img src="${t.avatarUrl}" class="rounded-circle shadow" style="width:100px;height:100px;object-fit:cover;">`
                    : `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-1 text-white" style="width:100px;height:100px;background:linear-gradient(135deg,#3b82f6,#0ea5e9);">${tutorName.charAt(0)}</div>`;

                const modalHtml = `
                    <div class="modal fade" id="tutorProfileModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="modal-header bg-primary text-white border-0 p-4">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-person-badge me-2"></i>Hồ sơ Gia sư</h5>
                                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="d-flex align-items-center gap-4 mb-4">
                                        ${avatarHtml}
                                        <div>
                                            <h3 class="fw-bold mb-1">${t.name}</h3>
                                            ${t.isApproved ? `<span class="badge bg-success-subtle text-success rounded-pill"><i class="bi bi-patch-check-fill"></i> Đã được xác minh</span>` : ''}
                                            <div class="mt-2 text-primary fw-bold">${t.specialization || 'Gia sư Tiếng Trung'}</div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-3 bg-light">
                                                <div class="text-secondary small mb-1">Học phí</div>
                                                <div class="fw-bold text-success">${rate}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-3 bg-light">
                                                <div class="text-secondary small mb-1">Trình độ</div>
                                                <div class="fw-bold text-primary">${(t.teachingLevels || 'HSK5-6').toUpperCase()}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-2">Giới thiệu</h6>
                                        <p class="text-secondary">${t.bio || 'Gia sư tận tâm, chuyên nghiệp.'}</p>
                                    </div>
                                    ${tags ? `<div class="mb-4"><h6 class="fw-bold mb-2">Chuyên môn</h6><div class="d-flex flex-wrap gap-2">${tags.split(',').map(tag => `<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">${tag.trim()}</span>`).join('')}</div></div>` : ''}
                                </div>
                                <div class="modal-footer border-0 p-4">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-primary-custom rounded-pill px-5" onclick="bootstrap.Modal.getInstance(document.getElementById('tutorProfileModal')).hide(); setTimeout(() => bookTutor(${t.id}), 300);">
                                        <i class="bi bi-calendar2-check me-2"></i>Đặt lịch ngay
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                const existing = document.getElementById('tutorProfileModal');
                if (existing) existing.remove();
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                new bootstrap.Modal(document.getElementById('tutorProfileModal')).show();
            } catch (err) {
                showToast('Người này không phải Gia sư hoặc không thể tải hồ sơ: ' + err.message);
            }
        };

        window.viewTutorProfile = async function(tutorId, tutorName) {
            try {
                const tutor = await TutorAPI.getById(tutorId);
                const t = Array.isArray(tutor) ? tutor[0] : tutor;
                const rate = t.hourlyRate ? t.hourlyRate.toLocaleString('vi-VN') + 'đ/buổi' : 'Thỏa thuận';
                const tags = t.tagsVector || '';
                const avatarHtml = t.avatarUrl && t.avatarUrl.length > 5
                    ? `<img src="${t.avatarUrl}" class="rounded-circle shadow" style="width:100px;height:100px;object-fit:cover;">`
                    : `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-1 text-white" style="width:100px;height:100px;background:linear-gradient(135deg,#3b82f6,#0ea5e9);">${tutorName.charAt(0)}</div>`;

                const modalHtml = `
                    <div class="modal fade" id="tutorProfileModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="modal-header bg-primary text-white border-0 p-4">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-person-badge me-2"></i>Hồ sơ Gia sư</h5>
                                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="d-flex align-items-center gap-4 mb-4">
                                        ${avatarHtml}
                                        <div>
                                            <h3 class="fw-bold mb-1">${t.name}</h3>
                                            ${t.isApproved ? `<span class="badge bg-success-subtle text-success rounded-pill"><i class="bi bi-patch-check-fill"></i> Đã được xác minh</span>` : ''}
                                            <div class="mt-2 text-primary fw-bold">${t.specialization || 'Gia sư Tiếng Trung'}</div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-3 bg-light">
                                                <div class="text-secondary small mb-1">Học phí</div>
                                                <div class="fw-bold text-success">${rate}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-3 bg-light">
                                                <div class="text-secondary small mb-1">Trình độ</div>
                                                <div class="fw-bold text-primary">${(t.teachingLevels || 'HSK5-6').toUpperCase()}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-2">Giới thiệu</h6>
                                        <p class="text-secondary">${t.bio || 'Gia sư tận tâm, chuyên nghiệp.'}</p>
                                    </div>
                                    ${tags ? `<div class="mb-4"><h6 class="fw-bold mb-2">Chuyên môn</h6><div class="d-flex flex-wrap gap-2">${tags.split(',').map(tag => `<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">${tag.trim()}</span>`).join('')}</div></div>` : ''}
                                </div>
                                <div class="modal-footer border-0 p-4">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-primary-custom rounded-pill px-5" onclick="bootstrap.Modal.getInstance(document.getElementById('tutorProfileModal')).hide(); setTimeout(() => bookTutor(${tutorId}), 300);">
                                        <i class="bi bi-calendar2-check me-2"></i>Đặt lịch ngay
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                const existing = document.getElementById('tutorProfileModal');
                if (existing) existing.remove();
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                new bootstrap.Modal(document.getElementById('tutorProfileModal')).show();
            } catch (err) {
                showToast('Không thể tải hồ sơ gia sư: ' + err.message);
            }
        }

        // Check login to update UI
        async function checkLoginState() {
            const user = JSON.parse(localStorage.getItem('lanying_user') || 'null');
            const navLoginBtn = document.getElementById('nav-login-btn');
            
            // Ẩn menu AI và danh sách gia sư nếu là gia sư
            if (user && user.role === 'tutor') {
                const aiMenu = document.getElementById('nav-ai-item');
                const tutorsMenu = document.getElementById('nav-tutors-item');
                const classesMenu = document.getElementById('nav-my-classes-item');
                
                if (aiMenu) aiMenu.style.display = 'none';
                if (tutorsMenu) tutorsMenu.style.display = 'none';
                if (classesMenu) classesMenu.style.display = 'block';
                
                // Ẩn tất cả các nút dẫn đến trang khảo sát đối với gia sư
                document.querySelectorAll('a[href="/student/survey"]').forEach(el => {
                    el.style.display = 'none';
                });
            } else if (user && user.role === 'student') {
                const classesMenu = document.getElementById('nav-my-classes-item');
                if (classesMenu) {
                    classesMenu.style.display = 'block';
                    const link = classesMenu.querySelector('a');
                    if (link) link.href = '/student/schedule';
                }
            }

            if (user && navLoginBtn) {
                let unreadBadge = '';
                let dashboardBadge = '';
                let hasRedDot = false;
                try {
                    const res = await MessageAPI.getUnreadCount();
                    if (res && res.count > 0) {
                        unreadBadge = `<span class="badge bg-danger rounded-pill ms-1">${res.count}</span>`;
                        hasRedDot = true;
                    }

                    // Kiểm tra trạng thái booking đã lưu
                    const bookingsRes = await apiRequest("/api/tutors/bookings");
                    let notificationsHtml = '';
                    let notifCount = 0;

                    if (res && res.count > 0) {
                        notifCount += res.count;
                        notificationsHtml += `
                            <li><a class="dropdown-item py-2 border-bottom" href="/chat">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-2"><i class="bi bi-chat-dots-fill"></i></div>
                                    <div>
                                        <div class="fw-bold">Tin nhắn mới</div>
                                        <div class="small text-secondary">Bạn có ${res.count} tin nhắn chưa đọc</div>
                                    </div>
                                </div>
                            </a></li>`;
                    }

                    const dashboardUrl = user.role === 'admin' ? '/admin' : (user.role === 'tutor' ? '/tutor/dashboard' : '/student/dashboard');

                    if (bookingsRes && Array.isArray(bookingsRes)) {
                        const currentState = JSON.stringify(bookingsRes.map(b => ({id: b.id, status: b.status})));
                        const lastSeen = localStorage.getItem('last_seen_bookings');
                        
                        if (user.role === 'tutor') {
                            const pendingBookings = bookingsRes.filter(b => b.status === 'pending');
                            if (pendingBookings.length > 0) {
                                hasRedDot = true;
                                notifCount += pendingBookings.length;
                                dashboardBadge = '<span class="badge bg-danger rounded-pill ms-1">Mới</span>';
                                pendingBookings.forEach(b => {
                                    notificationsHtml += `
                                        <li><a class="dropdown-item py-2 border-bottom" href="${dashboardUrl}">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="bg-warning-subtle text-warning rounded-circle p-2"><i class="bi bi-calendar-event-fill"></i></div>
                                                <div>
                                                    <div class="fw-bold">Yêu cầu đặt lịch</div>
                                                    <div class="small text-secondary">Từ học viên ${b.studentName || 'HS-' + b.studentId}</div>
                                                </div>
                                            </div>
                                        </a></li>`;
                                });
                            }
                        } else {
                            if (window.location.pathname.includes('/dashboard')) {
                                localStorage.setItem('last_seen_bookings', currentState);
                            } else if (currentState !== lastSeen) {
                                const newBookings = bookingsRes.filter(b => b.status === 'accepted' || b.status === 'rejected' || b.status === 'pending');
                                if (newBookings.length > 0) {
                                    hasRedDot = true;
                                    notifCount += 1;
                                    dashboardBadge = '<span class="badge bg-danger rounded-pill ms-1">Mới</span>';
                                    notificationsHtml += `
                                        <li><a class="dropdown-item py-2 border-bottom" href="${dashboardUrl}">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="bg-info-subtle text-info rounded-circle p-2"><i class="bi bi-info-circle-fill"></i></div>
                                                <div>
                                                    <div class="fw-bold">Cập nhật đặt lịch</div>
                                                    <div class="small text-secondary">Trạng thái đăng ký lớp học của bạn vừa thay đổi</div>
                                                </div>
                                            </div>
                                        </a></li>`;
                                }
                            }
                        }
                    }

                    if (notifCount === 0) {
                        notificationsHtml = `<li><div class="dropdown-item text-center text-secondary py-3">Không có thông báo mới</div></li>`;
                    }

                    let roleLabel = 'Học sinh';
                    if (user.role === 'tutor') roleLabel = 'Giáo viên';
                    if (user.role === 'admin') roleLabel = 'Admin';

                    navLoginBtn.innerHTML = `
                        <div class="d-flex align-items-center gap-3">
                            <!-- User Menu -->
                            <div class="dropdown">
                                <button class="btn btn-primary-custom rounded-pill dropdown-toggle px-4 position-relative shadow-sm" type="button" data-bs-toggle="dropdown">
                                    ${roleLabel} ${user.name}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-soft border-0 mt-2 p-2 rounded-4">
                                    <li><a class="dropdown-item rounded-3 py-2 fw-semibold text-success d-flex justify-content-between align-items-center" href="/chat">
                                        <span>💬 Hộp thư tin nhắn</span>
                                        ${unreadBadge}
                                    </a></li>
                                    <li><a class="dropdown-item rounded-3 py-2 d-flex justify-content-between align-items-center" href="${dashboardUrl}">
                                        <span>🔔 ${user.role === 'tutor' ? 'Không gian Gia sư' : (user.role === 'admin' ? 'Bảng quản trị' : 'Bảng tin Học viên')}</span>
                                        ${dashboardBadge}
                                    </a></li>
                                    <li><a class="dropdown-item rounded-3 py-2 text-primary" href="/profile">Cập nhật hồ sơ (Đổi tên)</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger rounded-3 py-2 fw-semibold" href="#" onclick="logout()">Đăng xuất</a></li>
                                </ul>
                            </div>

                            <!-- Notification Bell -->
                            <div class="dropdown">
                                <button class="btn rounded-circle position-relative shadow-sm d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" style="width: 44px; height: 44px; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border: 1px solid rgba(59,130,246,0.2); transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="bi bi-bell-fill fs-5" style="color: #3b82f6; ${notifCount > 0 ? 'animation: wiggle 2s linear infinite;' : ''}"></i>
                                    ${notifCount > 0 ? `
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.7rem; padding: 0.35em 0.5em;">
                                            ${notifCount}
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                        <span class="position-absolute top-0 start-100 translate-middle rounded-circle bg-danger opacity-75" style="width: 10px; height: 10px; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                                    ` : ''}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-0 rounded-4 overflow-hidden" style="width: 340px; border-radius: 1rem !important;">
                                    <div class="bg-gradient p-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #3b82f6, #0ea5e9);">
                                        <h6 class="m-0 fw-bold"><i class="bi bi-bell me-2"></i>Thông báo của bạn</h6>
                                        <span class="badge bg-white text-primary rounded-pill">${notifCount} mới</span>
                                    </div>
                                    <div style="max-height: 350px; overflow-y: auto;" class="custom-scrollbar">
                                        ${notificationsHtml}
                                    </div>
                                    <div class="p-2 border-top bg-light text-center">
                                        <a class="text-decoration-none text-primary fw-semibold small" href="${dashboardUrl}">Xem tất cả thông báo &rarr;</a>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    `;
                    
                    // Add animations CSS if not exists
                    if (!document.getElementById('bell-animations')) {
                        document.head.insertAdjacentHTML('beforeend', `
                            <style id="bell-animations">
                                @keyframes wiggle {
                                    0%, 7% { transform: rotateZ(0); }
                                    15% { transform: rotateZ(-15deg); }
                                    20% { transform: rotateZ(10deg); }
                                    25% { transform: rotateZ(-10deg); }
                                    30% { transform: rotateZ(6deg); }
                                    35% { transform: rotateZ(-4deg); }
                                    40%, 100% { transform: rotateZ(0); }
                                }
                                @keyframes ping {
                                    75%, 100% { transform: scale(2); opacity: 0; }
                                }
                                .custom-scrollbar::-webkit-scrollbar { width: 6px; }
                                .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
                                .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
                                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                            </style>
                        `);
                    }
                } catch(e) {}
            }
        }

        function openSchedule(e) {
            e.preventDefault();
            const user = JSON.parse(localStorage.getItem('lanying_user') || 'null');
            if (!user) {
                window.location.href = '/auth/login';
                return;
            }
            window.location.href = '/schedule';
        }

        function logout() {
            localStorage.removeItem('lanying_token');
            localStorage.removeItem('lanying_user');
            window.location.reload();
        }

        window.onload = () => {
            loadTutors();
            checkLoginState();
        };
    </script>
</body>
</html>

