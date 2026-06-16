import sqlite3
c = sqlite3.connect('lanyinghsk.db')
cur = c.cursor()

# Get all accepted bookings
bookings = cur.execute("SELECT StudentId, TutorId FROM bookings WHERE Status='accepted'").fetchall()

for student_id, tutor_id in bookings:
    # See if there are duplicate cloned schedules for this student
    labels = cur.execute("SELECT DISTINCT Label FROM tutor_schedules WHERE StudentId=? AND TutorId=? AND Label != ''", (student_id, tutor_id)).fetchall()
    
    for row in labels:
        label = row[0]
        # Get classroom id
        classroom = cur.execute("SELECT Id FROM classrooms WHERE TutorId=? AND Name=?", (tutor_id, label)).fetchone()
        if classroom:
            classroom_id = classroom[0]
            # check if already in classroom_students
            existing = cur.execute("SELECT Id FROM classroom_students WHERE ClassRoomId=? AND StudentId=?", (classroom_id, student_id)).fetchone()
            if not existing:
                print(f"Adding Student {student_id} to ClassRoom {classroom_id} ({label})")
                cur.execute("INSERT INTO classroom_students (ClassRoomId, StudentId, JoinedAt) VALUES (?, ?, CURRENT_TIMESTAMP)", (classroom_id, student_id))
            
            # Delete the duplicated slots for this class
            cur.execute("DELETE FROM tutor_schedules WHERE StudentId=? AND TutorId=? AND Label=?", (student_id, tutor_id, label))

c.commit()
c.close()
print("Done sync!")
