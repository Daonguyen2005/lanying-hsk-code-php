using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using LanyingAPI.Models;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class TutorsController : ControllerBase
    {
        private readonly AppDbContext _db;

        public TutorsController(AppDbContext db)
        {
            _db = db;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var tutorsQuery = await (from t in _db.Tutors
                                join u in _db.Users on t.UserId equals u.Id
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

            var tutorIds = tutorsQuery.Select(t => t.Id).ToList();
            var schedules = await _db.TutorSchedules
                                    .Where(s => tutorIds.Contains(s.TutorId))
                                    .Select(s => s.TutorId)
                                    .Distinct()
                                    .ToListAsync();

            var tutors = tutorsQuery.Select(t => new {
                t.Id, t.UserId, t.Name, t.Bio, t.TeachingLevels, t.Specialization,
                t.HourlyRate, t.TagsVector, t.AvatarUrl, t.CertificateUrl,
                t.IsApproved, t.IsSubmittedForReview, t.Gender,
                HasSchedule = schedules.Contains(t.Id)
            }).OrderByDescending(t => t.HasSchedule).ThenByDescending(t => t.Id).ToList();

            return Ok(tutors);
        }

        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(int id)
        {
            var tutor = await (from t in _db.Tutors
                               join u in _db.Users on t.UserId equals u.Id
                               where t.Id == id
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
                                   Gender = u.Gender
                               }).FirstOrDefaultAsync();

            if (tutor == null) return NotFound(new { detail = "Không tìm thấy gia sư" });
            return Ok(tutor);
        }

        [HttpGet("user/{userId}")]
        public async Task<IActionResult> GetByUserId(int userId)
        {
            var tutor = await (from t in _db.Tutors
                               join u in _db.Users on t.UserId equals u.Id
                               where t.UserId == userId
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
                                   Gender = u.Gender
                               }).FirstOrDefaultAsync();

            if (tutor == null) return NotFound(new { detail = "Không tìm thấy gia sư" });
            return Ok(tutor);
        }

        public class BookReq { public string note { get; set; } = string.Empty; }

        [HttpPost("{id}/book")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> BookTutor(int id, [FromBody] BookReq req)
        {
            var tutor = await _db.Tutors.FindAsync(id);
            if (tutor == null || !tutor.IsApproved) return BadRequest(new { detail = "Gia sư này chưa được duyệt." });

            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int studentId)) return Unauthorized();

            // Chỉ chặn khi có booking pending/accepted/paid - không chặn khi đã bị rejected
            var existingBooking = await _db.Bookings.FirstOrDefaultAsync(b => b.StudentId == studentId && b.TutorId == id
                && (b.Status == "pending" || b.Status == "accepted" || b.Status == "paid"));
            if (existingBooking != null) return BadRequest(new { detail = "Bạn đã đặt lịch với Gia sư này rồi, vui lòng kiểm tra Dashboard!" });

            // Giới hạn học viên chỉ được đặt tối đa 3 gia sư
            var bookedTutorsCount = await _db.Bookings
                .Where(b => b.StudentId == studentId)
                .Select(b => b.TutorId)
                .Distinct()
                .CountAsync();
            
            if (bookedTutorsCount >= 3) {
                return BadRequest(new { detail = "Bạn chỉ được phép gửi yêu cầu đặt lịch cho tối đa 3 Gia sư!" });
            }

            var booking = new Booking
            {
                StudentId = studentId,
                TutorId = id,
                Status = "pending",
                Note = req.note
            };
            _db.Bookings.Add(booking);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Booking successful", id = booking.Id });
        }

        [HttpGet("bookings")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> GetBookings()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            // Nếu là gia sư thì lấy danh sách booking của gia sư, nếu là học sinh thì lấy của học sinh
            var user = await _db.Users.FindAsync(userId);
            if (user == null) return NotFound();

            if (user.Role == "tutor")
            {
                var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
                if (tutor == null) return NotFound();
                var bookings = await _db.Bookings.Where(b => b.TutorId == tutor.Id).ToListAsync();
                var studentIds = bookings.Select(b => b.StudentId).Distinct().ToList();
                var students = await _db.Users.Where(u => studentIds.Contains(u.Id)).ToDictionaryAsync(u => u.Id, u => u.Name);
                var result = bookings.Select(b => new {
                    id = b.Id, studentId = b.StudentId,
                    studentName = students.ContainsKey(b.StudentId) ? students[b.StudentId] : $"HS-{b.StudentId}",
                    tutorId = b.TutorId, status = b.Status, note = b.Note, createdAt = b.CreatedAt
                });
                return Ok(result);
            }
            else
            {
                var bookings = await _db.Bookings.Where(b => b.StudentId == userId).ToListAsync();
                return Ok(bookings);
            }
        }

        // Xóa 1 booking theo ID
        [Microsoft.AspNetCore.Mvc.HttpDelete("bookings/{bookingId}")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> DeleteBooking(int bookingId)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var booking = await _db.Bookings.FindAsync(bookingId);
            if (booking == null) return NotFound(new { message = "Không tìm thấy booking" });

            var user = await _db.Users.FindAsync(userId);
            if (user == null) return Unauthorized();
            if (user.Role == "tutor") {
                var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
                if (tutor == null || booking.TutorId != tutor.Id) return Unauthorized();
            } else if (user.Role == "student" && booking.StudentId != userId) {
                return Unauthorized();
            }

            _db.Bookings.Remove(booking);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đã xóa booking thành công" });
        }

        // Xóa TẤT CẢ bookings của tutor (làm sạch danh sách)
        [Microsoft.AspNetCore.Mvc.HttpDelete("bookings/all")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> DeleteAllBookings()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var user = await _db.Users.FindAsync(userId);
            if (user == null || user.Role != "tutor") return Forbid();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return NotFound();

            var allBookings = await _db.Bookings.Where(b => b.TutorId == tutor.Id).ToListAsync();
            int count = allBookings.Count;
            _db.Bookings.RemoveRange(allBookings);
            await _db.SaveChangesAsync();
            return Ok(new { message = $"Đã xóa {count} yêu cầu đặt lịch thành công!" });
        }

        public class UpdateBookingReq { 

            public string status { get; set; } = string.Empty; 
            public int? scheduleId { get; set; }
        }

        [HttpPut("bookings/{id}")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> UpdateBookingStatus(int id, [FromBody] UpdateBookingReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var booking = await _db.Bookings.FindAsync(id);
            if (booking == null) return NotFound();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null || booking.TutorId != tutor.Id) return Unauthorized(new { message = "Bạn không có quyền cập nhật booking này" });
            if (!tutor.IsApproved) return BadRequest(new { detail = "Gia sư chưa được duyệt không thể thao tác!" });

            if (req.status == "accepted")
            {
                if (req.scheduleId == null || req.scheduleId == 0)
                {
                    return BadRequest(new { detail = "Vui lòng chọn một lớp học trống để xếp học viên vào." });
                }

                var schedule = await _db.TutorSchedules.FirstOrDefaultAsync(s => s.Id == req.scheduleId && s.TutorId == tutor.Id);
                if (schedule == null) return BadRequest(new { detail = "Không tìm thấy lịch học này hoặc lịch học không thuộc về bạn." });

                // Lưu ScheduleId vào Booking để sau khi thanh toán mới cho vào lớp
                // KHÔNG thêm vào lớp ngay - chờ thanh toán
                booking.ScheduleId = req.scheduleId;
                booking.Status = "accepted";
                await _db.SaveChangesAsync();
                return Ok(new { message = "Chấp nhận thành công. Học viên cần thanh toán để được vào lớp." });
            }

            if (req.status == "rejected")
            {
                booking.Status = "rejected";
                await _db.SaveChangesAsync();
                return Ok(new { message = "Cập nhật thành công." });
            }

            booking.Status = req.status;
            await _db.SaveChangesAsync();
            return Ok(new { message = "Cập nhật thành công" });
        }

        public class UpdateProfileReq
        {
            public string Bio { get; set; } = string.Empty;
            public string Specialization { get; set; } = string.Empty;
            public string TagsVector { get; set; } = string.Empty;
            public string TeachingLevels { get; set; } = string.Empty;
            public int HourlyRate { get; set; } = 0;
            public string AvatarBase64 { get; set; } = string.Empty;
            public string CertificateBase64 { get; set; } = string.Empty;
        }

        [HttpGet("me/profile")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> GetMyProfile()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return NotFound(new { message = "Chưa có hồ sơ" });
            
            return Ok(tutor);
        }

        [HttpPut("me/profile")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> UpdateProfile([FromBody] UpdateProfileReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            bool isNew = false;
            if (tutor == null) 
            {
                var user = await _db.Users.FindAsync(userId);
                if (user == null || user.Role != "tutor") return Unauthorized(new { message = "Không hợp lệ." });
                
                tutor = new Tutor { UserId = userId, Name = user.Name };
                _db.Tutors.Add(tutor);
                isNew = true;
            }

            tutor.Bio = req.Bio;
            tutor.Specialization = req.Specialization;
            tutor.TagsVector = req.TagsVector;
            tutor.TeachingLevels = req.TeachingLevels;
            tutor.HourlyRate = req.HourlyRate;
            
            if (!string.IsNullOrEmpty(req.AvatarBase64)) tutor.AvatarUrl = req.AvatarBase64;
            if (!string.IsNullOrEmpty(req.CertificateBase64)) tutor.CertificateUrl = req.CertificateBase64;
            
            tutor.IsSubmittedForReview = true;
            tutor.IsApproved = false; // Reset approval status upon re-submission

            await _db.SaveChangesAsync();
            return Ok(new { message = "Cập nhật và gửi xét duyệt thành công!" });
        }

        [HttpGet("schedule")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> GetSchedule([FromQuery] int? tutor_id, [FromQuery] string? start_date, [FromQuery] string? end_date, [FromQuery] int? mock_user_id = null)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            int userId = mock_user_id ?? 0;
            if (userId == 0 && userIdStr != null) int.TryParse(userIdStr, out userId);

            int targetTutorId = 0;
            if (tutor_id.HasValue && tutor_id.Value > 0) {
                targetTutorId = tutor_id.Value;
            } else if (userId > 0) {
                var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
                if (tutor != null) targetTutorId = tutor.Id;
            }

            if (targetTutorId == 0) {
                if (userId > 0) {
                    var user = await _db.Users.FindAsync(userId);
                    if (user != null && user.Role == "student") {
                        // Trả về lịch học của các lớp mà học viên này tham gia
                        var classRoomIds = await _db.ClassRoomStudents.Where(s => s.StudentId == userId).Select(s => s.ClassRoomId).ToListAsync();
                        var classes = await _db.ClassRooms.Where(c => classRoomIds.Contains(c.Id)).ToListAsync();
                        
                        var allSlots = new List<TutorSchedule>();
                        foreach(var c in classes) {
                            var slots = await _db.TutorSchedules.Where(s => s.TutorId == c.TutorId && s.Label == c.Name).ToListAsync();
                            allSlots.AddRange(slots);
                        }
                        
                        var individualSlots = await _db.TutorSchedules.Where(s => s.StudentId == userId).ToListAsync();
                        allSlots.AddRange(individualSlots);

                        var combinedQuery = allSlots.AsQueryable();
                        if (!string.IsNullOrEmpty(start_date)) {
                            combinedQuery = combinedQuery.Where(s => string.Compare(s.ScheduleDate, start_date) >= 0);
                        }
                        if (!string.IsNullOrEmpty(end_date)) {
                            combinedQuery = combinedQuery.Where(s => string.Compare(s.ScheduleDate, end_date) <= 0);
                        }

                        return Ok(combinedQuery.Distinct().ToList());
                    }
                }
                return BadRequest("Missing tutor_id");
            }

            var query = _db.TutorSchedules.Where(s => s.TutorId == targetTutorId);
            
            if (!string.IsNullOrEmpty(start_date)) {
                query = query.Where(s => string.Compare(s.ScheduleDate, start_date) >= 0);
            }
            if (!string.IsNullOrEmpty(end_date)) {
                query = query.Where(s => string.Compare(s.ScheduleDate, end_date) <= 0);
            }

            var schedule = await query.ToListAsync();
            return Ok(schedule);
        }

        public class ScheduleUpdateReq
        {
            public string ScheduleDate { get; set; } = string.Empty;
            public int DayOfWeek { get; set; }
            public int StartPeriod { get; set; }
            public int EndPeriod { get; set; }
            public string Status { get; set; } = string.Empty; // empty, available, booked
            public string Label { get; set; } = string.Empty;
            public string Location { get; set; } = string.Empty;
            public string Color { get; set; } = string.Empty;
        }

        [HttpPost("schedule")]
        public async Task<IActionResult> UpdateSchedule([FromBody] ScheduleUpdateReq req)
        {
            if (req.Status != "empty" && (string.IsNullOrWhiteSpace(req.Label) || string.IsNullOrWhiteSpace(req.Location)))
            {
                return BadRequest(new { message = "Vui lòng cung cấp đầy đủ Tên Khóa Học và Phòng học/Link Meet." });
            }

            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var user = await _db.Users.FindAsync(userId);
            if (user == null || user.Role != "tutor") return Forbid();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            // Xóa các slot trùng lặp trong cùng ngày
            var existingSlots = await _db.TutorSchedules
                .Where(s => s.TutorId == tutor.Id && s.ScheduleDate == req.ScheduleDate && 
                       (req.StartPeriod <= s.EndPeriod && req.EndPeriod >= s.StartPeriod))
                .ToListAsync();

            // Nếu đang tạo/sửa lịch, kiểm tra xem có slot nào khác bị đè lên không
            if (req.Status != "empty") {
                // Giả định: Slot đang sửa sẽ có cùng StartPeriod. Nếu có slot nào khác StartPeriod mà bị trùng -> Lỗi!
                var conflicts = existingSlots.Where(s => s.StartPeriod != req.StartPeriod).ToList();
                if (conflicts.Any()) {
                    return BadRequest(new { message = "Khung giờ này đã bị trùng với một buổi học khác của bạn. Vui lòng chọn giờ khác!" });
                }
            }

            if (existingSlots.Any())
            {
                _db.TutorSchedules.RemoveRange(existingSlots);
            }

            if (req.Status != "empty")
            {
                var newSlot = new TutorSchedule
                {
                    TutorId = tutor.Id,
                    ScheduleDate = req.ScheduleDate,
                    DayOfWeek = req.DayOfWeek,
                    StartPeriod = req.StartPeriod,
                    EndPeriod = req.EndPeriod,
                    Label = req.Label ?? "",
                    Location = req.Location ?? "",
                    Color = req.Color ?? "",
                    StudentId = req.Status == "booked" ? 1 : null // dummy
                };

                if (!string.IsNullOrEmpty(req.Label))
                {
                    var existingClass = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == tutor.Id && c.Name == req.Label);
                    if (existingClass == null)
                    {
                        _db.ClassRooms.Add(new ClassRoom {
                            TutorId = tutor.Id,
                            Name = req.Label,
                            Description = "Tạo tự động từ Thời khóa biểu",
                            Location = req.Location ?? "",
                            StartDate = req.ScheduleDate,
                            TotalSessions = 1
                        });
                    }
                    else
                    {
                        existingClass.TotalSessions += 1;
                    }
                }
                
                _db.TutorSchedules.Add(newSlot);
            }

            await _db.SaveChangesAsync();
            return Ok(new { message = "Updated successfully" });
        }

        public class GenerateReq
        {
            public string StartDate { get; set; } = string.Empty; // YYYY-MM-DD
            public string Pattern { get; set; } = string.Empty; // "2,4,6", "3,5,7", "8"
            public int StartPeriod { get; set; }
            public int EndPeriod { get; set; }
            public string Status { get; set; } = "available"; // available, booked
            public string Label { get; set; } = string.Empty;
            public string Location { get; set; } = string.Empty;
            public string Color { get; set; } = string.Empty;
            public int Weeks { get; set; } = 12; // Mặc định 12 tuần
        }

        [HttpPost("schedule/generate")]
        public async Task<IActionResult> GenerateSchedule([FromBody] GenerateReq req)
        {
            if (string.IsNullOrWhiteSpace(req.Label) || string.IsNullOrWhiteSpace(req.Location))
            {
                return BadRequest(new { message = "Vui lòng cung cấp đầy đủ Tên Khóa Học và Phòng học/Link Meet." });
            }

            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var user = await _db.Users.FindAsync(userId);
            if (user == null || user.Role != "tutor") return Forbid();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            if (!DateTime.TryParse(req.StartDate, out DateTime startDate))
            {
                return BadRequest("Invalid StartDate");
            }

            var patternDays = new List<int>();
            if (req.Pattern == "2,4,6") patternDays = new List<int> { 2, 4, 6 };
            else if (req.Pattern == "3,5,7") patternDays = new List<int> { 3, 5, 7 };
            else if (req.Pattern == "8") patternDays = new List<int> { 8 };
            else return BadRequest("Invalid Pattern");

            int generatedCount = 0;

            for (int w = 0; w < req.Weeks; w++)
            {
                for (int d = 0; d < 7; d++)
                {
                    var currentDate = startDate.AddDays(w * 7 + d);
                    int currentDayOfWeek = (int)currentDate.DayOfWeek;
                    int scheduleDayOfWeek = currentDayOfWeek == 0 ? 8 : currentDayOfWeek + 1; // 0 (Sun) -> 8, 1 (Mon) -> 2, ...

                    if (patternDays.Contains(scheduleDayOfWeek))
                    {
                        var newDateStr = currentDate.ToString("yyyy-MM-dd");

                        // Kiểm tra trùng lịch
                        var existing = await _db.TutorSchedules
                            .Where(s => s.TutorId == tutor.Id && s.ScheduleDate == newDateStr && 
                                   (req.StartPeriod <= s.EndPeriod && req.EndPeriod >= s.StartPeriod))
                            .ToListAsync();
                        
                        if (existing.Any()) {
                            return BadRequest(new { message = $"Lịch tạo tự động vào ngày {newDateStr} bị trùng với một lịch khác đã có. Vui lòng kiểm tra lại khung giờ!" });
                        }

                        var newSlot = new TutorSchedule
                        {
                            TutorId = tutor.Id,
                            ScheduleDate = newDateStr,
                            DayOfWeek = scheduleDayOfWeek,
                            StartPeriod = req.StartPeriod,
                            EndPeriod = req.EndPeriod,
                            Label = req.Label ?? "",
                            Location = req.Location ?? "",
                            Color = req.Color ?? "",
                            StudentId = req.Status == "booked" ? 1 : null
                        };
                        _db.TutorSchedules.Add(newSlot);
                        generatedCount++;
                    }
                }
            }

            if (!string.IsNullOrEmpty(req.Label) && generatedCount > 0)
            {
                var existingClass = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == tutor.Id && c.Name == req.Label);
                if (existingClass == null)
                {
                    _db.ClassRooms.Add(new ClassRoom {
                        TutorId = tutor.Id,
                        Name = req.Label,
                        Description = "Tạo tự động từ Thời khóa biểu",
                        Location = req.Location ?? "",
                        StartDate = req.StartDate,
                        TotalSessions = generatedCount
                    });
                }
                else
                {
                    existingClass.TotalSessions += generatedCount;
                }
            }

            await _db.SaveChangesAsync();
            return Ok(new { message = $"Đã tạo thành công {generatedCount} buổi học cho {req.Weeks} tuần tới!" });
        }

        [HttpDelete("schedule/bulk")]
        public async Task<IActionResult> DeleteBulkSchedule([FromQuery] string label)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            if (string.IsNullOrEmpty(label)) return BadRequest(new { message = "Tên lớp không hợp lệ" });

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            List<TutorSchedule> existingSlots;
            
            if (label == "[empty]" || label == "[Sẵn sàng]") {
                existingSlots = await _db.TutorSchedules
                    .Where(s => s.TutorId == tutor.Id && (s.Label == null || s.Label == ""))
                    .ToListAsync();
            } else {
                existingSlots = await _db.TutorSchedules
                    .Where(s => s.TutorId == tutor.Id && s.Label == label)
                    .ToListAsync();
            }

            if (!existingSlots.Any()) {
                if (label != "[empty]" && label != "[Sẵn sàng]") {
                    var emptyClassroom = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == tutor.Id && c.Name == label);
                    if (emptyClassroom != null) {
                        var students = await _db.ClassRoomStudents.Where(cs => cs.ClassRoomId == emptyClassroom.Id).ToListAsync();
                        foreach (var student in students) {
                            _db.Messages.Add(new Message {
                                SenderId = userId,
                                ReceiverId = student.StudentId,
                                Content = $"Giáo viên đã giải tán lớp '{label}'.",
                                CreatedAt = DateTime.UtcNow,
                                IsRead = false
                            });
                        }
                        if (students.Any()) _db.ClassRoomStudents.RemoveRange(students);
                        _db.ClassRooms.Remove(emptyClassroom);
                        await _db.SaveChangesAsync();
                        return Ok(new { message = $"Đã dọn dẹp lớp {label} (lớp này đã không còn lịch học nào)." });
                    }
                }
                return BadRequest(new { message = "Không tìm thấy lớp nào có tên này." });
            }

            int deletedCount = existingSlots.Count;
            _db.TutorSchedules.RemoveRange(existingSlots);

            // Xóa ClassRoom tương ứng nếu có
            if (label != "[empty]" && label != "[Sẵn sàng]") {
                var classroom = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == tutor.Id && c.Name == label);
                if (classroom != null) {
                    var students = await _db.ClassRoomStudents.Where(cs => cs.ClassRoomId == classroom.Id).ToListAsync();
                    foreach (var student in students) {
                        _db.Messages.Add(new Message {
                            SenderId = userId,
                            ReceiverId = student.StudentId,
                            Content = $"Giáo viên đã hủy toàn bộ lịch học và giải tán lớp '{label}'.",
                            CreatedAt = DateTime.UtcNow,
                            IsRead = false
                        });
                    }
                    if (students.Any()) {
                        _db.ClassRoomStudents.RemoveRange(students);
                    }
                    _db.ClassRooms.Remove(classroom);
                }
            }

            await _db.SaveChangesAsync();

            return Ok(new { message = $"Đã xóa toàn bộ {deletedCount} lịch của lớp {label}." });
        }

        public class RescheduleReq
        {
            public int SlotId { get; set; }
            public string NewDate { get; set; } = string.Empty;
            public int NewStartPeriod { get; set; }
            public int NewEndPeriod { get; set; }
            public string Reason { get; set; } = string.Empty;
        }

        [HttpPost("schedule/reschedule")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> RescheduleSlot([FromBody] RescheduleReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            var oldSlot = await _db.TutorSchedules.FirstOrDefaultAsync(s => s.Id == req.SlotId && s.TutorId == tutor.Id);
            if (oldSlot == null) return NotFound(new { message = "Không tìm thấy buổi học hoặc bạn không có quyền sửa buổi này." });

            string oldDate = oldSlot.ScheduleDate;
            int oldStart = oldSlot.StartPeriod;
            int oldEnd = oldSlot.EndPeriod;
            string label = oldSlot.Label;

            // Kiểm tra trùng lịch học bù
            var conflictingReschedule = await _db.TutorSchedules.FirstOrDefaultAsync(s => 
                s.TutorId == tutor.Id && s.Id != req.SlotId && s.ScheduleDate == req.NewDate && 
                (req.NewStartPeriod <= s.EndPeriod && req.NewEndPeriod >= s.StartPeriod));
            if (conflictingReschedule != null) {
                return BadRequest(new { message = $"Thời gian học bù (ngày {req.NewDate}) bị trùng với một lịch khác của bạn. Vui lòng chọn khung giờ khác!" });
            }

            // Remove old slot
            _db.TutorSchedules.Remove(oldSlot);

            // Create new slot
            var newSlot = new TutorSchedule
            {
                TutorId = tutor.Id,
                ScheduleDate = req.NewDate,
                DayOfWeek = (int)DateTime.Parse(req.NewDate).DayOfWeek == 0 ? 8 : (int)DateTime.Parse(req.NewDate).DayOfWeek + 1,
                StartPeriod = req.NewStartPeriod,
                EndPeriod = req.NewEndPeriod,
                Label = label,
                Location = oldSlot.Location,
                Color = oldSlot.Color,
                StudentId = oldSlot.StudentId
            };
            _db.TutorSchedules.Add(newSlot);

            // Find classroom and students to notify
            if (!string.IsNullOrEmpty(label))
            {
                var classroom = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == tutor.Id && c.Name == label);
                if (classroom != null)
                {
                    var students = await _db.ClassRoomStudents.Where(cs => cs.ClassRoomId == classroom.Id).Select(cs => cs.StudentId).ToListAsync();
                    foreach (var studentId in students)
                    {
                        var msgContent = $"Giáo viên đã thông báo nghỉ buổi học vào ngày {oldDate} (tiết {oldStart}-{oldEnd}). Lịch học bù đã được dời sang ngày {req.NewDate} (tiết {req.NewStartPeriod}-{req.NewEndPeriod}).";
                        if (!string.IsNullOrEmpty(req.Reason))
                        {
                            msgContent += $"\\nLý do: {req.Reason}";
                        }

                        _db.Messages.Add(new Message
                        {
                            SenderId = tutor.UserId,
                            ReceiverId = studentId,
                            Content = msgContent,
                            CreatedAt = DateTime.UtcNow,
                            IsRead = false
                        });
                    }
                }
            }

            await _db.SaveChangesAsync();

            return Ok(new { message = "Đã dời lịch thành công và gửi thông báo cho học viên!" });
        }


        [HttpGet("classes")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> GetMyClasses()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            var schedules = await _db.TutorSchedules
                .Where(s => s.TutorId == tutor.Id && s.Label != null && s.Label != "")
                .ToListAsync();

            var studentIds = schedules.Where(s => s.StudentId != null).Select(s => s.StudentId).Distinct().ToList();
            var students = await _db.Users.Where(u => studentIds.Contains(u.Id)).ToDictionaryAsync(u => u.Id);

            var groups = schedules.GroupBy(s => s.Label)
                .Select((g, index) => {
                    var studentIdsInClass = g.Where(x => x.StudentId != null).Select(x => x.StudentId.Value).Distinct().ToList();
                    var studentsList = studentIdsInClass.Select(id => new {
                        id = id,
                        name = students.ContainsKey(id) ? students[id].Name : $"HS-{id}"
                    }).ToList();
                    var classColor = g.FirstOrDefault(x => !string.IsNullOrEmpty(x.Color))?.Color ?? "#3b82f6";
                    var firstLocation = g.FirstOrDefault(x => !string.IsNullOrEmpty(x.Location))?.Location ?? "";

                    return new {
                        id = index + 1, // Dùng index làm id giả
                        name = g.Key,
                        description = "Được tạo tự động từ Thời khóa biểu",
                        location = firstLocation,
                        startDate = g.OrderBy(s => s.ScheduleDate).First().ScheduleDate,
                        totalSessions = g.Select(x => x.ScheduleDate).Distinct().Count(),
                        studentCount = studentIdsInClass.Count,
                        students = studentsList,
                        color = classColor
                    };
                })
                .ToList();

            return Ok(groups);
        }

        // ========== CLASSROOM APIs ==========

        public class ClassRoomReq {
            public string Name { get; set; } = string.Empty;
            public string Description { get; set; } = string.Empty;
            public string Location { get; set; } = string.Empty;
            public string StartDate { get; set; } = string.Empty;
            public int TotalSessions { get; set; } = 0;
        }

        [HttpGet("classrooms")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> GetClassRooms()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();
            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            var rooms = await _db.ClassRooms.Where(r => r.TutorId == tutor.Id && !r.DeleteRequested).ToListAsync();
            var roomIds = rooms.Select(r => r.Id).ToList();
            var members = await _db.ClassRoomStudents.Where(m => roomIds.Contains(m.ClassRoomId)).ToListAsync();
            var studentIds = members.Select(m => m.StudentId).Distinct().ToList();
            var students = await _db.Users.Where(u => studentIds.Contains(u.Id)).ToDictionaryAsync(u => u.Id);

            var labels = rooms.Select(r => r.Name).ToList();
            var schedulesColors = await _db.TutorSchedules
                .Where(s => s.TutorId == tutor.Id && labels.Contains(s.Label) && !string.IsNullOrEmpty(s.Color))
                .Select(s => new { s.Label, s.Color })
                .Distinct()
                .ToListAsync();
            var classColors = schedulesColors.GroupBy(s => s.Label).ToDictionary(g => g.Key, g => g.First().Color);

            var result = rooms.Select(r => {
                var roomMembers = members.Where(m => m.ClassRoomId == r.Id).ToList();
                return new {
                    id = r.Id, name = r.Name, description = r.Description,
                    location = r.Location, startDate = r.StartDate, totalSessions = r.TotalSessions,
                    createdAt = r.CreatedAt,
                    deleteRequested = r.DeleteRequested,
                    studentCount = roomMembers.Count,
                    students = roomMembers.Select(m => new {
                        id = m.StudentId,
                        name = students.ContainsKey(m.StudentId) ? students[m.StudentId].Name : $"HS-{m.StudentId}",
                        phone = students.ContainsKey(m.StudentId) ? students[m.StudentId].Phone : "",
                        currentLevel = students.ContainsKey(m.StudentId) ? students[m.StudentId].CurrentLevel : "",
                        joinedAt = m.JoinedAt
                    }).ToList(),
                    color = classColors.ContainsKey(r.Name) ? classColors[r.Name] : "#3b82f6"
                };
            }).ToList();

            return Ok(result);
        }

        [HttpGet("my-classes")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> GetStudentClasses()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var classRoomIds = await _db.ClassRoomStudents.Where(m => m.StudentId == userId).Select(m => m.ClassRoomId).ToListAsync();
            var rooms = await _db.ClassRooms.Where(r => classRoomIds.Contains(r.Id) && !r.DeleteRequested).ToListAsync();
            
            var tutorIds = rooms.Select(r => r.TutorId).Distinct().ToList();
            var tutors = await _db.Tutors.Where(t => tutorIds.Contains(t.Id)).ToDictionaryAsync(t => t.Id);

            var result = rooms.Select(r => {
                var tutor = tutors.ContainsKey(r.TutorId) ? tutors[r.TutorId] : null;
                return new {
                    id = r.Id,
                    name = r.Name,
                    description = r.Description,
                    location = r.Location,
                    startDate = r.StartDate,
                    totalSessions = r.TotalSessions,
                    tutorName = tutor?.Name ?? "Gia sư",
                    tutorAvatar = tutor?.AvatarUrl
                };
            });

            return Ok(result);
        }

        [HttpPost("classrooms")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> CreateClassRoom([FromBody] ClassRoomReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();
            var user = await _db.Users.FindAsync(userId);
            if (user == null || user.Role != "tutor") return Forbid();
            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            var room = new ClassRoom {
                TutorId = tutor.Id,
                Name = req.Name, Description = req.Description,
                Location = req.Location, StartDate = req.StartDate, TotalSessions = req.TotalSessions
            };
            _db.ClassRooms.Add(room);
            await _db.SaveChangesAsync();
            return Ok(new { id = room.Id, message = "Tạo lớp học thành công!" });
        }

        public class AddStudentReq { public int StudentId { get; set; } }

        [HttpPost("classrooms/{classRoomId}/students")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> AddStudentToClassRoom(int classRoomId, [FromBody] AddStudentReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var room = await _db.ClassRooms.FindAsync(classRoomId);
            if (room == null || room.TutorId != userId) return NotFound();

            bool exists = await _db.ClassRoomStudents.AnyAsync(m => m.ClassRoomId == classRoomId && m.StudentId == req.StudentId);
            if (exists) return BadRequest(new { message = "Học viên đã có trong lớp!" });

            _db.ClassRoomStudents.Add(new ClassRoomStudent { ClassRoomId = classRoomId, StudentId = req.StudentId });
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đã thêm học viên vào lớp!" });
        }

        [HttpDelete("classrooms/{classRoomId}/students/{studentId}")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> RemoveStudentFromClassRoom(int classRoomId, int studentId)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var room = await _db.ClassRooms.FindAsync(classRoomId);
            if (room == null || room.TutorId != userId) return Forbid();

            var member = await _db.ClassRoomStudents.FirstOrDefaultAsync(m => m.ClassRoomId == classRoomId && m.StudentId == studentId);
            if (member == null) return NotFound();

            _db.ClassRoomStudents.Remove(member);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đã xóa học viên khỏi lớp!" });
        }

        [HttpDelete("classrooms/{classRoomId}")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> DeleteClassRoom(int classRoomId)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var room = await _db.ClassRooms.FindAsync(classRoomId);
            if (room == null || room.TutorId != userId) return Forbid();

            var members = await _db.ClassRoomStudents.Where(m => m.ClassRoomId == classRoomId).ToListAsync();
            foreach (var member in members)
            {
                _db.Messages.Add(new Message
                {
                    SenderId = userId,
                    ReceiverId = member.StudentId,
                    Content = $"Giáo viên đã hủy lớp '{room.Name}'.",
                    CreatedAt = DateTime.UtcNow,
                    IsRead = false
                });
            }
            _db.ClassRoomStudents.RemoveRange(members);
            _db.ClassRooms.Remove(room);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Xóa lớp thành công" });
        }

        [HttpPut("classrooms/{classRoomId}/request-delete")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> RequestDeleteClassRoom(int classRoomId)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return Forbid();

            var room = await _db.ClassRooms.FindAsync(classRoomId);
            if (room == null || room.TutorId != tutor.Id) return NotFound(new { detail = "Không tìm thấy lớp học" });

            room.DeleteRequested = true;
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đã xóa lớp học!" });
        }
    }
}
