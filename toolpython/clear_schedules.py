"""
Script xóa tất cả lịch học trong database.
Đồ án: Xây dựng website gia sư tiếng Trung
Sử dụng: python clear_schedules.py
"""
# -*- coding: utf-8 -*-
import sqlite3
import os
import sys
sys.stdout.reconfigure(encoding='utf-8')

# Tìm file database
DB_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'csharp_core', 'lanyinghsk.db')

if not os.path.exists(DB_PATH):
    print(f"[ERROR] Khong tim thay database tai: {DB_PATH}")
    exit(1)

try:
    conn = sqlite3.connect(DB_PATH)
    c = conn.cursor()
    
    # Đếm lịch hiện có
    c.execute("SELECT COUNT(*) FROM tutor_schedules")
    count = c.fetchone()[0]
    print(f"[INFO] Tong so lich hoc hien co: {count}")
    
    if count == 0:
        print("[OK] Khong co lich nao de xoa!")
        conn.close()
        exit(0)
    
    # Tự động xóa (không hỏi lại vì user đã yêu cầu)
    c.execute("DELETE FROM tutor_schedules")
    conn.commit()
    
    # Reset auto-increment
    try:
        c.execute("DELETE FROM sqlite_sequence WHERE name='tutor_schedules'")
        conn.commit()
    except Exception:
        pass
    
    print(f"[OK] Da xoa thanh cong {count} lich hoc!")
    print("[INFO] Refresh lai trang Thoi khoa bieu de thay thay doi.")
    
    conn.close()

except sqlite3.Error as e:
    print(f"[ERROR] Loi database: {e}")
