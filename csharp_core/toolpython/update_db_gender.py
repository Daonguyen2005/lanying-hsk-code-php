import sqlite3

db_path = "c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db"

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

try:
    cursor.execute("ALTER TABLE users ADD COLUMN Gender TEXT;")
    print("Added Gender column to users table.")
except sqlite3.OperationalError as e:
    if "duplicate column name" in str(e):
        print("Column Gender already exists.")
    else:
        print("Error:", e)

conn.commit()
conn.close()
