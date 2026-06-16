import sqlite3

mapping = {
    "tre em": "Trẻ em",
    "co ban": "Cơ bản",
    "trung cap": "Trung cấp",
    "giao tiep": "Giao tiếp",
    "thuong mai": "Thương mại",
    "luyen thi": "Luyện thi HSK",
    "phat am": "Phát âm",
    "khau ngu": "Khẩu ngữ",
    "thap": "Cơ bản",
    "cao": "Nâng cao",
    "online": "Trực tuyến",
    "offline": "Trực tiếp",
    "hsk1": "HSK 1", "hsk2": "HSK 2", "hsk3": "HSK 3",
    "hsk4": "HSK 4", "hsk5": "HSK 5", "hsk6": "HSK 6"
}

def replace_accents(text):
    if not text:
        return text
    parts = [p.strip() for p in text.split(',')]
    new_parts = []
    for p in parts:
        lower_p = p.lower()
        if lower_p in mapping:
            new_parts.append(mapping[lower_p])
        else:
            new_parts.append(p)
    return ", ".join(new_parts)

conn = sqlite3.connect("lanyinghsk.db")
c = conn.cursor()
c.execute("SELECT Id, Specialization, TeachingLevels FROM tutors")
rows = c.fetchall()

for row in rows:
    tid, spec, levels = row
    new_spec = replace_accents(spec)
    new_levels = replace_accents(levels)
    c.execute("UPDATE tutors SET Specialization = ?, TeachingLevels = ? WHERE Id = ?", (new_spec, new_levels, tid))

conn.commit()
conn.close()
print("Updated database accents.")
