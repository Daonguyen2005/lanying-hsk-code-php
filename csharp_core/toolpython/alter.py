import sqlite3

conn = sqlite3.connect('lanyinghsk.db')
c = conn.cursor()

try:
    c.execute('ALTER TABLE users ADD COLUMN CurrentLevel TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN LearningGoal TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN Phone TEXT DEFAULT ""')
except Exception as e:
    print("Error or already exists:", e)

conn.commit()
conn.close()
print('Done!')
