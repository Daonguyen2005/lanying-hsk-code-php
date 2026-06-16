from fastapi import FastAPI
from pydantic import BaseModel
from typing import List, Optional
import math
import uvicorn
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI(title="Lanying AI Service")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

class AIRequest(BaseModel):
    current_level: str
    goals: List[str]
    skills: List[str]
    modes: List[str]
    age: str
    budget: str = "medium"
    schedule: List[str] = []
    timeframe: str = "6months"
    ai_preference: str = "medium"
    teaching_style: List[str] = []
    tutor_gender: str = ""
    weaknesses: List[str] = []
    study_time: str = ""
    long_term_goal: str = ""
    other_certs: str = ""
    tutors_data: List[dict]  # Data passed from C# or PHP

# --- Vector Logic ---
def build_user_vector(req: AIRequest) -> list:
    vector = [0] * 36
    if "hsk_exam" in req.goals: vector[0] = 1
    if "communication" in req.goals: vector[1] = 1
    if "business" in req.goals: vector[2] = 1
    if "kids" in req.goals: vector[3] = 1
    if "listening_speaking" in req.skills: vector[4] = 1
    if "pronunciation" in req.skills: vector[5] = 1
    if "reading_writing" in req.skills: vector[6] = 1
    if "grammar" in req.skills: vector[7] = 1
    if req.current_level in ["beginner", "hsk1", "hsk2"]: vector[8] = 1
    if req.current_level in ["hsk3", "hsk4"]: vector[9] = 1
    if req.current_level in ["hsk5", "hsk6"]: vector[10] = 1
    if req.current_level in ["hsk7", "hsk8", "hsk9"]: vector[11] = 1
    if "online" in req.modes: vector[12] = 1
    if "offline" in req.modes: vector[13] = 1
    if req.age == "kids": vector[14] = 1
    elif req.age == "student": vector[15] = 1
    elif req.age == "adult": vector[16] = 1
    else: vector[15] = 1
    if req.budget == "low": vector[17] = 2.0
    elif req.budget == "medium": vector[18] = 2.0
    elif req.budget == "high": vector[19] = 2.0
    if "weekdays" in req.schedule: vector[20] = 1.5
    if "evenings" in req.schedule: vector[21] = 1.5
    if "weekends" in req.schedule: vector[22] = 1.5
    
    # Timeframe vector
    if req.timeframe == "3months": vector[23] = 1.5
    elif req.timeframe == "6months": vector[24] = 1.5
    elif req.timeframe == "12months": vector[25] = 1.5
    
    # AI Preference vector
    if req.ai_preference == "high": vector[26] = 2.0
    elif req.ai_preference == "medium": vector[27] = 1.0

    # New 8 dimensions
    if "fun" in req.teaching_style: vector[28] = 1.0
    if "strict" in req.teaching_style: vector[29] = 1.0
    if "patient" in req.teaching_style: vector[30] = 1.0
    if "inspiring" in req.teaching_style: vector[31] = 1.0
    
    if req.tutor_gender == "female": vector[32] = 1.5
    elif req.tutor_gender == "male": vector[33] = 1.5
    
    if req.other_certs == "tocfl": vector[34] = 1.5
    if req.other_certs == "hskk": vector[35] = 1.5

    return vector

def build_tutor_vector(tags_str: str) -> list:
    tags = tags_str.lower() if tags_str else ""
    return [
        1 if any(t in tags for t in ["hsk", "luyen thi", "giai de"]) else 0,
        1 if any(t in tags for t in ["giao tiep", "khau ngu", "phat am", "hoi thoai"]) else 0,
        1 if any(t in tags for t in ["thuong mai", "business", "dich thuat", "xuat nhap khau"]) else 0,
        1 if any(t in tags for t in ["tre em", "kids", "thieu nhi"]) else 0,
        1 if any(t in tags for t in ["nghe", "noi", "khau ngu", "giao tiep", "phan xa"]) else 0,
        1 if any(t in tags for t in ["phat am", "thanh dieu", "pinyin", "chuan"]) else 0,
        1 if any(t in tags for t in ["doc", "viet", "chu han", "viet lach"]) else 0,
        1 if any(t in tags for t in ["ngu phap", "cau truc", "chuyen sau"]) else 0,
        1 if any(t in tags for t in ["co ban", "beginner", "hsk1", "hsk2"]) else 0,
        1 if any(t in tags for t in ["trung cap", "hsk3", "hsk4"]) else 0,
        1 if any(t in tags for t in ["nang cao", "hsk5", "hsk6"]) else 0,
        1 if any(t in tags for t in ["hsk7", "hsk8", "hsk9", "master", "sieu cap"]) else 0,
        1 if "online" in tags or "offline" not in tags else 0,
        1 if "offline" in tags or "online" not in tags else 0,
        1 if any(t in tags for t in ["tre em", "kids"]) else 0,
        1 if any(t in tags for t in ["sinh vien", "hoc sinh", "dai hoc"]) else 0,
        1 if any(t in tags for t in ["nguoi di lam", "thuong mai", "doanh nghiep"]) else 0,
        2.0 if any(t in tags for t in ["thap", "150k"]) else 0,
        2.0 if any(t in tags for t in ["trung binh", "150-300k"]) else 0,
        2.0 if any(t in tags for t in ["cao", "tren300k"]) else 0,
        1.5, 1.5, 1.5,
        1.5 if any(t in tags for t in ["cap toc", "3 thang", "luyen thi"]) else 0,
        1.5 if any(t in tags for t in ["tieu chuan", "6 thang", "co ban"]) else 0,
        1.5 if any(t in tags for t in ["dai han", "1 nam", "tre em"]) else 0,
        2.0 if "online" in tags else 0,  
        1.0,
        # New dimensions matching
        1.0 if any(t in tags for t in ["vui ve", "nang dong", "hai huoc", "fun"]) else 0,
        1.0 if any(t in tags for t in ["nghiem khac", "ky luat", "strict"]) else 0,
        1.0 if any(t in tags for t in ["nhe nhang", "kien nhan", "ti mi", "patient"]) else 0,
        1.0 if any(t in tags for t in ["truyen cam hung", "thuc te", "inspiring"]) else 0,
        1.5 if any(t in tags for t in ["nu", "cô", "female"]) else 0,
        1.5 if any(t in tags for t in ["nam", "thầy", "male"]) else 0,
        1.5 if any(t in tags for t in ["tocfl", "phồn thể"]) else 0,
        1.5 if any(t in tags for t in ["hskk", "khẩu ngữ"]) else 0
    ]

def cosine_similarity_pure(vec1, vec2):
    dot_product = sum(a * b for a, b in zip(vec1, vec2))
    norm_a = math.sqrt(sum(a * a for a in vec1))
    norm_b = math.sqrt(sum(b * b for b in vec2))
    if norm_a == 0 or norm_b == 0: return 0.0
    return dot_product / (norm_a * norm_b)

@app.post("/recommend")
def recommend_tutors(req: AIRequest):
    user_vec = build_user_vector(req)
    scores = []
    for tutor in req.tutors_data:
        tags_str = tutor.get("TagsVector", tutor.get("tags_vector", ""))
        tutor_vec = build_tutor_vector(tags_str)
        score = cosine_similarity_pure(user_vec, tutor_vec)
        scores.append({**tutor, "similarity_score": round(float(score) * 100, 1)})
    scores.sort(key=lambda x: x["similarity_score"], reverse=True)
    return {"user_vector": user_vec, "recommendations": scores[:3]}

import urllib.request
import json

class ChatMessage(BaseModel):
    message: str
    tutors_data: List[dict] = []
    history: List[dict] = []

@app.post("/chat")
def chat_with_rag(req: ChatMessage):
    context_lines = []
    for t in req.tutors_data:
        context_lines.append(f"- Gia sư {t.get('Name', 'Ẩn danh')}: Dạy {t.get('TeachingLevels', '')}, chuyên {t.get('Specialization', '')}, giá {t.get('HourlyRate', 0)} VNĐ/giờ. Giới thiệu: {t.get('Bio', '')}")
    
    context_text = "\n".join(context_lines)
    if not context_text:
        context_text = "Hệ thống chưa có dữ liệu gia sư nào."

    # Xây dựng lịch sử chat
    history_text = ""
    for msg in req.history[-5:]: # Chỉ lấy 5 tin nhắn gần nhất
        role_name = "Người dùng" if msg.get("role") == "user" else "AI"
        history_text += f"{role_name}: {msg.get('content')}\n"

    prompt = f"""Dựa vào thông tin hệ thống của nền tảng gia sư tiếng Trung Lanying HSK dưới đây (chứa danh sách gia sư hiện có):
{context_text}

Lịch sử trò chuyện:
{history_text}

Người dùng hỏi: {req.message}
Hãy đóng vai trợ lý ảo tư vấn học tiếng Trung thân thiện, trả lời ngắn gọn, tự nhiên dựa theo lịch sử cuộc trò chuyện. Nếu người dùng hỏi tìm gia sư, hãy ưu tiên gợi ý từ danh sách trên."""

    url = "http://localhost:11434/api/generate"
    payload = {
        "model": "qwen2.5:7b",
        "prompt": prompt,
        "stream": False
    }
    
    try:
        req_body = json.dumps(payload).encode('utf-8')
        headers = {'Content-Type': 'application/json'}
        request = urllib.request.Request(url, data=req_body, headers=headers)
        with urllib.request.urlopen(request, timeout=300) as response:
            result = json.loads(response.read().decode('utf-8'))
            return {"reply": result.get("response", "Xin lỗi, tôi không thể trả lời lúc này.")}
    except Exception as e:
        return {"reply": f"Lỗi kết nối AI: Không thể gọi Ollama (qwen2.5:7b) tại localhost. Chi tiết: {str(e)}"}

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8001)
