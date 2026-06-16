import sqlite3

conn = sqlite3.connect('c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db')
cursor = conn.cursor()

cursor.execute('''
CREATE TABLE IF NOT EXISTS payments (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    BookingId INTEGER NOT NULL,
    StudentId INTEGER NOT NULL,
    Amount DECIMAL NOT NULL,
    Method TEXT NOT NULL,
    Status TEXT NOT NULL DEFAULT 'pending',
    TransactionId TEXT NOT NULL,
    OrderCode TEXT NOT NULL,
    CreatedAt TEXT NOT NULL,
    PaidAt TEXT
)
''')

conn.commit()
conn.close()
print("Table created successfully")
