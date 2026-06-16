using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using LanyingAPI.Models;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class SurveyQuestionsController : ControllerBase
    {
        private readonly AppDbContext _db;

        public SurveyQuestionsController(AppDbContext db)
        {
            _db = db;
        }

        [HttpGet]
        public async Task<IActionResult> GetQuestions()
        {
            await SeedQuestionsIfEmpty();
            var questions = await _db.Questions
                .Include(q => q.Options)
                .OrderBy(q => q.Order)
                .ToListAsync();
            return Ok(questions);
        }

        private async Task SeedQuestionsIfEmpty()
        {
            if (await _db.Questions.AnyAsync()) return;

            var questions = new List<Question>
            {
                new Question { Text = "Mục tiêu học tiếng Trung của bạn là gì?", Order = 1, StepKey = "goals", Options = new List<QuestionOption> {
                    new QuestionOption { Text = "Lấy chứng chỉ HSK (Đi du học/Làm việc)", Value = "hsk_exam" },
                    new QuestionOption { Text = "Giao tiếp hàng ngày / Công sở", Value = "communication" },
                    new QuestionOption { Text = "Sở thích cá nhân (Xem phim, nghe nhạc)", Value = "hobby" }
                }},
                new Question { Text = "Trình độ hiện tại của bạn?", Order = 2, StepKey = "current_level", Options = new List<QuestionOption> {
                    new QuestionOption { Text = "Chưa biết gì (Người mới bắt đầu)", Value = "beginner" },
                    new QuestionOption { Text = "Đã học qua Pinyin, biết chào hỏi cơ bản (HSK 1-2)", Value = "hsk1" },
                    new QuestionOption { Text = "Có thể giao tiếp cơ bản, đọc hiểu đoạn văn ngắn (HSK 3-4)", Value = "hsk3" }
                }},
                new Question { Text = "Kỹ năng nào bạn muốn tập trung cải thiện nhất?", Order = 3, StepKey = "skills", Options = new List<QuestionOption> {
                    new QuestionOption { Text = "Nghe & Nói (Phản xạ giao tiếp)", Value = "listening_speaking" },
                    new QuestionOption { Text = "Đọc & Viết (Ngữ pháp, từ vựng thi HSK)", Value = "reading_writing" },
                    new QuestionOption { Text = "Phát âm chuẩn (Chữa ngọng, luyện giọng)", Value = "pronunciation" }
                }},
                new Question { Text = "Bạn thích hình thức học nào?", Order = 4, StepKey = "modes", Options = new List<QuestionOption> {
                    new QuestionOption { Text = "Học Online (1 kèm 1 qua Zoom/Meet)", Value = "online" },
                    new QuestionOption { Text = "Học Offline (Gia sư đến nhà)", Value = "offline" },
                    new QuestionOption { Text = "Học nhóm nhỏ (5-6 người)", Value = "group_small" },
                    new QuestionOption { Text = "Học nhóm vừa (8-10 người)", Value = "group_medium" }
                }},
                new Question { Text = "Độ tuổi của người học?", Order = 5, StepKey = "age", Options = new List<QuestionOption> {
                    new QuestionOption { Text = "Trẻ em (Dưới 12 tuổi)", Value = "kids" },
                    new QuestionOption { Text = "Học sinh / Sinh viên", Value = "student" },
                    new QuestionOption { Text = "Người đi làm", Value = "adult" }
                }},
                new Question { Text = "Ngân sách dự kiến cho mỗi buổi học?", Order = 6, StepKey = "budget", Options = new List<QuestionOption> {
                    new QuestionOption { Text = "Dưới 150k", Value = "low" },
                    new QuestionOption { Text = "150k - 300k", Value = "medium" },
                    new QuestionOption { Text = "Trên 300k (Gia sư bản xứ/Thạc sĩ)", Value = "high" }
                }},
                new Question { Text = "Bạn có thể học vào thời gian nào?", Order = 7, StepKey = "schedule", Options = new List<QuestionOption> {
                    new QuestionOption { Text = "Các buổi tối trong tuần", Value = "evenings" },
                    new QuestionOption { Text = "Cuối tuần (Thứ 7, Chủ Nhật)", Value = "weekends" },
                    new QuestionOption { Text = "Linh hoạt", Value = "flexible" }
                }}
            };

            _db.Questions.AddRange(questions);
            await _db.SaveChangesAsync();
        }
    }
}
