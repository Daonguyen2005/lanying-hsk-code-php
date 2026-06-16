import requests
import time
import random
import sys

API_BASE = "http://localhost:5000/api"
CHAT_API = "http://localhost:8001/chat"

print("="*50)
print("BẮT ĐẦU E2E TEST: LANYING HSK")
print("="*50)

def print_step(name):
    print(f"\n---> {name}")

def print_success(msg):
    print(f"  [OK] {msg}")

def print_fail(msg):
    print(f"  [FAIL] {msg}")
    sys.exit(1)

# Helpers
def register(email, password, name, role):
    res = requests.post(f"{API_BASE}/auth/register", json={
        "email": email, "password": password, "name": name, "role": role, "gender": "other"
    })
    if res.status_code == 200:
        return res.json()
    print_fail(f"Register failed for {email}: {res.text}")

def login(email, password):
    res = requests.post(f"{API_BASE}/auth/login", json={
        "email": email, "password": password
    })
    if res.status_code == 200:
        return res.json().get('access_token')
    print_fail(f"Login failed for {email}: {res.text}")

def get_headers(token):
    return {"Authorization": f"Bearer {token}"}

# 1. SETUP USERS
random_id = random.randint(1000, 9999)
tutor_email = f"e2e_tutor_{random_id}@test.com"
student_email = f"e2e_student_{random_id}@test.com"

print_step("1. ĐĂNG KÝ VÀ TẠO TÀI KHOẢN")
register(tutor_email, "123456", "Gia Sư E2E", "tutor")
register(student_email, "123456", "Học Viên E2E", "student")
print_success("Tạo tài khoản Gia Sư và Học Viên thành công.")

admin_email = f"e2e_admin_{random_id}@test.com"
register(admin_email, "123456", "Admin E2E", "admin")

tutor_token = login(tutor_email, "123456")
student_token = login(student_email, "123456")
admin_token = login(admin_email, "123456")
print_success("Đăng nhập thành công cả 3 quyền.")

# 2. GIA SƯ CẬP NHẬT HỒ SƠ
print_step("2. GIA SƯ NỘP HỒ SƠ")
res = requests.put(f"{API_BASE}/tutors/me/profile", json={
    "specialization": "Giao tiếp cơ bản",
    "bio": "Xin chào, tôi là gia sư tự động.",
    "hourlyRate": 150000,
    "teachingLevels": "hsk1,hsk2",
    "avatarBase64": "data:image/png;base64,iVBOR",
    "certificateBase64": "data:image/png;base64,iVBOR"
}, headers=get_headers(tutor_token))
if res.status_code == 200:
    print_success("Gia sư đã cập nhật hồ sơ và gửi duyệt.")
else:
    print_fail(f"Gia sư cập nhật hồ sơ thất bại: HTTP {res.status_code} - {res.text}")

# Lấy ID của Gia sư
res = requests.get(f"{API_BASE}/tutors/me/profile", headers=get_headers(tutor_token))
tutor_data = res.json()
tutor_profile_id = tutor_data['id']
tutor_user_id = tutor_data['userId']

# 3. ADMIN DUYỆT GIA SƯ
print_step("3. ADMIN DUYỆT HỒ SƠ")
res = requests.put(f"{API_BASE}/admin/tutors/{tutor_profile_id}/approve", headers=get_headers(admin_token))
if res.status_code == 200:
    print_success("Admin đã duyệt hồ sơ gia sư.")
else:
    print_fail(f"Admin duyệt thất bại: {res.text}")

# 4. HỌC VIÊN LÀM KHẢO SÁT & TÌM KIẾM
print_step("4. HỌC VIÊN KHẢO SÁT & TÌM KIẾM")
res = requests.get(f"{API_BASE}/surveyquestions", headers=get_headers(student_token))
if res.status_code == 200:
    print_success("Lấy danh sách câu hỏi khảo sát thành công.")
else:
    print_fail("Lỗi lấy câu hỏi khảo sát.")

res = requests.get(f"{API_BASE}/tutors/", headers=get_headers(student_token))
if res.status_code == 200 and len(res.json()) > 0:
    print_success(f"Học viên lấy danh sách gia sư thành công (Có {len(res.json())} gia sư).")
else:
    print_fail("Lỗi lấy danh sách gia sư.")

# 5. HỌC VIÊN ĐẶT LỊCH GIA SƯ
print_step("5. HỌC VIÊN ĐẶT LỊCH")
res = requests.post(f"{API_BASE}/tutors/{tutor_profile_id}/book", json={
    "note": "Chào thầy, em muốn đăng ký học."
}, headers=get_headers(student_token))
if res.status_code == 200:
    print_success("Học viên đặt lịch thành công.")
else:
    print_fail(f"Đặt lịch thất bại: {res.text}")

# 6. GIA SƯ CHẤP NHẬN YÊU CẦU
print_step("6. GIA SƯ XỬ LÝ ĐẶT LỊCH")

# Tạo schedule trước
requests.post(f"{API_BASE}/tutors/schedule", json={
    "scheduleDate": "2026-06-16",
    "dayOfWeek": 3,
    "startPeriod": 1,
    "endPeriod": 4,
    "status": "available",
    "label": "Lớp E2E Test",
    "location": "Zoom"
}, headers=get_headers(tutor_token))

# Lấy ID của schedule vừa tạo
res = requests.get(f"{API_BASE}/tutors/schedule", headers=get_headers(tutor_token))
schedules = res.json()
schedule_id = schedules[-1]['id'] if len(schedules) > 0 else 0

res = requests.get(f"{API_BASE}/tutors/bookings", headers=get_headers(tutor_token))
bookings = res.json()
booking_id = bookings[0]['id']
student_user_id = bookings[0]['studentId']

res = requests.put(f"{API_BASE}/tutors/bookings/{booking_id}", json={
    "status": "accepted",
    "scheduleId": schedule_id
}, headers=get_headers(tutor_token))
if res.status_code == 200:
    print_success("Gia sư đã chấp nhận yêu cầu và xếp lớp thành công.")
else:
    print_fail(f"Gia sư chấp nhận thất bại: {res.text}")

# 7. THANH TOÁN & THỜI KHÓA BIỂU
print_step("7. HỌC VIÊN THANH TOÁN (MOCK WEBHOOK)")
# Tạo link thanh toán trước để lấy OrderCode
res = requests.post(f"{API_BASE}/payments/create", json={
    "bookingId": booking_id,
    "amount": 150000
}, headers=get_headers(student_token))
if res.status_code == 200:
    payment_data = res.json()
    order_code = payment_data.get("orderCode")
else:
    print_fail(f"Lỗi tạo thanh toán: {res.text}")

# Giả lập Webhook gọi về
res = requests.post(f"{API_BASE}/payments/webhook", json={
    "orderCode": order_code,
    "amount": 150000,
    "status": "success",
    "transactionId": "MOCK_TX_123"
})
if res.status_code == 200:
    print_success("Webhook thanh toán gọi thành công (Booking tự chuyển sang 'paid').")
else:
    print_fail(f"Lỗi webhook thanh toán: {res.text}")

# Kiểm tra lại lịch học của học viên
res = requests.get(f"{API_BASE}/tutors/schedule", headers=get_headers(student_token))
if res.status_code == 200:
    print_success(f"Học viên tải thời khóa biểu thành công: {len(res.json())} buổi học.")
else:
    print_fail(f"Lỗi tải thời khóa biểu: {res.text}")

# 8. TIN NHẮN (CHAT)
print_step("8. HỆ THỐNG TIN NHẮN")
res = requests.post(f"{API_BASE}/messages/send", json={
    "receiverId": tutor_user_id,
    "content": "Chào thầy, em đã thanh toán xong."
}, headers=get_headers(student_token))
if res.status_code == 200:
    print_success("Học viên gửi tin nhắn thành công.")
else:
    print_fail(f"Gửi tin nhắn thất bại: {res.text}")

res = requests.get(f"{API_BASE}/messages/recent", headers=get_headers(tutor_token))
if res.status_code == 200 and len(res.json()) > 0:
    print_success("Gia sư nhận được tin nhắn trong Recent Chat.")
else:
    print_fail("Lỗi tải tin nhắn mới.")

print("\n" + "="*50)
print("🎉 TOÀN BỘ END-TO-END TEST ĐÃ HOÀN TẤT THÀNH CÔNG! 🎉")
print("="*50)
