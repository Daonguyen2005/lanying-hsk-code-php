import sqlite3

conn = sqlite3.connect('lanyinghsk.db')
c = conn.cursor()

try:
    c.execute('ALTER TABLE tutor_schedules ADD COLUMN ScheduleDate TEXT DEFAULT ""')
    print("Added ScheduleDate to tutor_schedules")
except Exception as e:
    print("Error or already exists:", e)

conn.commit()
conn.close()
