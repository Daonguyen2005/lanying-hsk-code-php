import sqlite3
import random

db_path = 'c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db'
conn = sqlite3.connect(db_path)
c = conn.cursor()

names = [
    "Trần Hữu Nam", "Lê Thị Lan", "Nguyễn Minh Khang", "Phạm Thu Hương", "Hoàng Anh Quân",
    "Đỗ Ngọc Mai", "Vũ Minh Tiến", "Phan Thanh Thúy", "Bùi Vĩnh Phúc", "Đặng Thị Hoa",
    "Hồ Minh Tâm", "Ngô Quốc Việt", "Lưu Thanh Hà", "Đinh Tuấn Kiệt", "Dương Yến Nhi",
    "Lý Quang Vinh", "Vương Thúy Kiều", "Trương Bá Duy", "Mai Lan Hương", "Châu Khải Đăng"
]

tags_pool = [
    ["hsk5", "giao tiep", "online", "sinh vien", "150-300k", "nghe noi", "phat am"],
    ["hsk6", "luyen thi", "offline", "nguoi di lam", "cao", "doc viet", "ngu phap"],
    ["hsk5", "tre em", "online", "thap", "phat am", "khau ngu"],
    ["hsk6", "thuong mai", "online", "nguoi di lam", "cao", "giao tiep", "chuyen sau"],
    ["hsk5", "giai de", "offline", "sinh vien", "150-300k", "ngu phap", "doc viet"],
    ["hsk6", "khau ngu", "online", "nguoi di lam", "150-300k", "nghe noi", "phat am"]
]

bios = [
    "Tôi tốt nghiệp ngành ngôn ngữ Trung Quốc. Kinh nghiệm giảng dạy 3 năm. Phong cách thân thiện, nhiệt tình.",
    "Từng du học tại Bắc Kinh. Sở trường luyện khẩu ngữ, phát âm chuẩn giọng Bắc Kinh.",
    "Giáo viên chuyên ôn thi HSK cấp tốc. Đã giúp hàng trăm học viên đạt chứng chỉ HSK5, 6.",
    "Chuyên dạy tiếng Trung thương mại cho người đi làm. Phương pháp thực tế, dễ áp dụng vào công việc.",
    "Yêu trẻ em và có kinh nghiệm 4 năm dạy tiếng Trung thiếu nhi bằng phương pháp trực quan sinh động."
]

for i in range(20):
    email = f"tutor{i+1}@lanying.com"
    name = names[i]
    password = "123456"
    
    # Check if already exists to prevent duplicate runs
    c.execute("SELECT Id FROM users WHERE Email = ?", (email,))
    if c.fetchone() is not None:
        continue
    
    c.execute("INSERT INTO users (Email, HashedPassword, Name, Role) VALUES (?, ?, ?, 'tutor')", (email, password, name))
    user_id = c.lastrowid
    
    tags = ", ".join(random.choice(tags_pool))
    bio = random.choice(bios)
    h_rate = random.choice([120, 150, 200, 250, 300]) * 1000
    
    level = "hsk5" if "hsk5" in tags else "hsk6"
    
    # Random default avatar
    avatar_url = f"https://ui-avatars.com/api/?name={name.replace(' ', '+')}&background=random"
    cert_url = "https://images.unsplash.com/photo-1589330694653-ded6df03f754?q=80&w=600&auto=format&fit=crop"
    
    c.execute("""
        INSERT INTO tutors (UserId, Name, Bio, TeachingLevels, Specialization, HourlyRate, TagsVector, IsApproved, IsSubmittedForReview, AvatarUrl, CertificateUrl)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0, 1, ?, ?)
    """, (user_id, name, bio, level, "Tiếng Trung Tổng Hợp", h_rate, tags, avatar_url, cert_url))

conn.commit()
conn.close()
print("Seeded 20 tutors successfully!")
