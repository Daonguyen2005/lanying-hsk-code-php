using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Microsoft.IdentityModel.Tokens;
using LanyingAPI.Models;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AuthController : ControllerBase
    {
        private readonly AppDbContext _db;
        private readonly IConfiguration _config;

        public AuthController(AppDbContext db, IConfiguration config)
        {
            _db = db;
            _config = config;
        }

        public class RegisterReq { public string Name { get; set; } public string Email { get; set; } public string Password { get; set; } public string Role { get; set; } public string Gender { get; set; } }
        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] RegisterReq req)
        {
            if (string.IsNullOrEmpty(req.Password) || req.Password.Length < 6) 
                return BadRequest(new { detail = "Mật khẩu phải có ít nhất 6 ký tự" });

            if (await _db.Users.AnyAsync(u => u.Email == req.Email)) return BadRequest(new { detail = "Email đã tồn tại" });
            
            var user = new User { Email = req.Email, Name = req.Name, HashedPassword = req.Password, Role = req.Role, Gender = req.Gender };
            _db.Users.Add(user);
            await _db.SaveChangesAsync();
            return Ok(new { message = "Đăng ký thành công" });
        }

        public class LoginReq { public string Email { get; set; } public string Password { get; set; } }
        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] LoginReq req)
        {
            var user = await _db.Users.FirstOrDefaultAsync(u => u.Email == req.Email && u.HashedPassword == req.Password);
            if (user == null) return Unauthorized(new { detail = "Sai email hoặc mật khẩu" });

            var claims = new[] { 
                new Claim(ClaimTypes.NameIdentifier, user.Id.ToString()),
                new Claim(ClaimTypes.Role, user.Role)
            };
            var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(_config["Jwt:Key"] ?? "supersecretkey_lanyinghsk_1234567890"));
            var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);
            var token = new JwtSecurityToken(claims: claims, expires: DateTime.Now.AddDays(7), signingCredentials: creds);

            return Ok(new { 
                access_token = new JwtSecurityTokenHandler().WriteToken(token), 
                user = new { 
                    id = user.Id, 
                    name = user.Name, 
                    role = user.Role,
                    currentLevel = user.CurrentLevel,
                    learningGoal = user.LearningGoal,
                    phone = user.Phone,
                    hasCompletedSurvey = user.HasCompletedSurvey
                } 
            });
        }
        
        public class UpdateProfileReq { 
            public string Name { get; set; } = string.Empty;
            public string CurrentLevel { get; set; } = string.Empty;
            public string LearningGoal { get; set; } = string.Empty;
            public string Phone { get; set; } = string.Empty;
            
            // Các trường của Tutor
            public string AvatarUrl { get; set; } = null;
            public string CertificateUrl { get; set; } = null;
            public string Bio { get; set; } = null;
            public string TeachingLevels { get; set; } = null;
            public string Specialization { get; set; } = null;
            public int? HourlyRate { get; set; } = null;
        }

        [Microsoft.AspNetCore.Authorization.Authorize]
        [HttpGet("profile")]
        public async Task<IActionResult> GetProfile()
        {
            var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (userIdClaim == null) return Unauthorized(new { detail = "Vui lòng đăng nhập" });
            
            var user = await _db.Users.FindAsync(int.Parse(userIdClaim));
            if (user == null) return NotFound(new { detail = "Không tìm thấy người dùng" });

            if (user.Role == "tutor")
            {
                var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == user.Id);
                return Ok(new { 
                    id = user.Id, name = user.Name, role = user.Role, 
                    phone = user.Phone, currentLevel = user.CurrentLevel, learningGoal = user.LearningGoal,
                    avatarUrl = tutor?.AvatarUrl, certificateUrl = tutor?.CertificateUrl,
                    bio = tutor?.Bio, teachingLevels = tutor?.TeachingLevels,
                    specialization = tutor?.Specialization, hourlyRate = tutor?.HourlyRate,
                    isApproved = tutor?.IsApproved
                });
            }

            return Ok(new { 
                id = user.Id, 
                name = user.Name, 
                role = user.Role,
                currentLevel = user.CurrentLevel,
                learningGoal = user.LearningGoal,
                phone = user.Phone
            });
        }

        [Microsoft.AspNetCore.Authorization.Authorize]
        [HttpPut("profile")]
        public async Task<IActionResult> UpdateProfile([FromBody] UpdateProfileReq req)
        {
            var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (userIdClaim == null) return Unauthorized(new { detail = "Vui lòng đăng nhập" });
            
            var user = await _db.Users.FindAsync(int.Parse(userIdClaim));
            if (user == null) return NotFound(new { detail = "Không tìm thấy người dùng" });

            if (!string.IsNullOrEmpty(req.Name)) user.Name = req.Name;
            if (req.CurrentLevel != null) user.CurrentLevel = req.CurrentLevel;
            if (req.LearningGoal != null) user.LearningGoal = req.LearningGoal;
            if (req.Phone != null) user.Phone = req.Phone;

            if (user.Role == "tutor")
            {
                var tutor = await _db.Tutors.FirstOrDefaultAsync(t => t.UserId == user.Id);
                if (tutor != null)
                {
                    if (req.AvatarUrl != null) tutor.AvatarUrl = req.AvatarUrl;
                    if (req.CertificateUrl != null) tutor.CertificateUrl = req.CertificateUrl;
                    if (req.Bio != null) tutor.Bio = req.Bio;
                    if (req.TeachingLevels != null) tutor.TeachingLevels = req.TeachingLevels;
                    if (req.Specialization != null) tutor.Specialization = req.Specialization;
                    if (req.HourlyRate != null) tutor.HourlyRate = req.HourlyRate.Value;
                }
            }

            await _db.SaveChangesAsync();
            return Ok(new { message = "Cập nhật hồ sơ thành công" });
        }
    }
}
