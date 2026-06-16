import sqlite3
c=sqlite3.connect('lanyinghsk.db').cursor()
# Student ID is 5 (from my previous fix_sync.py output).
classrooms = c.execute("SELECT ClassRoomId FROM classroom_students WHERE StudentId=5").fetchall()
classrooms = [r[0] for r in classrooms]

if not classrooms:
    print("No classrooms")
else:
    classes = c.execute("SELECT TutorId, Name FROM classrooms WHERE Id IN ({})".format(",".join("?"*len(classrooms))), classrooms).fetchall()

    slots = []
    for tutor_id, name in classes:
        s = c.execute("SELECT * FROM tutor_schedules WHERE TutorId=? AND Label=?", (tutor_id, name)).fetchall()
        slots.extend(s)
        
    print(f"Total slots: {len(slots)}")
