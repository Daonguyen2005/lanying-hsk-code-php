import sqlite3
conn = sqlite3.connect('c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db')
c = conn.cursor()
try:
    c.execute('ALTER TABLE users ADD COLUMN Skills TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN Modes TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN Age TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN Budget TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN Schedule TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN HasCompletedSurvey BOOLEAN DEFAULT 0')
except sqlite3.OperationalError as e:
    print(f"Error: {e}")
conn.commit()
conn.close()
print("Thành công")
