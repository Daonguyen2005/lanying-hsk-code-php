import os

file_path = "c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/php_views/Views/tutor/dashboard.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

modal_html = """
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

<script>"""

content = content.replace("<script>", modal_html, 1)

old_updateStatus = """    async function updateStatus(id, status) {
        try {
            await TutorAPI.updateBookingStatus(id, status);
            showToast("Cập nhật trạng thái thành công!");
            loadBookings();
        } catch (err) {
            showToast("Lỗi: " + err.message);
        }
    }"""

new_updateStatus = """    let selectClassModal = null;

    async function updateStatus(id, status) {
        if (status === 'accepted') {
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
                // Lọc lịch trống (StudentId == null hoặc thiếu StudentId)
                const available = schedules.filter(s => !s.studentId && !s.StudentId);
                
                if (available.length === 0) {
                    select.innerHTML = '<option value="">(Không có lịch rảnh nào)</option>';
                    warning.classList.remove('d-none');
                } else {
                    select.innerHTML = '<option value="">-- Chọn một lớp học --</option>';
                    available.forEach(s => {
                        const dayLabel = s.dayOfWeek === 8 ? "Chủ nhật" : "Thứ " + s.dayOfWeek;
                        select.innerHTML += `<option value="${s.id || s.Id}">${s.scheduleDate} (${dayLabel}), Ca: ${s.startPeriod}-${s.endPeriod} ${s.label ? ' - ' + s.label : ''}</option>`;
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
    }"""

content = content.replace(old_updateStatus, new_updateStatus)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Đã cập nhật dashboard.php với Modal chọn lớp")
