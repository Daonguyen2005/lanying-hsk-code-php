// =============================================
// API.JS - Tập trung xử lý tất cả HTTP request
// Backend URL: http://localhost:8000
// =============================================

const API_BASE = "https://code-c-production.up.railway.app";

function getToken() {
    return localStorage.getItem("lanying_token");
}

function getUser() {
    const u = localStorage.getItem("lanying_user");
    return u ? JSON.parse(u) : null;
}

async function apiRequest(endpoint, method = "GET", body = null) {
    const headers = { "Content-Type": "application/json" };
    const token = getToken();
    if (token) headers["Authorization"] = `Bearer ${token}`;

    const options = { method, headers };
    if (body) options.body = JSON.stringify(body);

    const res = await fetch(`${API_BASE}${endpoint}`, options);
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || data.detail || data.title || "Có lỗi xảy ra");
    return data;
}

// Auth APIs
const AuthAPI = {
    register: (name, email, password, role = "student", gender = "") =>
        apiRequest("/api/auth/register", "POST", { name, email, password, role, gender }),
    login: (email, password) =>
        apiRequest("/api/auth/login", "POST", { email, password }),
    getDashboard: () => apiRequest("/api/auth/me/dashboard"),
    updateProfile: (name) => apiRequest("/api/auth/profile", "PUT", { name })
};

// Tutor APIs
const TutorAPI = {
    getAll: (hsk_level = null) =>
        apiRequest(`/api/tutors/${hsk_level ? `?hsk_level=${hsk_level}` : ""}`),
    getById: (id) => apiRequest(`/api/tutors/${id}`),
    getByUserId: (userId) => apiRequest(`/api/tutors/user/${userId}`),
    getSchedule: (tutorId = null) => apiRequest(`/api/tutors/schedule${tutorId ? `?tutor_id=${tutorId}` : ''}`),
    getMyClasses: () => apiRequest('/api/tutors/classes'),
    getMyProfile: () => apiRequest("/api/tutors/me"),
    updateMyProfile: (data) => apiRequest("/api/tutors/me", "PUT", data),
    book: (id, note = "") => apiRequest(`/api/tutors/${id}/book`, "POST", { note }),
    updateBookingStatus: (id, status, scheduleId = null) => apiRequest(`/api/tutors/bookings/${id}`, "PUT", { status, scheduleId }),
    deleteBooking: (id) => apiRequest(`/api/tutors/bookings/${id}`, 'DELETE'),
    deleteAllBookings: () => apiRequest('/api/tutors/bookings/all', 'DELETE'),
    // ClassRoom APIs
    getClassRooms: () => apiRequest('/api/tutors/classrooms'),
    createClassRoom: (data) => apiRequest('/api/tutors/classrooms', 'POST', data),
    deleteClassRoom: (id) => apiRequest(`/api/tutors/classrooms/${id}`, 'DELETE'),
    addStudentToClass: (classRoomId, studentId) => apiRequest(`/api/tutors/classrooms/${classRoomId}/students`, 'POST', { studentId }),
    removeStudentFromClass: (classRoomId, studentId) => apiRequest(`/api/tutors/classrooms/${classRoomId}/students/${studentId}`, 'DELETE'),
};

// Message APIs
const MessageAPI = {
    getRecent: (userId = null) => apiRequest(`/api/messages/recent${userId ? `?user_id=${userId}` : ''}`),
    getHistory: (userId) => apiRequest(`/api/messages/history/${userId}`),
    send: (receiverId, content) => apiRequest("/api/messages/send", "POST", { receiverId, content }),
    getUnreadCount: () => apiRequest("/api/messages/unread-count")
};

// Survey API
const SurveyAPI = {
    getQuestions: () => apiRequest("/api/surveyquestions"),
    submit: (data) => apiRequest("/api/survey/", "POST", data),
};

// Chatbot API
const ChatAPI = {
    send: async (message, history = []) => {
        const tutors = await TutorAPI.getAll();
        const res = await fetch("https://chatbot-production-eec8.up.railway.app/chat", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: message, tutors_data: tutors, history: history })
        });
        if (!res.ok) throw new Error("Lỗi kết nối AI Server");
        return await res.json();
    }
};

