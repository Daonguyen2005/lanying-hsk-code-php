import sqlite3

db_path = 'c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db'
conn = sqlite3.connect(db_path)
cursor = conn.cursor()

columns = [
    ("TeachingStyle", "TEXT"),
    ("TutorGender", "TEXT"),
    ("StudyTime", "TEXT"),
    ("LongTermGoal", "TEXT"),
    ("OtherCerts", "TEXT"),
    ("AiRecommendations", "TEXT")
]

for col_name, col_type in columns:
    try:
        cursor.execute(f"ALTER TABLE users ADD COLUMN {col_name} {col_type};")
        print(f"Added column {col_name}")
    except sqlite3.OperationalError as e:
        if "duplicate column name" in str(e).lower():
            print(f"Column {col_name} already exists")
        else:
            print(f"Error adding {col_name}: {e}")

conn.commit()
conn.close()
print("Database update complete.")
