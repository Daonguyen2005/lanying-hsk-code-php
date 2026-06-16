using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;

namespace LanyingAPI.Models
{
    public class User
    {
        [Key]
        public int Id { get; set; }
        public string Email { get; set; } = string.Empty;
        public string HashedPassword { get; set; } = string.Empty;
        public string Name { get; set; } = string.Empty;
        public string Role { get; set; } = "student";
        public string CurrentLevel { get; set; } = string.Empty;
        public string LearningGoal { get; set; } = string.Empty;
        public string Phone { get; set; } = string.Empty;
        public string Skills { get; set; } = string.Empty;
        public string Modes { get; set; } = string.Empty;
        public string Age { get; set; } = string.Empty;
        public string Budget { get; set; } = string.Empty;
        public string Schedule { get; set; } = string.Empty;
        public string Timeframe { get; set; } = string.Empty;
        public string AiPreference { get; set; } = string.Empty;
        
        public string? Gender { get; set; }
        public string? TeachingStyle { get; set; }
        public string? TutorGender { get; set; }
        public string? StudyTime { get; set; }
        public string? LongTermGoal { get; set; }
        public string? OtherCerts { get; set; }
        public string? AiRecommendations { get; set; }
        public bool HasCompletedSurvey { get; set; } = false;
    }

    public class Tutor
    {
        [Key]
        public int Id { get; set; }
        public int UserId { get; set; }
        public string Name { get; set; } = string.Empty;
        public string Bio { get; set; } = string.Empty;
        public string TeachingLevels { get; set; } = string.Empty;
        public string Specialization { get; set; } = string.Empty;
        public int HourlyRate { get; set; } = 0;
        public string TagsVector { get; set; } = string.Empty;
        public string AvatarUrl { get; set; } = string.Empty;
        public string CertificateUrl { get; set; } = string.Empty;
        public bool IsApproved { get; set; } = false;
        public bool IsSubmittedForReview { get; set; } = false;
    }

    public class Booking
    {
        [Key]
        public int Id { get; set; }
        public int StudentId { get; set; }
        public int TutorId { get; set; }
        public string Status { get; set; } = "pending";
        // accepted_pending_payment = gia sư đã chấp nhận, chờ thanh toán
        public string Note { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public int? ScheduleId { get; set; } // Lưu lại lịch học giáo viên chấp nhận cho học viên
    }

    public class Question
    {
        [Key]
        public int Id { get; set; }
        public string Text { get; set; } = string.Empty;
        public int Order { get; set; }
        public string StepKey { get; set; } = string.Empty; // e.g. "goals", "skills"
        
        public ICollection<QuestionOption> Options { get; set; }
    }

    public class QuestionOption
    {
        [Key]
        public int Id { get; set; }
        public int QuestionId { get; set; }
        public string Text { get; set; } = string.Empty;
        public string Value { get; set; } = string.Empty;

        [JsonIgnore]
        [ForeignKey("QuestionId")]
        public Question Question { get; set; }
    }

    public class Message
    {
        [Key]
        public int Id { get; set; }
        public int SenderId { get; set; }
        public int ReceiverId { get; set; }
        public string Content { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public bool IsRead { get; set; } = false;
    }
    public class TutorSchedule
    {
        [Key]
        public int Id { get; set; }
        public int TutorId { get; set; }
        public string ScheduleDate { get; set; } = string.Empty; // YYYY-MM-DD
        public int DayOfWeek { get; set; } // 2=Mon, 3=Tue, ..., 8=Sun
        public int StartPeriod { get; set; } // 1-16
        public int EndPeriod { get; set; } // 1-16
        public int? StudentId { get; set; } // Null if available, ID if booked
        public string Label { get; set; } = string.Empty; // Class name or empty
        public string Location { get; set; } = string.Empty; // Meet link / Room
        public string Color { get; set; } = string.Empty; // Custom hex color
    }

    public class ClassRoom
    {
        [Key]
        public int Id { get; set; }
        public int TutorId { get; set; }       // UserId of tutor
        public string Name { get; set; } = string.Empty;     // Label / Tên lớp
        public string Description { get; set; } = string.Empty;
        public string Location { get; set; } = string.Empty;
        public string StartDate { get; set; } = string.Empty; // YYYY-MM-DD
        public int TotalSessions { get; set; } = 0;
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public bool DeleteRequested { get; set; } = false;
    }

    public class ClassRoomStudent
    {
        [Key]
        public int Id { get; set; }
        public int ClassRoomId { get; set; }
        public int StudentId { get; set; }    // UserId of student
        public DateTime JoinedAt { get; set; } = DateTime.UtcNow;
    }

    public class Homework
    {
        [Key]
        public int Id { get; set; }
        public int TutorId { get; set; }
        public int StudentId { get; set; }
        public string Title { get; set; } = string.Empty;
        public string Content { get; set; } = string.Empty;
        public DateTime DueDate { get; set; }
        public string Status { get; set; } = "assigned"; // assigned, completed
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
    }

    public class Payment
    {
        [Key]
        public int Id { get; set; }
        public int BookingId { get; set; }
        public int StudentId { get; set; }
        public decimal Amount { get; set; }
        public string Method { get; set; } = string.Empty; // "momo" or "vietqr"
        public string Status { get; set; } = "pending";    // pending, success, failed
        public string TransactionId { get; set; } = string.Empty;
        public string OrderCode { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public DateTime? PaidAt { get; set; }
    }
}
