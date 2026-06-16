import sqlite3

conn = sqlite3.connect('lanyinghsk.db')
c = conn.cursor()

try:
    c.execute("INSERT INTO users (Email, HashedPassword, Name, Role) VALUES ('admin@lanying.com', 'admin123', 'Super Admin', 'admin')")
    conn.commit()
    print("Admin user created successfully")
except Exception as e:
    print(f"Error: {e}")

conn.close()
