import sqlite3
try:
    conn = sqlite3.connect('lanyinghsk.db')
    c = conn.cursor()
    c.execute('ALTER TABLE messages ADD COLUMN IsRead INTEGER NOT NULL DEFAULT 0;')
    conn.commit()
    conn.close()
    print("Column IsRead added successfully!")
except Exception as e:
    print(f"Error: {e}")
