import sqlite3
c=sqlite3.connect('lanyinghsk.db')
cur=c.cursor()
cur.execute("INSERT INTO classrooms (TutorId, Name, Description, Location, StartDate, TotalSessions, CreatedAt, DeleteRequested) SELECT TutorId, Label, 'Tạo tự động từ Thời khóa biểu', Location, MIN(ScheduleDate), COUNT(*), CURRENT_TIMESTAMP, 0 FROM tutor_schedules WHERE Label != '' GROUP BY TutorId, Label")
c.commit()
c.close()
