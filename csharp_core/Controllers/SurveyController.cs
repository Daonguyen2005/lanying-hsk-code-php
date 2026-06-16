using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using LanyingAPI.Models;
using System.Net.Http;
using System.Text.Json;
using System.Text;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class SurveyController : ControllerBase
    {
        private readonly AppDbContext _db;
        private readonly HttpClient _http;

        public SurveyController(AppDbContext db, IHttpClientFactory httpClientFactory)
        {
            _db = db;
            _http = httpClientFactory.CreateClient();
        }

        public class SurveyReq 
        { 
            public string current_level { get; set; } 
            public string age { get; set; } 
            public string budget { get; set; }
            public List<string> goals { get; set; } = new();
            public List<string> skills { get; set; } = new();
            public List<string> modes { get; set; } = new();
            public List<string> schedule { get; set; } = new();
            public string timeframe { get; set; }
            public string ai_preference { get; set; }
            
            // New fields
            public List<string> teaching_style { get; set; } = new();
            public string tutor_gender { get; set; }
            public List<string> weaknesses { get; set; } = new();
            public string study_time { get; set; }
            public string long_term_goal { get; set; }
            public string other_certs { get; set; }
        }

        [HttpPost]
        public async Task<IActionResult> SubmitSurvey([FromBody] SurveyReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            int.TryParse(userIdStr, out int userId);
            var user = await _db.Users.FindAsync(userId);

            if (user != null)
            {
                user.CurrentLevel = req.current_level ?? "";
                user.Age = req.age ?? "";
                user.Budget = req.budget ?? "";
                user.LearningGoal = string.Join(",", req.goals ?? new List<string>());
                user.Skills = string.Join(",", req.skills ?? new List<string>());
                user.Modes = string.Join(",", req.modes ?? new List<string>());
                user.Schedule = string.Join(",", req.schedule ?? new List<string>());
                user.Timeframe = req.timeframe ?? "";
                user.AiPreference = req.ai_preference ?? "";
                
                // Map new fields
                user.TeachingStyle = string.Join(",", req.teaching_style ?? new List<string>());
                user.TutorGender = req.tutor_gender ?? "";
                user.StudyTime = req.study_time ?? "";
                user.LongTermGoal = req.long_term_goal ?? "";
                user.OtherCerts = req.other_certs ?? "";
                
                user.HasCompletedSurvey = true;
                await _db.SaveChangesAsync();
            }

            var tutorIdsWithSchedule = await _db.TutorSchedules.Select(s => s.TutorId).Distinct().ToListAsync();
            var tutors = await _db.Tutors.Where(t => t.IsApproved && tutorIdsWithSchedule.Contains(t.Id)).ToListAsync();
            
            var aiReq = new {
                req.current_level, req.goals, req.skills, req.modes, req.age, req.budget, req.schedule, req.timeframe, req.ai_preference,
                req.teaching_style, req.tutor_gender, req.weaknesses, req.study_time, req.long_term_goal, req.other_certs,
                tutors_data = tutors
            };

            var content = new StringContent(JsonSerializer.Serialize(aiReq), Encoding.UTF8, "application/json");
            var res = await _http.PostAsync("http://localhost:8001/recommend", content);
            
            if (!res.IsSuccessStatusCode) return StatusCode(500, new { detail = "Lỗi từ AI Service" });
            var result = await res.Content.ReadAsStringAsync();

            if (user != null)
            {
                user.AiRecommendations = result;
                await _db.SaveChangesAsync();
            }

            return Content(result, "application/json");
        }
    }
}
