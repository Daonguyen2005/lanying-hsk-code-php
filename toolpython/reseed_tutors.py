import sqlite3
import random

db_path = 'c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db'
conn = sqlite3.connect(db_path)
c = conn.cursor()

# Clear existing tutors
c.execute("DELETE FROM tutors")
c.execute("DELETE FROM users WHERE Role = 'tutor'")

names = [
    ("Trần Hữu Nam", "Nam"), ("Lê Thị Lan", "Nữ"), ("Nguyễn Minh Khang", "Nam"), ("Phạm Thu Hương", "Nữ"), ("Hoàng Anh Quân", "Nam"),
    ("Đỗ Ngọc Mai", "Nữ"), ("Vũ Minh Tiến", "Nam"), ("Phan Thanh Thúy", "Nữ"), ("Bùi Vĩnh Phúc", "Nam"), ("Đặng Thị Hoa", "Nữ"),
    ("Hồ Minh Tâm", "Nam"), ("Ngô Quốc Việt", "Nam"), ("Lưu Thanh Hà", "Nữ"), ("Đinh Tuấn Kiệt", "Nam"), ("Dương Yến Nhi", "Nữ"),
    ("Lý Quang Vinh", "Nam"), ("Vương Thúy Kiều", "Nữ"), ("Trương Bá Duy", "Nam"), ("Mai Lan Hương", "Nữ"), ("Châu Khải Đăng", "Nam")
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
    name, gender = names[i]
    password = "123456"
    
    c.execute("INSERT INTO users (Email, HashedPassword, Name, Role, Gender) VALUES (?, ?, ?, 'tutor', ?)", (email, password, name, gender))
    user_id = c.lastrowid
    
    tags = ", ".join(random.choice(tags_pool))
    bio = random.choice(bios)
    h_rate = random.choice([120, 150, 200, 250, 300]) * 1000
    
    level = "hsk5" if "hsk5" in tags else "hsk6"
    
    avatar_url = f"https://ui-avatars.com/api/?name={name.replace(' ', '+')}&background=random"
    cert_url = "https://images.unsplash.com/photo-1589330694653-ded6df03f754?q=80&w=600&auto=format&fit=crop"
    
    c.execute("""
        INSERT INTO tutors (UserId, Name, Bio, TeachingLevels, Specialization, HourlyRate, TagsVector, IsApproved, IsSubmittedForReview, AvatarUrl, CertificateUrl)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0, 1, ?, ?)
    """, (user_id, name, bio, level, "Tiếng Trung Tổng Hợp", h_rate, tags, avatar_url, cert_url))

conn.commit()
conn.close()
print("Re-seeded 20 tutors with gender successfully!")
