import sqlite3

db_path = 'c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/csharp_core/lanyinghsk.db'
conn = sqlite3.connect(db_path)
c = conn.cursor()

c.execute("SELECT Id, TagsVector, Name FROM tutors")
tutors = c.fetchall()

for t_id, tags_str, name in tutors:
    tags = tags_str.lower() if tags_str else ""
    spec = "Tiếng Trung Tổng Hợp"
    
    if "luyen thi" in tags or "giai de" in tags:
        spec = "Luyện thi HSK/TOCFL"
    elif "giao tiep" in tags or "khau ngu" in tags:
        spec = "Tiếng Trung Giao tiếp"
    elif "thuong mai" in tags or "business" in tags:
        spec = "Tiếng Trung Thương mại"
    elif "tre em" in tags or "kids" in tags:
        spec = "Tiếng Trung Trẻ em"
    elif "doc viet" in tags or "ngu phap" in tags:
        spec = "Ngữ pháp & Đọc Viết"

    # Dummy certificate image using placehold.co
    cert_url = f"https://placehold.co/600x400/2ecc71/ffffff?text=HSK+Certificate\\n{name.replace(' ', '+')}"

    c.execute("UPDATE tutors SET Specialization = ?, CertificateUrl = ? WHERE Id = ?", (spec, cert_url, t_id))

conn.commit()
conn.close()
print("Updated Specializations and Certificates for tutors!")
