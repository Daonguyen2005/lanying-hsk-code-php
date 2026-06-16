import sqlite3

conn = sqlite3.connect('lanyinghsk.db')
c = conn.cursor()

c.execute('''
    CREATE TABLE IF NOT EXISTS questions (
        Id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        Text TEXT NOT NULL,
        "Order" INTEGER NOT NULL,
        StepKey TEXT NOT NULL
    )
''')

c.execute('''
    CREATE TABLE IF NOT EXISTS question_options (
        Id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        QuestionId INTEGER NOT NULL,
        Text TEXT NOT NULL,
        Value TEXT NOT NULL,
        FOREIGN KEY (QuestionId) REFERENCES questions (Id) ON DELETE CASCADE
    )
''')

c.execute('''
    CREATE TABLE IF NOT EXISTS tutor_schedules (
        Id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        TutorId INTEGER NOT NULL,
        DayOfWeek INTEGER NOT NULL,
        StartPeriod INTEGER NOT NULL,
        EndPeriod INTEGER NOT NULL,
        StudentId INTEGER,
        Label TEXT NOT NULL
    )
''')

c.execute('''
    CREATE TABLE IF NOT EXISTS homeworks (
        Id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        TutorId INTEGER NOT NULL,
        StudentId INTEGER NOT NULL,
        Title TEXT NOT NULL,
        Content TEXT NOT NULL,
        DueDate TEXT NOT NULL,
        Status TEXT NOT NULL,
        CreatedAt TEXT NOT NULL
    )
''')

conn.commit()
conn.close()
print('Created tables manually!')
