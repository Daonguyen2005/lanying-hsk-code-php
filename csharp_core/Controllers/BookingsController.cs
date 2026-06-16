using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Security.Claims;
using LanyingAPI.Models;

namespace LanyingAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize]
    public class BookingsController : ControllerBase
    {
        private readonly AppDbContext _db;

        public BookingsController(AppDbContext db)
        {
            _db = db;
        }

        [HttpGet("student")]
        public async Task<IActionResult> GetForStudent()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var bookings = await (from b in _db.Bookings
                                  join t in _db.Tutors on b.TutorId equals t.Id
                                  join u in _db.Users on t.UserId equals u.Id
                                  where b.StudentId == userId
                                  orderby b.CreatedAt descending
                                  select new {
                                      b.Id,
                                      b.Status,
                                      b.Note,
                                      b.CreatedAt,
                                      TutorName = u.Name,
                                      TutorAvatar = t.AvatarUrl,
                                      Amount = t.HourlyRate > 0 ? t.HourlyRate : 100000
                                  }).ToListAsync();

            return Ok(bookings);
        }

        [HttpGet("tutor")]
        public async Task<IActionResult> GetForTutor()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null) return BadRequest(new { detail = "Bạn không phải là gia sư." });

            var bookings = await (from b in _db.Bookings
                                  join u in _db.Users on b.StudentId equals u.Id
                                  where b.TutorId == tutor.Id
                                  orderby b.CreatedAt descending
                                  select new {
                                      b.Id,
                                      b.Status,
                                      b.Note,
                                      b.CreatedAt,
                                      StudentName = u.Name
                                  }).ToListAsync();

            return Ok(bookings);
        }

        public class StatusReq { public string status { get; set; } = string.Empty; }

        [HttpPut("{id}/status")]
        public async Task<IActionResult> UpdateStatus(int id, [FromBody] StatusReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var booking = await _db.Bookings.FindAsync(id);
            if (booking == null) return NotFound(new { detail = "Không tìm thấy booking." });

            var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == userId);
            if (tutor == null || booking.TutorId != tutor.Id) return Forbid();

            if (req.status != "accepted" && req.status != "rejected")
                return BadRequest(new { detail = "Trạng thái không hợp lệ." });

            booking.Status = req.status;
            await _db.SaveChangesAsync();

            return Ok(new { detail = "Cập nhật thành công." });
        }
    }
}
