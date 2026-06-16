import sqlite3
import os

db_path = r'c:\Users\VanDao\.gemini\antigravity\scratch\tiengtrungcautrucmvc\csharp_core\lanyinghsk.db'
conn = sqlite3.connect(db_path)
c = conn.cursor()

try:
    c.execute('ALTER TABLE tutor_schedules ADD COLUMN Location TEXT DEFAULT ""')
    print("Added Location")
except Exception as e:
    print(e)

try:
    c.execute('ALTER TABLE tutor_schedules ADD COLUMN Color TEXT DEFAULT ""')
    print("Added Color")
except Exception as e:
    print(e)

conn.commit()
conn.close()
