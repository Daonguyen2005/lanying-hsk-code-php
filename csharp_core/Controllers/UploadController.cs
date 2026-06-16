using Microsoft.AspNetCore.Mvc;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class UploadController : ControllerBase
    {
        private readonly IWebHostEnvironment _env;

        public UploadController(IWebHostEnvironment env)
        {
            _env = env;
        }

        [HttpPost]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> Upload(IFormFile file)
        {
            if (file == null || file.Length == 0)
                return BadRequest(new { detail = "Không tìm thấy file" });

            try
            {
                var uploadsFolder = Path.Combine(_env.WebRootPath, "uploads");
                if (!Directory.Exists(uploadsFolder))
                {
                    Directory.CreateDirectory(uploadsFolder);
                }

                var uniqueFileName = Guid.NewGuid().ToString() + "_" + Path.GetFileName(file.FileName);
                var filePath = Path.Combine(uploadsFolder, uniqueFileName);

                using (var stream = new FileStream(filePath, FileMode.Create))
                {
                    await file.CopyToAsync(stream);
                }

                // Chạy localhost:5213 nên trả về host từ request
                var request = HttpContext.Request;
                var url = $"{request.Scheme}://{request.Host}/uploads/{uniqueFileName}";

                return Ok(new { url = url });
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { detail = "Lỗi khi upload file: " + ex.Message });
            }
        }
    }
}
