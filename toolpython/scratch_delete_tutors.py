import sqlite3

conn = sqlite3.connect('csharp_core/lanyinghsk.db')
c = conn.cursor()
c.execute("DELETE FROM tutors WHERE UserId IN (SELECT Id FROM users WHERE Role != 'tutor') OR UserId NOT IN (SELECT Id FROM users)")
conn.commit()
print("Deleted fake tutors")
conn.close()
