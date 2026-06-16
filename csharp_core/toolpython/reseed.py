import sqlite3

conn = sqlite3.connect('lanyinghsk.db')
c = conn.cursor()

try:
    # Xóa toàn bộ học viên và gia sư cũ, giữ lại Admin
    c.execute("DELETE FROM users WHERE Email != 'admin@lanying.com'")
    c.execute("DELETE FROM tutors")
    
    # Reset auto_increment (sqlite_sequence)
    try:
        c.execute("DELETE FROM sqlite_sequence WHERE name='users'")
        c.execute("DELETE FROM sqlite_sequence WHERE name='tutors'")
    except:
        pass
        
    # Thêm 1 học viên
    c.execute("INSERT INTO users (Email, HashedPassword, Name, Role, Gender, CurrentLevel, LearningGoal, Phone, Skills, Modes, Age, Budget, Schedule, Timeframe, AiPreference, AiRecommendations, HasCompletedSurvey) VALUES ('student1@lanying.com', '123456', 'Học viên 1', 'student', 'Nữ', '', '', '', '', '', '', '', '', '', '', '', 0)")
    
    # Thêm 6 gia sư
    tutors_data = [
        ('tutor1@lanying.com', '123456', 'Trần Hữu Nam', 'tutor', 'Nam', 'HSK 5', 100000),
        ('tutor2@lanying.com', '123456', 'Lê Thị Lan', 'tutor', 'Nữ', 'HSK 6', 150000),
        ('tutor3@lanying.com', '123456', 'Nguyễn Minh Khang', 'tutor', 'Nam', 'HSK 4', 80000),
        ('tutor4@lanying.com', '123456', 'Phạm Thu Hương', 'tutor', 'Nữ', 'HSK 5, HSK 6', 200000),
        ('tutor5@lanying.com', '123456', 'Hoàng Anh Quân', 'tutor', 'Nam', 'HSK 3, HSK 4', 90000),
        ('tutor6@lanying.com', '123456', 'Đỗ Ngọc Mai', 'tutor', 'Nữ', 'Giao tiếp cơ bản', 120000)
    ]
    
    for t in tutors_data:
        c.execute("INSERT INTO users (Email, HashedPassword, Name, Role, Gender, CurrentLevel, LearningGoal, Phone, Skills, Modes, Age, Budget, Schedule, Timeframe, AiPreference, AiRecommendations, HasCompletedSurvey) VALUES (?, ?, ?, ?, ?, '', '', '', '', '', '', '', '', '', '', '', 0)", (t[0], t[1], t[2], t[3], t[4]))
        user_id = c.lastrowid
        c.execute("INSERT INTO tutors (UserId, Name, Bio, TeachingLevels, Specialization, HourlyRate, TagsVector, AvatarUrl, CertificateUrl, IsApproved, IsSubmittedForReview) VALUES (?, ?, '', ?, ?, ?, '', '', '', 1, 0)", (user_id, t[2], t[5], t[5], t[6]))
        
    conn.commit()
    print("Xóa và tạo lại 1 học viên, 6 gia sư thành công!")

except Exception as e:
    print(f"Lỗi: {e}")
    conn.rollback()

conn.close()
