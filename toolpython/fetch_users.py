import sqlite3

conn = sqlite3.connect('csharp_core/lanyinghsk.db')
conn.row_factory = sqlite3.Row
c = conn.cursor()
c.execute("SELECT * FROM users")
rows = c.fetchall()
print([dict(r) for r in rows])
conn.close()
