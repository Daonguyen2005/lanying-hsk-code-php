import sqlite3

db_path = 'c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db'
conn = sqlite3.connect(db_path)
c = conn.cursor()

try:
    c.execute('ALTER TABLE users ADD COLUMN Timeframe TEXT DEFAULT ""')
    c.execute('ALTER TABLE users ADD COLUMN AiPreference TEXT DEFAULT ""')
except sqlite3.OperationalError as e:
    print(f"Error: {e}")

conn.commit()
conn.close()
print("Added Timeframe and AiPreference to users table!")
