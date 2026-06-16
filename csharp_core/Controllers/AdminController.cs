using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using LanyingAPI.Models;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [Microsoft.AspNetCore.Authorization.Authorize]
    public class AdminController : ControllerBase
    {
        private readonly AppDbContext _db;

        public AdminController(AppDbContext db)
        {
            _db = db;
        }

        private bool IsAdmin()
        {
            var role = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.Role)?.Value;
            return role == "admin";
        }

        [HttpGet("users")]
        public async Task<IActionResult> GetUsers()
        {
            if (!IsAdmin()) return Unauthorized();
            var users = await _db.Users.ToListAsync();
            return Ok(users);
        }

        [HttpGet("tutors")]
        public async Task<IActionResult> GetTutors()
        {
            if (!IsAdmin()) return Unauthorized();
            var query = from u in _db.Users
                        where u.Role == "tutor"
                        join t in _db.Tutors on u.Id equals t.UserId into ut
                        from t in ut.DefaultIfEmpty()
                        select new {
                            Id = t != null ? t.Id : 0,
                            UserId = u.Id,
                            Name = u.Name,
                            Email = u.Email,
                            Gender = u.Gender,
                            Specialization = t != null ? t.Specialization : "Chưa cập nhật",
                            HourlyRate = t != null ? t.HourlyRate : 0,
                            IsApproved = t != null ? t.IsApproved : false
                        };
            return Ok(await query.ToListAsync());
        }

        [HttpGet("bookings")]
        public async Task<IActionResult> GetBookings()
        {
            if (!IsAdmin()) return Unauthorized();
            var bookings = await _db.Bookings.ToListAsync();
            return Ok(bookings);
        }

        [HttpGet("tutors/pending")]
        public async Task<IActionResult> GetPendingTutors()
        {
            if (!IsAdmin()) return Unauthorized();
            var tutors = await (from t in _db.Tutors
                                join u in _db.Users on t.UserId equals u.Id
                                where t.IsSubmittedForReview && !t.IsApproved
                                select new {
                                    t.Id,
                                    t.UserId,
                                    t.Name,
                                    t.Bio,
                                    t.TeachingLevels,
                                    t.Specialization,
                                    t.HourlyRate,
                                    t.TagsVector,
                                    t.AvatarUrl,
                                    t.CertificateUrl,
                                    t.IsApproved,
                                    t.IsSubmittedForReview,
                                    Gender = u.Gender
                                }).ToListAsync();
            return Ok(tutors);
        }

        [HttpPut("tutors/{id}/approve")]
        public async Task<IActionResult> ApproveTutor(int id)
        {
            if (!IsAdmin()) return Unauthorized();
            var tutor = await _db.Tutors.FindAsync(id);
            if (tutor == null) return NotFound();

            tutor.IsApproved = true;
            tutor.IsSubmittedForReview = false;
            await _db.SaveChangesAsync();

            return Ok(new { message = "Đã duyệt hồ sơ gia sư thành công!" });
        }

        [HttpPut("tutors/{id}/reject")]
        public async Task<IActionResult> RejectTutor(int id)
        {
            if (!IsAdmin()) return Unauthorized();
            var tutor = await _db.Tutors.FindAsync(id);
            if (tutor == null) return NotFound();

            tutor.IsApproved = false;
            tutor.IsSubmittedForReview = false;
            await _db.SaveChangesAsync();

            return Ok(new { message = "Đã từ chối hồ sơ gia sư!" });
        }

        [HttpGet("classrooms/pending-deletion")]
        public async Task<IActionResult> GetPendingDeletions()
        {
            if (!IsAdmin()) return Unauthorized();
            var classrooms = await (from c in _db.ClassRooms
                                    join u in _db.Users on c.TutorId equals u.Id
                                    where c.DeleteRequested
                                    select new {
                                        c.Id,
                                        c.Name,
                                        c.StartDate,
                                        c.TotalSessions,
                                        TutorName = u.Name,
                                        TutorId = u.Id
                                    }).ToListAsync();
            return Ok(classrooms);
        }

        [HttpDelete("classrooms/{id}/approve-delete")]
        public async Task<IActionResult> ApproveDeleteClassRoom(int id)
        {
            if (!IsAdmin()) return Unauthorized();
            var room = await _db.ClassRooms.FindAsync(id);
            if (room == null) return NotFound();

            var members = await _db.ClassRoomStudents.Where(m => m.ClassRoomId == id).ToListAsync();
            _db.ClassRoomStudents.RemoveRange(members);
            _db.ClassRooms.Remove(room);
            await _db.SaveChangesAsync();

            return Ok(new { message = "Đã xóa lớp học thành công!" });
        }

        [HttpPut("classrooms/{id}/reject-delete")]
        public async Task<IActionResult> RejectDeleteClassRoom(int id)
        {
            if (!IsAdmin()) return Unauthorized();
            var room = await _db.ClassRooms.FindAsync(id);
            if (room == null) return NotFound();

            room.DeleteRequested = false;
            await _db.SaveChangesAsync();

            return Ok(new { message = "Đã hủy yêu cầu xóa lớp!" });
        }

        // ====================== FULL CRUD API ======================

        public class UserUpdateReq {
            public string Name { get; set; } = string.Empty;
            public string Email { get; set; } = string.Empty;
            public string Role { get; set; } = string.Empty;
        }

        [HttpPut("users/{id}")]
        public async Task<IActionResult> UpdateUser(int id, [FromBody] UserUpdateReq req)
        {
            if (!IsAdmin()) return Unauthorized();
            var user = await _db.Users.FindAsync(id);
            if (user == null) return NotFound();

            user.Name = req.Name;
            user.Email = req.Email;
            user.Role = req.Role;
            await _db.SaveChangesAsync();
            return Ok(new { message = "Cập nhật học viên thành công!" });
        }

        [HttpDelete("users/{id}")]
        public async Task<IActionResult> DeleteUser(int id)
        {
            if (!IsAdmin()) return Unauthorized();
            var user = await _db.Users.FindAsync(id);
            if (user == null) return NotFound();
            _db.Users.Remove(user);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đã xóa học viên!" });
        }

        public class TutorUpdateReq {
            public string Name { get; set; } = string.Empty;
            public string Specialization { get; set; } = string.Empty;
            public int HourlyRate { get; set; }
            public bool IsApproved { get; set; }
        }

        [HttpPut("tutors/{id}/info")]
        public async Task<IActionResult> UpdateTutorInfo(int id, [FromBody] TutorUpdateReq req)
        {
            if (!IsAdmin()) return Unauthorized();
            var tutor = await _db.Tutors.FindAsync(id);
            if (tutor == null) return NotFound();

            tutor.Name = req.Name;
            tutor.Specialization = req.Specialization;
            tutor.HourlyRate = req.HourlyRate;
            tutor.IsApproved = req.IsApproved;
            await _db.SaveChangesAsync();
            return Ok(new { message = "Cập nhật gia sư thành công!" });
        }

        [HttpDelete("tutors/{id}")]
        public async Task<IActionResult> DeleteTutor(int id)
        {
            if (!IsAdmin()) return Unauthorized();
            var tutor = await _db.Tutors.FindAsync(id);
            if (tutor == null) return NotFound();
            _db.Tutors.Remove(tutor);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đã xóa gia sư!" });
        }

        public class BookingUpdateReq {
            public string Status { get; set; } = string.Empty;
        }

        [HttpPut("bookings/{id}")]
        public async Task<IActionResult> UpdateBooking(int id, [FromBody] BookingUpdateReq req)
        {
            if (!IsAdmin()) return Unauthorized();
            var booking = await _db.Bookings.FindAsync(id);
            if (booking == null) return NotFound();
            booking.Status = req.Status;
            await _db.SaveChangesAsync();
            return Ok(new { message = "Cập nhật đăng ký thành công!" });
        }

        [HttpDelete("bookings/{id}")]
        public async Task<IActionResult> DeleteBooking(int id)
        {
            if (!IsAdmin()) return Unauthorized();
            var booking = await _db.Bookings.FindAsync(id);
            if (booking == null) return NotFound();
            _db.Bookings.Remove(booking);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đã xóa đăng ký!" });
        }

        [HttpGet("classrooms")]
        public async Task<IActionResult> GetAllClassRooms()
        {
            if (!IsAdmin()) return Unauthorized();
            var classrooms = await (from c in _db.ClassRooms
                                    join u in _db.Users on c.TutorId equals u.Id
                                    select new {
                                        c.Id,
                                        c.Name,
                                        c.StartDate,
                                        c.TotalSessions,
                                        c.DeleteRequested,
                                        TutorName = u.Name,
                                        TutorId = u.Id
                                    }).ToListAsync();
            return Ok(classrooms);
        }

        public class ClassRoomUpdateReq {
            public string Name { get; set; } = string.Empty;
            public string StartDate { get; set; } = string.Empty;
            public int TotalSessions { get; set; }
        }

        [HttpPut("classrooms/{id}")]
        public async Task<IActionResult> UpdateClassRoom(int id, [FromBody] ClassRoomUpdateReq req)
        {
            if (!IsAdmin()) return Unauthorized();
            var room = await _db.ClassRooms.FindAsync(id);
            if (room == null) return NotFound();

            room.Name = req.Name;
            room.StartDate = req.StartDate;
            room.TotalSessions = req.TotalSessions;
            await _db.SaveChangesAsync();
            return Ok(new { message = "Cập nhật lớp học thành công!" });
        }
    }
}
