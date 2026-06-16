using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using LanyingAPI.Models;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [Microsoft.AspNetCore.Authorization.Authorize]
    public class MessagesController : ControllerBase
    {
        private readonly AppDbContext _db;

        public MessagesController(AppDbContext db)
        {
            _db = db;
        }

        public class SendMessageReq { public int receiverId { get; set; } public string content { get; set; } = string.Empty; }

        [HttpPost("send")]
        public async Task<IActionResult> SendMessage([FromBody] SendMessageReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int senderId)) return Unauthorized();

            var senderUser = await _db.Users.FindAsync(senderId);
            var receiverUser = await _db.Users.FindAsync(req.receiverId);
            if (senderUser == null || receiverUser == null) return NotFound();

            if (senderUser.Role != "admin" && receiverUser.Role != "admin")
            {
                if (senderUser.Role == receiverUser.Role) 
                    return BadRequest(new { detail = "Không thể nhắn tin cho người cùng vai trò!" });

                int studentId = senderUser.Role == "student" ? senderId : req.receiverId;
                int tutorUserId = senderUser.Role == "tutor" ? senderId : req.receiverId;

                var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == tutorUserId);
                if (tutor == null) return BadRequest(new { detail = "Gia sư không tồn tại." });

                var hasAcceptedBooking = await _db.Bookings.AnyAsync(b => 
                    b.StudentId == studentId && b.TutorId == tutor.Id && (b.Status == "accepted" || b.Status == "paid"));
                
                if (!hasAcceptedBooking) {
                    return BadRequest(new { detail = "Bạn chỉ có thể nhắn tin khi yêu cầu đặt lịch đã được chấp nhận!" });
                }
            }

            var message = new Message
            {
                SenderId = senderId,
                ReceiverId = req.receiverId,
                Content = req.content,
                CreatedAt = DateTime.UtcNow
            };

            _db.Messages.Add(message);
            await _db.SaveChangesAsync();
            return Ok(message);
        }

        [HttpGet("history/{otherUserId}")]
        public async Task<IActionResult> GetHistory(int otherUserId)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var messages = await _db.Messages
                .Where(m => (m.SenderId == userId && m.ReceiverId == otherUserId) || 
                            (m.SenderId == otherUserId && m.ReceiverId == userId))
                .OrderBy(m => m.CreatedAt)
                .ToListAsync();

            var unreadMessages = messages.Where(m => m.ReceiverId == userId && !m.IsRead).ToList();
            if (unreadMessages.Any())
            {
                foreach(var m in unreadMessages) m.IsRead = true;
                await _db.SaveChangesAsync();
            }

            return Ok(messages);
        }

        [HttpGet("recent")]
        public async Task<IActionResult> GetRecentChats([FromQuery] int? user_id)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            // Lấy danh sách các user mà người này đã từng nhắn tin
            var contactIds = await _db.Messages
                .Where(m => m.SenderId == userId || m.ReceiverId == userId)
                .Select(m => m.SenderId == userId ? m.ReceiverId : m.SenderId)
                .Distinct()
                .ToListAsync();
            
            if (user_id.HasValue && user_id.Value != userId) {
                contactIds.Add(user_id.Value);
            }

            var contacts = await _db.Users
                .Where(u => contactIds.Contains(u.Id) || u.Role == "admin")
                .Select(u => new { u.Id, u.Name, u.Role })
                .Distinct()
                .ToListAsync();

            // Loại bỏ chính mình ra khỏi danh sách
            contacts = contacts.Where(c => c.Id != userId).ToList();

            var result = new List<object>();
            foreach (var c in contacts)
            {
                var unreadCount = await _db.Messages.CountAsync(m => m.SenderId == c.Id && m.ReceiverId == userId && !m.IsRead);
                result.Add(new { id = c.Id, name = c.Name, role = c.Role, unreadCount = unreadCount });
            }

            return Ok(result);
        }
        [HttpGet("unread-count")]
        public async Task<IActionResult> GetUnreadCount()
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var count = await _db.Messages
                .Where(m => m.ReceiverId == userId && !m.IsRead)
                .CountAsync();

            return Ok(new { count });
        }
    }
}
