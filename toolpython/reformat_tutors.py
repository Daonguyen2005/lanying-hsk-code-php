import sqlite3
import random

db_path = 'c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db'
conn = sqlite3.connect(db_path)
c = conn.cursor()

goals_pool = [["luyen thi", "giao tiep"], ["thuong mai", "giao tiep"], ["tre em"], ["luyen thi"], ["giao tiep"]]
skills_pool = [["nghe noi", "phat am"], ["doc viet", "ngu phap"], ["nghe noi", "doc viet"], ["phat am", "ngu phap"]]
levels_pool = [["co ban", "trung cap"], ["nang cao"], ["co ban", "trung cap", "nang cao"], ["sieu cap"]]
modes_pool = [["online"], ["offline"], ["online", "offline"]]
ages_pool = [["sinh vien", "nguoi di lam"], ["tre em"], ["sinh vien"], ["nguoi di lam"]]
schedules_pool = [["weekdays", "evenings"], ["weekends"], ["evenings", "weekends"]]
styles_pool = [["vui ve", "truyen cam hung"], ["nghiem khac"], ["kien nhan", "vui ve"], ["truyen cam hung"]]

c.execute("SELECT Id FROM tutors")
tutors = c.fetchall()

for (t_id,) in tutors:
    goals = random.choice(goals_pool)
    skills = random.choice(skills_pool)
    modes = random.choice(modes_pool)
    ages = random.choice(ages_pool)
    scheds = random.choice(schedules_pool)
    styles = random.choice(styles_pool)
    
    tags = goals + skills + modes + ages + scheds + styles
    tags_str = ", ".join(tags)
    
    levels = random.choice(levels_pool)
    levels_str = ", ".join(levels)
    
    spec = ", ".join(goals[:2])
    
    # Update tutor to pending state with new tags
    c.execute("""
        UPDATE tutors 
        SET TagsVector = ?, TeachingLevels = ?, Specialization = ?, IsApproved = 0, IsSubmittedForReview = 1
        WHERE Id = ?
    """, (tags_str, levels_str, spec, t_id))

conn.commit()
conn.close()
print("Updated all tutors to use the new CV format and set to pending!")
