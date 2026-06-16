import sqlite3
c=sqlite3.connect('lanyinghsk.db').cursor()
print(c.execute("SELECT Id, Email, Role FROM users WHERE Role='student'").fetchall())
