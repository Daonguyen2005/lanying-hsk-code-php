import sqlite3
import random

db_path = 'c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db'
conn = sqlite3.connect(db_path)
c = conn.cursor()

# Get all tutors
c.execute("SELECT Id, Name, TagsVector FROM tutors")
tutors = c.fetchall()

styles = ["vui ve", "nghiem khac", "nhe nhang", "truyen cam hung"]
certs = ["tocfl", "hskk", ""]

for tutor in tutors:
    t_id = tutor[0]
    t_name = tutor[1]
    tags = tutor[2]
    
    # Determine gender loosely by name
    gender = "nam" if any(word in t_name.lower() for word in ["nam", "khang", "quân", "tiến", "phúc", "việt", "kiệt", "vinh", "duy", "đăng"]) else "nu"
    
    style = random.choice(styles)
    cert = random.choice(certs)
    
    # Check if we already appended new tags
    if gender not in tags and style not in tags:
        new_tags = f"{tags}, {gender}, {style}"
        if cert:
            new_tags += f", {cert}"
            
        c.execute("UPDATE tutors SET TagsVector = ? WHERE Id = ?", (new_tags, t_id))

conn.commit()
conn.close()
print("Updated tags for existing tutors successfully!")
