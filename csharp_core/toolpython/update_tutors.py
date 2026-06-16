import sqlite3
import random

conn = sqlite3.connect('lanyinghsk.db')
c = conn.cursor()

try:
    # Lấy danh sách gia sư
    c.execute("SELECT t.Id, u.Name FROM tutors t JOIN users u ON t.UserId = u.Id")
    tutors = c.fetchall()

    bios = [
        "Cử nhân Sư phạm Tiếng Trung, 5 năm kinh nghiệm luyện thi HSK cực kỳ hiệu quả.",
        "Đam mê giảng dạy giao tiếp tiếng Trung, giúp học viên tự tin nói chuyện với người bản xứ.",
        "Chuyên đào tạo tiếng Trung thương mại cho người đi làm. 10 năm sinh sống tại Bắc Kinh.",
        "Giáo viên nhiệt tình, vui vẻ, phương pháp dạy học sáng tạo, phù hợp với mọi lứa tuổi.",
        "Tiến sĩ Ngôn ngữ học, chuyên sửa phát âm và dạy ngữ pháp chuyên sâu.",
        "Nhận dạy kèm 1-1 tất cả các trình độ, chú trọng phát triển toàn diện 4 kỹ năng."
    ]

    all_goals = ["luyen thi", "giao tiep", "thuong mai", "tre em"]
    all_skills = ["nghe noi", "phat am", "doc viet", "ngu phap"]
    all_levels = ["co ban", "trung cap", "nang cao", "sieu cap"]
    all_modes = ["online", "offline"]

    for i, t in enumerate(tutors):
        tutor_id = t[0]
        name = t[1]
        
        # Chọn ngẫu nhiên (hoặc cố định) vài tags
        goals = random.sample(all_goals, k=random.randint(1, 3))
        skills = random.sample(all_skills, k=random.randint(2, 4))
        modes = random.sample(all_modes, k=random.randint(1, 2))
        
        tags_list = goals + skills + modes
        tags_vector = ", ".join(tags_list)
        
        specialization = ", ".join(tags_list[:3])
        
        # Cập nhật DB
        c.execute("""
            UPDATE tutors 
            SET Bio = ?, TagsVector = ?, Specialization = ?, TeachingLevels = ? 
            WHERE Id = ?
        """, (
            bios[i % len(bios)], 
            tags_vector, 
            specialization, 
            ", ".join(random.sample(all_levels, k=random.randint(2, 3))),
            tutor_id
        ))

    conn.commit()
    print("Cập nhật chi tiết hồ sơ 6 gia sư thành công!")

except Exception as e:
    print(f"Lỗi: {e}")
    conn.rollback()

conn.close()
