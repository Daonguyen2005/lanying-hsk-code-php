<?php include 'Views/layouts/header.php'; ?>

<style>
    .schedule-grid {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
        table-layout: fixed;
    }
    .schedule-grid th, .schedule-grid td {
        border: 1px solid #f1f3f5;
        text-align: center;
        vertical-align: middle;
        padding: 4px;
        position: relative;
        height: 55px;
        font-size: 0.85rem;
    }
    .schedule-grid th {
        background-color: #f8f9fa;
        font-weight: bold;
        font-size: 0.85rem;
        padding: 12px 4px;
        color: #495057;
    }
    .period-col {
        width: 100px;
        background-color: #f8f9fa !important;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.75rem;
    }
    .cell-slot {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border-radius: 4px;
    }
    .cell-slot:hover {
        background-color: #e3f2fd;
        box-shadow: inset 0 0 0 2px #90caf9;
    }
    .slot-available {
        background-color: #a7f3d0; /* Strong emerald pastel */
        color: #064e3b;
        border-left: 6px solid #059669;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: inset 0 0 0 1px rgba(5, 150, 105, 0.2), 0 4px 6px rgba(5, 150, 105, 0.1);
    }
    .slot-available:hover {
        background-color: #6ee7b7;
        transform: translateY(-2px);
        box-shadow: inset 0 0 0 1px rgba(5, 150, 105, 0.3), 0 6px 12px rgba(5, 150, 105, 0.2);
    }
    .slot-booked {
        background-color: #bfdbfe; /* Strong blue pastel */
        color: #1e3a8a;
        font-weight: bold;
        border-left: 6px solid #2563eb;
        box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.2), 0 4px 6px rgba(37, 99, 235, 0.1);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .slot-booked:hover {
        background-color: #93c5fd;
        transform: translateY(-2px);
        box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.3), 0 6px 12px rgba(37, 99, 235, 0.2);
    }
</style>

<div class="container-fluid mt-5 py-5 px-lg-5 min-vh-100 animate-fade-up">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-gradient mb-1"><i class="bi bi-calendar3"></i> Thời khóa biểu</h2>
            <p class="text-secondary mb-0" id="schedule-subtitle">Đang tải dữ liệu...</p>
        </div>
        
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <!-- Tuần selector -->
            <div class="d-flex align-items-center bg-white rounded-pill shadow-sm px-3 py-2 border text-nowrap">
                <button class="btn btn-primary-soft rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 36px; height: 36px;" onclick="changeWeek(-1)">
                    &#10094;
                </button>
                <span class="mx-3 fw-bold text-primary fs-5" id="current-week-label" style="letter-spacing: 0.5px;">Tuần này</span>
                <button class="btn btn-primary-soft rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 36px; height: 36px;" onclick="changeWeek(1)">
                    &#10095;
                </button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill ms-3" onclick="changeWeek(0)">Hôm nay</button>
            </div>
            
            <!-- View Mode -->
            <div class="bg-white rounded-pill shadow-sm p-1 border">
                <div class="btn-group btn-group-sm text-nowrap" role="group">
                    <input type="radio" class="btn-check" name="viewMode" id="viewAll" autocomplete="off" value="all" checked onchange="changeViewMode(this.value)">
                    <label class="btn btn-outline-primary" for="viewAll" style="border-radius: 50px 0 0 50px;">24 Giờ</label>

                    <input type="radio" class="btn-check" name="viewMode" id="viewMorning" autocomplete="off" value="morning" onchange="changeViewMode(this.value)">
                    <label class="btn btn-outline-primary" for="viewMorning">Sáng</label>

                    <input type="radio" class="btn-check" name="viewMode" id="viewAfternoon" autocomplete="off" value="afternoon" onchange="changeViewMode(this.value)">
                    <label class="btn btn-outline-primary" for="viewAfternoon">Trưa - Chiều</label>

                    <input type="radio" class="btn-check" name="viewMode" id="viewEvening" autocomplete="off" value="evening" onchange="changeViewMode(this.value)">
                    <label class="btn btn-outline-primary" for="viewEvening">Tối</label>

                    <input type="radio" class="btn-check" name="viewMode" id="viewNight" autocomplete="off" value="night" onchange="changeViewMode(this.value)">
                    <label class="btn btn-outline-primary" for="viewNight" style="border-radius: 0 50px 50px 0;">Khuya</label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 align-items-center text-nowrap">
                <select class="form-select rounded-pill" id="tutor-select" style="display: none; min-width: 200px;" onchange="loadSchedule()">
                    <option value="">Chọn Gia sư để xem lịch...</option>
                </select>
                <button class="btn btn-outline-primary rounded-pill d-none" id="btn-print" onclick="window.print()"><i class="bi bi-printer"></i></button>
                <button class="btn btn-success rounded-pill shadow-sm d-none fw-bold text-nowrap px-3" id="btn-duplicate" data-bs-toggle="modal" data-bs-target="#generateModal" style="background: linear-gradient(45deg, #198754, #20c997); border: none;">
                    <i class="bi bi-magic"></i> Tạo Lịch
                </button>
                <button class="btn btn-danger rounded-pill shadow-sm d-none fw-bold text-nowrap px-3" id="btn-delete-bulk" onclick="openDeleteModal()">
                    <i class="bi bi-trash"></i> Xóa Lịch
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 rounded-4 overflow-hidden mb-5">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 80vh; overflow-y: auto;">
                <table class="table table-bordered schedule-grid mb-0" id="schedule-table">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr style="background: linear-gradient(to right, #f8f9fa, #e3f2fd);">
                            <th class="period-col">Tiết</th>
                            <th>Thứ 2</th>
                            <th>Thứ 3</th>
                            <th>Thứ 4</th>
                            <th>Thứ 5</th>
                            <th>Thứ 6</th>
                            <th>Thứ 7</th>
                            <th class="text-danger">Chủ Nhật</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-body">
                        <!-- Generate by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Slot (For Tutors) -->
<div class="modal fade" id="slotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title fw-bold text-primary" id="slotModalLabel">Cập nhật Lịch dạy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary mb-3" id="slotInfo">Thứ ... - Tiết ...</p>
                <form id="slotForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select class="form-select shadow-sm" id="slotStatus" required>
                            <option value="empty">Trống (Không dạy)</option>
                            <option value="available">Sẵn sàng nhận lớp</option>
                            <option value="booked">Đã xếp lớp (Nhập tên)</option>
                        </select>
                    </div>
                    <div class="mb-3" id="slotDurationDiv">
                        <label class="form-label fw-bold">Thời lượng học</label>
                        <select class="form-select shadow-sm" id="slotDuration">
                            <option value="1">1 Giờ</option>
                            <option value="2" selected>2 Giờ</option>
                            <option value="3">3 Giờ</option>
                            <option value="4">4 Giờ</option>
                        </select>
                    </div>
                    <div class="mb-3" id="slotLabelDiv" style="display: none;">
                        <label class="form-label fw-bold">Tên Khóa Học</label>
                        <input type="text" class="form-control shadow-sm" id="slotLabel" placeholder="Ví dụ: HSK 1, HSK 2...">
                    </div>
                    <div class="mb-3" id="slotLocationDiv" style="display: none;">
                        <label class="form-label fw-bold">Phòng học / Link Meet</label>
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" id="slotBtnOnline" class="btn btn-sm btn-outline-primary rounded-pill flex-fill fw-bold" onclick="setLocationType('slot', 'online')"><i class="bi bi-camera-video"></i> Học Online</button>
                            <button type="button" id="slotBtnOffline" class="btn btn-sm btn-outline-success rounded-pill flex-fill fw-bold" onclick="setLocationType('slot', 'offline')"><i class="bi bi-building"></i> Học Offline</button>
                        </div>
                        <input type="text" class="form-control shadow-sm" id="slotLocation" placeholder="Nhập link hoặc phòng học">
                    </div>
                    <div class="mb-3" id="slotColorDiv" style="display: none;">
                        <label class="form-label fw-bold d-block">Màu sắc lớp học</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#3b82f6" style="width: 32px; height: 32px; background-color: #3b82f6; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('slotColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#ef4444" style="width: 32px; height: 32px; background-color: #ef4444; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('slotColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#f59e0b" style="width: 32px; height: 32px; background-color: #f59e0b; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('slotColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#10b981" style="width: 32px; height: 32px; background-color: #10b981; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('slotColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#8b5cf6" style="width: 32px; height: 32px; background-color: #8b5cf6; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('slotColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#ec4899" style="width: 32px; height: 32px; background-color: #ec4899; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('slotColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#64748b" style="width: 32px; height: 32px; background-color: #64748b; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('slotColor', this)"></div>
                        </div>
                        <input type="hidden" id="slotColor" value="#3b82f6">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between bg-light" id="slotModalFooter">
                <div>
                    <button type="button" class="btn btn-outline-danger rounded-pill px-3 me-2" id="btn-delete-slot" style="display: none;" onclick="deleteSingleSlot()">
                        <i class="bi bi-trash"></i> Xóa Buổi Này
                    </button>
                    <button type="button" class="btn btn-warning rounded-pill px-3" id="btn-reschedule-slot" style="display: none;" onclick="showRescheduleForm()">
                        <i class="bi bi-calendar-x"></i> Báo Nghỉ & Học Bù
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-light rounded-pill px-4 border shadow-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="saveSlot()">Lưu</button>
                </div>
            </div>
            
            <!-- Reschedule Form (Hidden by default) -->
            <div id="rescheduleFormDiv" style="display: none;" class="p-3 bg-light border-top">
                <h6 class="fw-bold text-danger mb-3"><i class="bi bi-exclamation-triangle"></i> Thông tin học bù</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold">Ngày học bù</label>
                    <input type="date" class="form-control shadow-sm" id="reschDate">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Thời gian bắt đầu</label>
                    <select class="form-select shadow-sm" id="reschStartPeriod">
                        <!-- populated via JS -->
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Thời lượng học (Số tiết)</label>
                    <select class="form-select shadow-sm" id="reschDuration">
                        <option value="1">1 Giờ (1 Tiết)</option>
                        <option value="2">2 Giờ (2 Tiết)</option>
                        <option value="3">3 Giờ (3 Tiết)</option>
                        <option value="4">4 Giờ (4 Tiết)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Lý do nghỉ (Tùy chọn)</label>
                    <input type="text" class="form-control shadow-sm" id="reschReason" placeholder="Vd: Thầy ốm đột xuất">
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-light rounded-pill px-3 border shadow-sm" id="btn-cancel-reschedule" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger rounded-pill px-3 shadow-sm" onclick="submitReschedule()">
                        <i class="bi bi-send"></i> Xác nhận & Thông báo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sinh Lịch Tự Động -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 text-white" style="background: linear-gradient(45deg, #198754, #20c997);">
                <h5 class="modal-title fw-bold"><i class="bi bi-magic"></i> Tạo Lịch Tự Động</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="generateForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Tuần dự kiến dạy</label>
                        <select class="form-select shadow-sm border-primary" id="genStartWeek">
                            <!-- Được điền bởi JS -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mẫu Lịch Học</label>
                        <select class="form-select shadow-sm" id="genPattern">
                            <option value="2,4,6">Thứ 2 - Thứ 4 - Thứ 6</option>
                            <option value="3,5,7">Thứ 3 - Thứ 5 - Thứ 7</option>
                            <option value="8">Chỉ Chủ Nhật</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Giờ bắt đầu</label>
                            <select class="form-select shadow-sm" id="genStartPeriod">
                                <!-- Được điền bởi JS -->
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Thời lượng 1 buổi</label>
                            <select class="form-select shadow-sm" id="genDurationClass">
                                <option value="1">1 Giờ</option>
                                <option value="2" selected>2 Giờ</option>
                                <option value="3">3 Giờ</option>
                                <option value="4">4 Giờ</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thời gian (Khóa học kéo dài)</label>
                        <select class="form-select shadow-sm border-success text-success fw-bold" id="genDuration">
                            <option value="8">2 Tháng (8 tuần)</option>
                            <option value="12" selected>3 Tháng (12 tuần)</option>
                            <option value="16">4 Tháng (16 tuần)</option>
                            <option value="24">6 Tháng (24 tuần)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Khóa Học</label>
                        <input type="text" class="form-control shadow-sm" id="genLabel" placeholder="Ví dụ: HSK 1, HSK 2..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phòng học / Link Meet</label>
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" id="genBtnOnline" class="btn btn-sm btn-outline-primary rounded-pill flex-fill fw-bold" onclick="setLocationType('gen', 'online')"><i class="bi bi-camera-video"></i> Học Online</button>
                            <button type="button" id="genBtnOffline" class="btn btn-sm btn-outline-success rounded-pill flex-fill fw-bold" onclick="setLocationType('gen', 'offline')"><i class="bi bi-building"></i> Học Offline</button>
                        </div>
                        <input type="text" class="form-control shadow-sm" id="genLocation" placeholder="Nhập link hoặc phòng học">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Màu sắc lớp học</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#3b82f6" style="width: 32px; height: 32px; background-color: #3b82f6; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('genColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#ef4444" style="width: 32px; height: 32px; background-color: #ef4444; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('genColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#f59e0b" style="width: 32px; height: 32px; background-color: #f59e0b; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('genColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#10b981" style="width: 32px; height: 32px; background-color: #10b981; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('genColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#8b5cf6" style="width: 32px; height: 32px; background-color: #8b5cf6; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('genColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#ec4899" style="width: 32px; height: 32px; background-color: #ec4899; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('genColor', this)"></div>
                            <div class="color-swatch rounded-circle shadow-sm" data-color="#64748b" style="width: 32px; height: 32px; background-color: #64748b; cursor: pointer; border: 2px solid transparent;" onclick="selectColor('genColor', this)"></div>
                        </div>
                        <input type="hidden" id="genColor" value="#3b82f6">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-light rounded-pill px-4 border shadow-sm" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm" id="btn-submit-generate" onclick="submitGenerate()">Xác nhận Tạo</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xóa Lịch Hàng Loạt -->
<div class="modal fade" id="deleteBulkModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-trash"></i> Xóa Lịch Đã Tạo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary small">Chọn lớp học bạn muốn xóa. Thao tác này sẽ quét sạch toàn bộ lịch của lớp đó trong hệ thống.</p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Chọn Lớp cần xóa</label>
                    <select class="form-select shadow-sm border-danger" id="delLabel">
                        <option value="">Đang tải danh sách lớp...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-light rounded-pill px-4 border shadow-sm" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm" onclick="submitDeleteBulk()">Xác nhận Xóa</button>
            </div>
        </div>
    </div>
</div>
<script>
    let currentSchedule = [];
    let selectedSlot = null; // {day, period, dateStr}
    let viewTutorId = null;
    let currentWeekStart = getMonday(new Date());

    function getMonday(d) {
        d = new Date(d);
        var day = d.getDay(),
            diff = d.getDate() - day + (day == 0 ? -6 : 1);
        return new Date(d.setDate(diff));
    }

    function formatDate(date) {
        return date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
    }

    function formatDisplayDate(date) {
        return String(date.getDate()).padStart(2, '0') + '/' + String(date.getMonth() + 1).padStart(2, '0');
    }

    function changeWeek(offset) {
        if (offset === 0) {
            currentWeekStart = getMonday(new Date());
        } else {
            currentWeekStart.setDate(currentWeekStart.getDate() + (offset * 7));
        }
        updateWeekLabel();
        renderEmptyGrid();
        populateStartWeeks();
        loadSchedule();
    }

    function updateWeekLabel() {
        const endOfWeek = new Date(currentWeekStart);
        endOfWeek.setDate(currentWeekStart.getDate() + 6);
        document.getElementById('current-week-label').textContent = `${formatDisplayDate(currentWeekStart)} - ${formatDisplayDate(endOfWeek)}`;
    }

    document.addEventListener("DOMContentLoaded", () => {
        const user = getUser();
        if (!user) {
            window.location.href = '/auth/login';
            return;
        }

        document.getElementById('schedule-subtitle').textContent = 
            user.role === 'tutor' ? 'Nhấp vào các ô thời gian để xếp lịch dạy. Bạn có thể tự động nhân bản ra nhiều tuần.' :
            user.role === 'admin' ? 'Quản lý thời khóa biểu của hệ thống.' :
            'Xem thời khóa biểu các lớp học của bạn.';

        if (user.role === 'tutor') {
            document.getElementById('btn-duplicate').classList.remove('d-none');
            document.getElementById('btn-delete-bulk').classList.remove('d-none');
        }

        if (user.role === 'admin') {
            document.getElementById('tutor-select').style.display = 'block';
            loadTutorsList();
        } else if (user.role === 'student') {
            document.getElementById('tutor-select').style.display = 'none';
            loadSchedule();
        } else {
            viewTutorId = user.id;
            loadSchedule();
        }

        updateWeekLabel();
        renderEmptyGrid();
        populateStartWeeks();

        document.getElementById('slotStatus').addEventListener('change', function() {
            const isVisible = this.value === 'booked' || this.value === 'available';
            document.getElementById('slotLabelDiv').style.display = isVisible ? 'block' : 'none';
            document.getElementById('slotLocationDiv').style.display = isVisible ? 'block' : 'none';
            document.getElementById('slotColorDiv').style.display = isVisible ? 'block' : 'none';
        });

        updateSwatches('genColor', '#3b82f6');
        updateLocationButtons('gen', '');
    });

    async function loadTutorsList() {
        try {
            const res = await apiRequest("/api/tutors");
            const select = document.getElementById('tutor-select');
            res.forEach(t => {
                select.innerHTML += `<option value="${t.id}">${t.name}</option>`;
            });
        } catch(e) {}
    }

    let currentViewMode = 'all';
    function changeViewMode(mode) {
        currentViewMode = mode;
        renderEmptyGrid();
        loadSchedule();
    }

    const periodTimes = Array.from({length: 24}, (_, i) => {
        const start = i.toString().padStart(2, '0') + ':00';
        const end = (i + 1).toString().padStart(2, '0') + ':00';
        return `${start} - ${end}`;
    });

    document.addEventListener('DOMContentLoaded', () => {
        const genStartSelect = document.getElementById('genStartPeriod');
        if (genStartSelect) {
            genStartSelect.innerHTML = '';
            periodTimes.forEach((pt, idx) => {
                genStartSelect.innerHTML += `<option value="${idx + 1}">${pt.split(' - ')[0]}</option>`;
            });
            genStartSelect.value = "8"; // Default 07:00 (Period 8)
        }
    });

    function populateStartWeeks() {
        const select = document.getElementById('genStartWeek');
        if(!select) return;
        select.innerHTML = '';
        
        let d = new Date(currentWeekStart);
        for (let i = 0; i < 10; i++) {
            const startStr = formatDisplayDate(d);
            const endD = new Date(d);
            endD.setDate(d.getDate() + 6);
            const endStr = formatDisplayDate(endD);
            
            const option = document.createElement('option');
            option.value = formatDate(d);
            
            if (i === 0) {
                option.textContent = `Tuần đang xem (${startStr} - ${endStr})`;
            } else if (i === 1) {
                option.textContent = `Tuần tiếp theo (${startStr} - ${endStr})`;
            } else {
                option.textContent = `Sau ${i} tuần (${startStr} - ${endStr})`;
            }
            
            select.appendChild(option);
            d.setDate(d.getDate() + 7);
        }
    }

    function renderEmptyGrid() {
        const theadRow = document.querySelector('#schedule-table thead tr');
        theadRow.innerHTML = '<th class="period-col">Thời gian</th>';
        
        const days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ Nhật'];
        const datesInWeek = [];
        for (let i = 0; i < 7; i++) {
            let d = new Date(currentWeekStart);
            d.setDate(d.getDate() + i);
            datesInWeek.push(formatDate(d));
            let className = i === 6 ? 'text-danger' : '';
            theadRow.innerHTML += `<th class="${className}">${days[i]}<br><small class="text-secondary fw-normal">${formatDisplayDate(d)}</small></th>`;
        }

        const tbody = document.getElementById('schedule-body');
        tbody.innerHTML = '';
        
        let startPeriod = 1;
        let endPeriod = 24;
        if (currentViewMode === 'morning') { startPeriod = 7; endPeriod = 11; } // 06:00 - 11:00
        else if (currentViewMode === 'afternoon') { startPeriod = 12; endPeriod = 18; } // 11:00 - 18:00
        else if (currentViewMode === 'evening') { startPeriod = 19; endPeriod = 24; } // 18:00 - 24:00
        else if (currentViewMode === 'night') { startPeriod = 1; endPeriod = 6; } // 00:00 - 06:00

        for (let period = startPeriod; period <= endPeriod; period++) {
            let tr = document.createElement('tr');
            const startTime = periodTimes[period-1].split(' - ')[0];
            tr.innerHTML = `<td class="period-col text-center align-middle fw-bold" style="background: var(--gradient-primary); color: white; border-bottom: 1px solid rgba(255,255,255,0.3); font-size: 1.25rem; letter-spacing: 0.5px;">${startTime}</td>`;
            for (let day = 2; day <= 8; day++) {
                const dateStr = datesInWeek[day - 2];
                tr.innerHTML += `<td class="cell-slot" id="cell-${dateStr}-${period}" onclick="handleCellClick(${day}, ${period}, '${dateStr}')"></td>`;
            }
            tbody.appendChild(tr);
        }
    }

    async function loadSchedule() {
        const user = getUser();
        let queryTutorId = '';
        if (user.role === 'admin') {
            queryTutorId = document.getElementById('tutor-select').value;
        }

        const endOfWeek = new Date(currentWeekStart);
        endOfWeek.setDate(currentWeekStart.getDate() + 6);
        const startDate = formatDate(currentWeekStart);
        const endDate = formatDate(endOfWeek);

        try {
            let apiUrl = `/api/tutors/schedule?start_date=${startDate}&end_date=${endDate}&t=${new Date().getTime()}`;
            if (queryTutorId) apiUrl += `&tutor_id=${queryTutorId}`;
            const res = await apiRequest(apiUrl);
            currentSchedule = res;
            
            // Xóa cũ
            document.querySelectorAll('.cell-slot').forEach(el => {
                el.className = 'cell-slot';
                el.innerHTML = '';
                el.removeAttribute('rowspan');
                el.style.display = 'table-cell';
            });
            
            // Điền mới
            res.forEach(slot => {
                let viewStart = 1; let viewEnd = 24;
                if (currentViewMode === 'morning') { viewStart = 7; viewEnd = 11; }
                else if (currentViewMode === 'afternoon') { viewStart = 12; viewEnd = 18; }
                else if (currentViewMode === 'evening') { viewStart = 19; viewEnd = 24; }
                else if (currentViewMode === 'night') { viewStart = 1; viewEnd = 6; }

                if (slot.endPeriod < viewStart || slot.startPeriod > viewEnd) return; // Nằm hoàn toàn ngoài viewMode

                const visibleStart = Math.max(slot.startPeriod, viewStart);
                const visibleEnd = Math.min(slot.endPeriod, viewEnd);
                const duration = visibleEnd - visibleStart + 1;

                const firstCell = document.getElementById(`cell-${slot.scheduleDate}-${visibleStart}`);
                
                if (firstCell) {
                    firstCell.setAttribute('rowspan', duration);
                    firstCell.style.verticalAlign = 'middle';
                    
                    const startTime = periodTimes[slot.startPeriod-1].split(' - ')[0];
                    const endTime = periodTimes[slot.endPeriod-1].split(' - ')[1];

                    const tutorName = document.querySelector(`#tutor-select option[value="${slot.tutorId || viewTutorId}"]`)?.textContent || 'Giáo viên';
                    
                    const slotColor = slot.color || '#3b82f6';
                    firstCell.className = `cell-slot text-start p-2 shadow-sm`;
                    firstCell.style.backgroundColor = slotColor;
                    firstCell.style.border = 'none';
                    firstCell.style.borderRadius = '6px';
                    firstCell.style.cursor = 'pointer';

                    const roomHtml = slot.location ? `<div class="mb-1 text-truncate" style="font-size:0.85rem"><b>Phòng:</b> ${slot.location}</div>` : '';
                    
                    firstCell.innerHTML = `
                        <div class="d-flex flex-column h-100 text-white overflow-hidden" style="min-width: 0;">
                            <div class="fw-bold mb-1 text-truncate" style="font-size:1.1rem" title="${slot.label || 'Sẵn sàng nhận lớp'}">${slot.label || 'Sẵn sàng nhận lớp'}</div>
                            ${roomHtml}
                            <div class="mb-1 text-truncate" style="font-size:0.85rem"><b>GV:</b> ${tutorName}</div>
                            <div class="mt-auto fw-bold text-white text-truncate" style="font-size:1rem; opacity: 0.95;">
                                <i class="bi bi-clock"></i> ${startTime} -> ${endTime}
                            </div>
                        </div>
                    `;

                    // Ẩn các ô bị gộp
                    for (let p = visibleStart + 1; p <= visibleEnd; p++) {
                        const hiddenCell = document.getElementById(`cell-${slot.scheduleDate}-${p}`);
                        if (hiddenCell) {
                            hiddenCell.style.display = 'none';
                        }
                    }
                }
            });
        } catch(e) {
            console.error("Lỗi tải lịch: ", e);
        }
    }

    function handleCellClick(day, period, dateStr) {
        const user = getUser();
        if (!user) {
            showToast("Vui lòng đăng nhập để tương tác với lịch!");
            return;
        }

        const days = ['CN', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ Nhật'];
        const displayDate = formatDisplayDate(new Date(dateStr));
        const timeSlot = periodTimes[period-1];

        if (user.role === 'student') {
            const queryTutorId = document.getElementById('tutor-select').value;
            if (!queryTutorId) {
                showToast("Vui lòng chọn gia sư trước.");
                return;
            }

            const existing = currentSchedule.find(s => s.scheduleDate === dateStr && period >= s.startPeriod && period <= s.endPeriod);
            if (!existing || !existing.label) {
                showToast("Giáo viên chưa có lớp vào khung giờ này.");
                return;
            }

            const className = existing.label;
            const defaultNote = `Em muốn đăng ký tham gia lớp "${className}" vào ${days[day]} (${displayDate}) lúc ${timeSlot}.`;
            
            if (typeof window.bookTutor === 'function') {
                window.bookTutor(queryTutorId, defaultNote);
            }
            return;
        }

        if (user.role !== 'tutor') return; // Các role khác bỏ qua

        selectedSlot = { day, period, dateStr };
        document.getElementById('slotInfo').textContent = `${days[day]} (${displayDate}) - ${timeSlot}`;
        
        const existing = currentSchedule.find(s => s.scheduleDate === dateStr && period >= s.startPeriod && period <= s.endPeriod);
        const statusSelect = document.getElementById('slotStatus');
        const labelInput = document.getElementById('slotLabel');
        const durationSelect = document.getElementById('slotDuration');
        const btnDeleteSlot = document.getElementById('btn-delete-slot');
        
        let isBooked = false;
        
        if (existing) {
            btnDeleteSlot.style.display = 'block';
            durationSelect.value = (existing.endPeriod - existing.startPeriod + 1).toString();
            
            isBooked = existing.studentId || existing.label;
            statusSelect.value = isBooked ? 'booked' : 'available';
            labelInput.value = existing.label || '';
            const locVal = existing.location || '';
            document.getElementById('slotLocation').value = locVal;
            updateLocationButtons('slot', locVal);
            
            currentEditingSlot = existing;
            document.getElementById('slotColor').value = existing.color || '#3b82f6';
            updateSwatches('slotColor', existing.color || '#3b82f6');
            document.getElementById('slotDuration').value = (existing.endPeriod - existing.startPeriod + 1).toString();
            
            // Logic hiển thị nút và quyền
            document.getElementById('btn-delete-slot').style.display = 'block';
            if (isBooked) {
                document.getElementById('btn-reschedule-slot').style.display = 'block';
            } else {
                document.getElementById('btn-reschedule-slot').style.display = 'none';
            }
            
            // Cập nhật thủ công hiển thị các trường dựa trên status
            const isVisible = statusSelect.value === 'booked' || statusSelect.value === 'available';
            document.getElementById('slotLabelDiv').style.display = isVisible ? 'block' : 'none';
            document.getElementById('slotLocationDiv').style.display = isVisible ? 'block' : 'none';
            document.getElementById('slotColorDiv').style.display = isVisible ? 'block' : 'none';
        } else {
            currentEditingSlot = null;
            document.getElementById('slotStatus').value = 'available';
            document.getElementById('slotLabel').value = '';
            document.getElementById('slotLocation').value = '';
            document.getElementById('slotColor').value = '#3b82f6';
            updateSwatches('slotColor', '#3b82f6');
            document.getElementById('slotLocationDiv').style.display = 'none';
            document.getElementById('slotColorDiv').style.display = 'none';
        }

        hideRescheduleForm(); // Đảm bảo UI luôn ở trạng thái gốc khi mở ban đầu
        const modal = new bootstrap.Modal(document.getElementById('slotModal'));

        if (existing && isBooked) {
            document.getElementById('slotModalLabel').innerHTML = '<i class="bi bi-calendar-x"></i> Báo Nghỉ & Học Bù';
            showRescheduleForm(true); // Trực tiếp mở form Reschedule
        } else {
            document.getElementById('slotModalLabel').innerHTML = '<i class="bi bi-pencil-square"></i> Cập nhật Lịch dạy';
        }

        modal.show();
    }

    async function saveSlot() {
        if (!selectedSlot) return;
        const status = document.getElementById('slotStatus').value;
        const label = document.getElementById('slotLabel').value;
        const location = document.getElementById('slotLocation').value;
        const color = document.getElementById('slotColor').value;
        const duration = parseInt(document.getElementById('slotDuration').value) || 1;
        
        if (status !== 'empty' && (!label.trim() || !location.trim())) {
            alert("Vui lòng điền đầy đủ Tên Khóa Học và Phòng học/Link Meet trước khi lưu!");
            return;
        }
        
        // Nếu xóa (Trống) thì chỉ xóa khoảng tiết hiện tại
        const calculatedEndPeriod = status === 'empty' ? selectedSlot.period : (selectedSlot.period + duration - 1);
        
        try {
            await apiRequest('/api/tutors/schedule', 'POST', {
                scheduleDate: selectedSlot.dateStr,
                dayOfWeek: selectedSlot.day,
                startPeriod: selectedSlot.period,
                endPeriod: calculatedEndPeriod,
                status: status,
                label: label,
                location: location,
                color: color
            });
            
            bootstrap.Modal.getInstance(document.getElementById('slotModal')).hide();
            loadSchedule();
            showToast("Đã cập nhật lịch!");
        } catch(e) {
            alert("Lỗi: " + e.message);
        }
    }

    async function deleteSingleSlot() {
        document.getElementById('slotStatus').value = 'empty';
        await saveSlot();
    }

    function showRescheduleForm(directOpen = false) {
        document.querySelector('#slotModal .modal-body form').style.display = 'none';
        
        const footerEl = document.getElementById('slotModalFooter');
        if (footerEl) {
            footerEl.classList.remove('d-flex');
            footerEl.style.display = 'none';
        }

        document.getElementById('rescheduleFormDiv').style.display = 'block';

        // Điền tiết học
        const reschSelect = document.getElementById('reschStartPeriod');
        reschSelect.innerHTML = '';
        periodTimes.forEach((pt, idx) => {
            reschSelect.innerHTML += `<option value="${idx + 1}">${pt.split(' - ')[0]}</option>`;
        });
        if(currentEditingSlot) {
            reschSelect.value = currentEditingSlot.startPeriod;
            document.getElementById('reschDate').value = currentEditingSlot.scheduleDate;
            document.getElementById('reschDuration').value = (currentEditingSlot.endPeriod - currentEditingSlot.startPeriod + 1).toString();
        }

        const btnCancel = document.getElementById('btn-cancel-reschedule');
        if (directOpen) {
            btnCancel.onclick = function() {
                bootstrap.Modal.getInstance(document.getElementById('slotModal')).hide();
            };
        } else {
            btnCancel.onclick = function() {
                hideRescheduleForm();
            };
        }
    }

    function hideRescheduleForm() {
        const formEl = document.querySelector('#slotModal .modal-body form');
        if(formEl) formEl.style.display = 'block';
        const footerEl = document.getElementById('slotModalFooter');
        if(footerEl) {
            footerEl.style.display = 'flex';
            footerEl.classList.add('d-flex');
        }
        const reschDiv = document.getElementById('rescheduleFormDiv');
        if(reschDiv) reschDiv.style.display = 'none';
    }

    async function submitReschedule() {
        if (!currentEditingSlot) return;

        const newDate = document.getElementById('reschDate').value;
        const newStart = parseInt(document.getElementById('reschStartPeriod').value);
        const duration = parseInt(document.getElementById('reschDuration').value);
        const reason = document.getElementById('reschReason').value;

        if (!newDate) {
            alert("Vui lòng chọn ngày học bù.");
            return;
        }

        const newEnd = newStart + duration - 1;

        if (newEnd > 24) {
            alert("Lỗi: Buổi học kết thúc sau 24:00. Vui lòng chọn tiết bắt đầu sớm hơn.");
            return;
        }

        const btn = document.querySelector('#rescheduleFormDiv .btn-danger');
        const oldHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';

        try {
            const res = await apiRequest('/api/tutors/schedule/reschedule', 'POST', {
                slotId: currentEditingSlot.id,
                newDate: newDate,
                newStartPeriod: newStart,
                newEndPeriod: newEnd,
                reason: reason
            });

            bootstrap.Modal.getInstance(document.getElementById('slotModal')).hide();
            loadSchedule();
            showToast(res.message || "Đã báo nghỉ và dời lịch học bù thành công!");
            hideRescheduleForm();
        } catch (e) {
            alert("Lỗi: " + e.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = oldHtml;
        }
    }

    async function submitGenerate() {
        const pattern = document.getElementById('genPattern').value;
        const startPeriod = parseInt(document.getElementById('genStartPeriod').value);
        const classDuration = parseInt(document.getElementById('genDurationClass').value);
        const endPeriod = startPeriod + classDuration - 1;
        const duration = document.getElementById('genDuration').value;
        const label = document.getElementById('genLabel').value;
        const location = document.getElementById('genLocation').value;
        const color = document.getElementById('genColor').value;

        if (!label.trim() || !location.trim()) {
            alert("Vui lòng điền đầy đủ Tên Khóa Học và Phòng học/Link Meet trước khi tạo lịch!");
            return;
        }

        if (endPeriod > 24) {
            alert("Lỗi: Lớp học kéo dài vượt qua 24h. Vui lòng chọn giờ bắt đầu sớm hơn!");
            return;
        }

        const btn = document.getElementById('btn-submit-generate');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang tạo...';

        const startWeekValue = document.getElementById('genStartWeek').value;

        try {
            const res = await apiRequest('/api/tutors/schedule/generate', 'POST', {
                startDate: startWeekValue,
                pattern: pattern,
                startPeriod: startPeriod,
                endPeriod: endPeriod,
                status: "available",
                label: label,
                location: location,
                color: color,
                weeks: parseInt(duration)
            });
            
            bootstrap.Modal.getInstance(document.getElementById('generateModal')).hide();
            alert(res.message);
            loadSchedule();
        } catch(e) {
            alert("Lỗi: " + e.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Xác nhận Tạo';
        }
    }

    async function openDeleteModal() {
        const delSelect = document.getElementById('delLabel');
        delSelect.innerHTML = '<option value="">Đang tải danh sách lớp...</option>';
        
        const modal = new bootstrap.Modal(document.getElementById('deleteBulkModal'));
        modal.show();

        try {
            const allSchedules = await apiRequest('/api/tutors/schedule', 'GET');
            const uniqueLabels = [...new Set(allSchedules.map(s => s.label).filter(l => l))];
            const hasEmptySlots = allSchedules.some(s => !s.label);
            
            delSelect.innerHTML = '<option value="">-- Chọn một danh mục --</option>';
            if (uniqueLabels.length === 0 && !hasEmptySlots) {
                delSelect.innerHTML = '<option value="">Chưa có lịch nào được tạo</option>';
                return;
            }

            if (hasEmptySlots) {
                const opt = document.createElement('option');
                opt.value = '[empty]';
                opt.textContent = '[Xóa toàn bộ] Các lịch Trống (Sẵn sàng)';
                opt.className = 'text-primary fw-bold';
                delSelect.appendChild(opt);
            }

            uniqueLabels.forEach(label => {
                const opt = document.createElement('option');
                opt.value = label;
                opt.textContent = 'Lớp: ' + label;
                delSelect.appendChild(opt);
            });
        } catch(e) {
            delSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
            alert("Lỗi tải danh sách lớp: " + e.message);
        }
    }

    async function submitDeleteBulk() {
        const label = document.getElementById('delLabel').value;
        if (!label) {
            alert("Vui lòng chọn Tên Lớp cần xóa!");
            return;
        }

        const confirmMsg = label === '[empty]' 
            ? "Bạn có CHẮC CHẮN muốn xóa TOÀN BỘ các lịch Sẵn sàng (chưa có lớp) không?"
            : `Bạn có CHẮC CHẮN muốn xóa toàn bộ lịch của lớp [${label}] không? Hành động này không thể hoàn tác.`;

        if (!confirm(confirmMsg)) {
            return;
        }

        try {
            const res = await apiRequest('/api/tutors/schedule/bulk?label=' + encodeURIComponent(label), 'DELETE');
            bootstrap.Modal.getInstance(document.getElementById('deleteBulkModal')).hide();
            alert(res.message);
            loadSchedule();
        } catch(e) {
            alert("Lỗi: " + e.message);
        }
    }
    function selectColor(inputId, element) {
        const colorHex = element.dataset.color;
        document.getElementById(inputId).value = colorHex;
        const swatches = element.parentElement.querySelectorAll('.color-swatch');
        swatches.forEach(s => {
            s.style.border = '2px solid transparent';
            s.style.boxShadow = 'none';
        });
        element.style.border = '2px solid white';
        element.style.boxShadow = '0 0 0 2px #1e293b';
    }

    function updateSwatches(inputId, colorHex) {
        let found = false;
        const inputEl = document.getElementById(inputId);
        if(!inputEl) return;
        const swatches = inputEl.parentElement.querySelectorAll('.color-swatch');
        swatches.forEach(s => {
            if (s.dataset.color === colorHex) {
                s.style.border = '2px solid white';
                s.style.boxShadow = '0 0 0 2px #1e293b';
                found = true;
            } else {
                s.style.border = '2px solid transparent';
                s.style.boxShadow = 'none';
            }
        });
        if (!found && swatches.length > 0) {
            swatches[0].style.border = '2px solid white';
            swatches[0].style.boxShadow = '0 0 0 2px #1e293b';
            inputEl.value = swatches[0].dataset.color;
        }
    }

    function setLocationType(prefix, type) {
        const input = document.getElementById(prefix + 'Location');
        const btnOnline = document.getElementById(prefix + 'BtnOnline');
        const btnOffline = document.getElementById(prefix + 'BtnOffline');

        if (type === 'online') {
            input.value = 'https://meet.google.com/xxx-xxxx-xxx';
            btnOnline.className = 'btn btn-sm btn-primary rounded-pill flex-fill fw-bold';
            btnOffline.className = 'btn btn-sm btn-outline-success rounded-pill flex-fill fw-bold';
        } else {
            input.value = 'Địa chỉ: Tầng 3, Tòa nhà ...';
            btnOffline.className = 'btn btn-sm btn-success rounded-pill flex-fill fw-bold';
            btnOnline.className = 'btn btn-sm btn-outline-primary rounded-pill flex-fill fw-bold';
        }
        
        // Bôi đen text để người dùng dễ dàng gõ đè lên
        input.focus();
        input.select();
    }

    function updateLocationButtons(prefix, locationText) {
        const btnOnline = document.getElementById(prefix + 'BtnOnline');
        const btnOffline = document.getElementById(prefix + 'BtnOffline');
        if (!btnOnline || !btnOffline) return;

        if (!locationText) {
            btnOnline.className = 'btn btn-sm btn-outline-primary rounded-pill flex-fill fw-bold';
            btnOffline.className = 'btn btn-sm btn-outline-success rounded-pill flex-fill fw-bold';
        } else if (locationText.includes('http') || locationText.includes('meet') || locationText.includes('zoom')) {
            btnOnline.className = 'btn btn-sm btn-primary rounded-pill flex-fill fw-bold';
            btnOffline.className = 'btn btn-sm btn-outline-success rounded-pill flex-fill fw-bold';
        } else {
            btnOffline.className = 'btn btn-sm btn-success rounded-pill flex-fill fw-bold';
            btnOnline.className = 'btn btn-sm btn-outline-primary rounded-pill flex-fill fw-bold';
        }
    }
</script>

<?php include 'Views/layouts/footer.php'; ?>
