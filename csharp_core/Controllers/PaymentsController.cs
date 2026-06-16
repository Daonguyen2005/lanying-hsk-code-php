using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using LanyingAPI.Models;
using System.Text.Json;

namespace LanyingAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class PaymentsController : ControllerBase
    {
        private readonly AppDbContext _db;
        private readonly PaymentDbContext _paymentDb;
        private readonly IConfiguration _config;
        private readonly ILogger<PaymentsController> _logger;

        public PaymentsController(AppDbContext db, PaymentDbContext paymentDb, IConfiguration config, ILogger<PaymentsController> logger)
        {
            _db = db;
            _paymentDb = paymentDb;
            _config = config;
            _logger = logger;
        }

        public class CreatePaymentReq
        {
            public int BookingId { get; set; }
            public string Method { get; set; } = "vietqr";
        }

        [HttpPost("create")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> CreatePayment([FromBody] CreatePaymentReq req)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var booking = await _db.Bookings.FindAsync(req.BookingId);
            if (booking == null) return NotFound(new { detail = "Không tìm thấy thông tin đặt lịch" });

            if (booking.StudentId != userId) return Forbid();
            if (booking.Status != "accepted") return BadRequest(new { detail = "Gia sư chưa chấp nhận yêu cầu này hoặc đã bị từ chối." });

            var tutor = await _db.Tutors.FindAsync(booking.TutorId);
            if (tutor == null) return NotFound(new { detail = "Không tìm thấy Gia sư" });

            decimal amount = tutor.HourlyRate;
            if (amount <= 0) amount = 100000; // Default if not set

            // Check if there is an existing pending payment
            var existingPayment = await _paymentDb.Payments.FirstOrDefaultAsync(p => p.BookingId == req.BookingId && p.Status == "pending");
            Payment payment;
            if (existingPayment != null)
            {
                payment = existingPayment;
                payment.Method = req.Method;
                payment.Amount = amount;
            }
            else
            {
                payment = new Payment
                {
                    BookingId = req.BookingId,
                    StudentId = userId,
                    Amount = amount,
                    Method = req.Method,
                    Status = "pending",
                    OrderCode = "LY" + req.BookingId.ToString().PadLeft(6, '0')
                };
                _paymentDb.Payments.Add(payment);
            }
            
            await _paymentDb.SaveChangesAsync();

            // Sử dụng qr.sepay.vn để sinh ảnh mã QR
            string bankId = "MBBank"; // SePay sử dụng tên viết tắt hoặc BIN
            string accountNo = "0374345803"; 
            string accountName = "NGUYEN VAN DAO";
            
            string addInfo = payment.OrderCode;
            // Format link của SePay
            string qrUrl = $"https://qr.sepay.vn/img?bank={bankId}&acc={accountNo}&amount={amount}&des={addInfo}";

            return Ok(new {
                paymentId = payment.Id,
                amount = payment.Amount,
                orderCode = payment.OrderCode,
                qrUrl = qrUrl,
                status = payment.Status
            });
        }

        [HttpGet("{id}/status")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> GetPaymentStatus(int id)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var payment = await _paymentDb.Payments.FindAsync(id);
            if (payment == null) return NotFound();

            if (payment.StudentId != userId) return Forbid();

            return Ok(new { status = payment.Status, paidAt = payment.PaidAt });
        }

        // === SEPAY AUTO POLLING ===
        // Backend gọi SePay API để tìm giao dịch khớp với orderCode
        [HttpGet("{id}/check-sepay")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> CheckSePay(int id)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var payment = await _paymentDb.Payments.FindAsync(id);
            if (payment == null) return NotFound();
            if (payment.StudentId != userId) return Forbid();

            // Nếu đã paid rồi, trả về ngay
            if (payment.Status == "success") return Ok(new { paid = true, status = "success" });

            // Lấy API token từ config
            var sePayToken = _config["SePay:ApiToken"];
            if (string.IsNullOrEmpty(sePayToken))
                return Ok(new { paid = false, status = payment.Status, error = "SePay API token chưa được cấu hình" });

            // Gọi SePay API để kiểm tra giao dịch
            var httpClient = HttpContext.RequestServices.GetRequiredService<IHttpClientFactory>().CreateClient();
            httpClient.DefaultRequestHeaders.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("Bearer", sePayToken);

            // Lấy 20 giao dịch gần nhất
            var sePayUrl = $"https://my.sepay.vn/userapi/transactions/list?account_number=0374345803&limit=20";

            try
            {
                var response = await httpClient.GetAsync(sePayUrl);
                if (!response.IsSuccessStatusCode)
                    return Ok(new { paid = false, status = payment.Status, error = $"SePay API lỗi: {response.StatusCode}" });

                var content = await response.Content.ReadAsStringAsync();
                _logger.LogInformation($"[DEBUG SEPAY] RAW RESPONSE: {content}");
                using var doc = JsonDocument.Parse(content);
                var root = doc.RootElement;

                // SePay trả về transactions array
                JsonElement transactions;
                if (!root.TryGetProperty("transactions", out transactions))
                {
                    _logger.LogWarning("[DEBUG SEPAY] Không tìm thấy property 'transactions' trong JSON!");
                    return Ok(new { paid = false, status = payment.Status });
                }

                _logger.LogInformation($"[DEBUG SEPAY] Found {transactions.GetArrayLength()} transactions");
                foreach (var tx in transactions.EnumerateArray())
                {
                    // Kiểm tra nội dung giao dịch chứa orderCode
                    string txDesc = "";
                    if (tx.TryGetProperty("transaction_content", out var descProp)) txDesc = descProp.GetString() ?? "";
                    if (string.IsNullOrEmpty(txDesc) && tx.TryGetProperty("body", out var bodyProp)) txDesc = bodyProp.GetString() ?? "";

                    decimal txAmount = 0;
                    if (tx.TryGetProperty("amount_in", out var amtProp)) 
                    {
                        string amtStr = amtProp.GetString() ?? "0";
                        decimal.TryParse(amtStr, System.Globalization.NumberStyles.Any, System.Globalization.CultureInfo.InvariantCulture, out txAmount);
                    }

                    bool descMatches = txDesc.Contains(payment.OrderCode, StringComparison.OrdinalIgnoreCase);
                    bool amountMatches = txAmount >= payment.Amount;

                    _logger.LogInformation($"[DEBUG SEPAY] Checking TX: desc='{txDesc}' (matches {payment.OrderCode}? {descMatches}), amountIn={txAmount} (matches >= {payment.Amount}? {amountMatches})");

                    if (descMatches && amountMatches)
                    {
                        // Tìm thấy! Xác nhận thanh toán và mở khóa lớp
                        payment.Status = "success";
                        payment.PaidAt = DateTime.UtcNow;
                        if (tx.TryGetProperty("reference_number", out var refProp))
                            payment.TransactionId = refProp.GetString() ?? "SEPAY_AUTO";
                        else
                            payment.TransactionId = "SEPAY_AUTO_" + DateTime.UtcNow.Ticks;

                        var booking = await _db.Bookings.FindAsync(payment.BookingId);
                        if (booking != null)
                        {
                            booking.Status = "paid";
                            if (booking.ScheduleId.HasValue)
                            {
                                var schedule = await _db.TutorSchedules.FindAsync(booking.ScheduleId.Value);
                                if (schedule != null)
                                {
                                    if (!string.IsNullOrEmpty(schedule.Label))
                                    {
                                        var classRoom = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == schedule.TutorId && c.Name == schedule.Label);
                                        if (classRoom != null)
                                        {
                                            bool alreadyIn = await _db.ClassRoomStudents.AnyAsync(cs => cs.ClassRoomId == classRoom.Id && cs.StudentId == booking.StudentId);
                                            if (!alreadyIn)
                                                _db.ClassRoomStudents.Add(new ClassRoomStudent { ClassRoomId = classRoom.Id, StudentId = booking.StudentId, JoinedAt = DateTime.UtcNow });
                                        }
                                    }
                                    else
                                    {
                                        bool alreadyAssigned = await _db.TutorSchedules.AnyAsync(x => x.TutorId == schedule.TutorId && string.IsNullOrEmpty(x.Label) && x.ScheduleDate == schedule.ScheduleDate && x.StartPeriod == schedule.StartPeriod && x.StudentId == booking.StudentId);
                                        if (!alreadyAssigned)
                                            _db.TutorSchedules.Add(new TutorSchedule { TutorId = schedule.TutorId, ScheduleDate = schedule.ScheduleDate, DayOfWeek = schedule.DayOfWeek, StartPeriod = schedule.StartPeriod, EndPeriod = schedule.EndPeriod, StudentId = booking.StudentId, Label = schedule.Label, Location = schedule.Location, Color = schedule.Color });
                                    }
                                }
                            }
                        }

                        await _paymentDb.SaveChangesAsync();
                        await _db.SaveChangesAsync();
                        return Ok(new { paid = true, status = "success", message = "Thanh toán xác nhận tự động qua SePay!" });
                    }
                }

                return Ok(new { paid = false, status = payment.Status });
            }
            catch (Exception ex)
            {
                _logger.LogError($"[DEBUG SEPAY ERROR] Exception: {ex.Message}\n{ex.StackTrace}");
                return Ok(new { paid = false, status = payment.Status, error = ex.Message });
            }
        }


        // Học viên tự xác nhận đã chuyển tiền → mở khóa lớp
        [HttpPost("{id}/student-confirm")]
        [Microsoft.AspNetCore.Authorization.Authorize]
        public async Task<IActionResult> StudentConfirmPayment(int id)
        {
            var userIdStr = User.Claims.FirstOrDefault(c => c.Type == System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
            if (!int.TryParse(userIdStr, out int userId)) return Unauthorized();

            var payment = await _paymentDb.Payments.FindAsync(id);
            if (payment == null) return NotFound(new { detail = "Không tìm thấy giao dịch" });
            if (payment.StudentId != userId) return Forbid();
            if (payment.Status == "success") return Ok(new { detail = "Giao dịch đã được xác nhận trước đó", alreadyPaid = true });

            // Đánh dấu thanh toán thành công
            payment.Status = "success";
            payment.PaidAt = DateTime.UtcNow;
            payment.TransactionId = "STUDENT_CONFIRM_" + DateTime.UtcNow.Ticks;

            // Cập nhật booking → paid và mở khóa lớp học
            var booking = await _db.Bookings.FindAsync(payment.BookingId);
            if (booking != null)
            {
                booking.Status = "paid";

                if (booking.ScheduleId.HasValue)
                {
                    var schedule = await _db.TutorSchedules.FindAsync(booking.ScheduleId.Value);
                    if (schedule != null)
                    {
                        if (!string.IsNullOrEmpty(schedule.Label))
                        {
                            var classRoom = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == schedule.TutorId && c.Name == schedule.Label);
                            if (classRoom != null)
                            {
                                bool alreadyInClass = await _db.ClassRoomStudents.AnyAsync(cs => cs.ClassRoomId == classRoom.Id && cs.StudentId == booking.StudentId);
                                if (!alreadyInClass)
                                {
                                    _db.ClassRoomStudents.Add(new ClassRoomStudent {
                                        ClassRoomId = classRoom.Id,
                                        StudentId = booking.StudentId,
                                        JoinedAt = DateTime.UtcNow
                                    });
                                }
                            }
                        }
                        else
                        {
                            bool alreadyAssigned = await _db.TutorSchedules.AnyAsync(x => x.TutorId == schedule.TutorId && string.IsNullOrEmpty(x.Label) && x.ScheduleDate == schedule.ScheduleDate && x.StartPeriod == schedule.StartPeriod && x.StudentId == booking.StudentId);
                            if (!alreadyAssigned)
                            {
                                _db.TutorSchedules.Add(new TutorSchedule {
                                    TutorId = schedule.TutorId,
                                    ScheduleDate = schedule.ScheduleDate,
                                    DayOfWeek = schedule.DayOfWeek,
                                    StartPeriod = schedule.StartPeriod,
                                    EndPeriod = schedule.EndPeriod,
                                    StudentId = booking.StudentId,
                                    Label = schedule.Label,
                                    Location = schedule.Location,
                                    Color = schedule.Color
                                });
                            }
                        }
                    }
                }
            }

            await _paymentDb.SaveChangesAsync();
            await _db.SaveChangesAsync();
            return Ok(new { message = "Xác nhận thành công! Lớp học đã được mở khóa." });
        }


        public class WebhookReq
        {
            public string OrderCode { get; set; }
            public decimal Amount { get; set; }
            public string Status { get; set; }
            public string TransactionId { get; set; }
        }

        // Webhook from PayOS or MoMo (Mock version)
        [HttpPost("webhook")]
        public async Task<IActionResult> Webhook([FromBody] WebhookReq req)
        {
            // In production, MUST verify the signature from the payment gateway to prevent fraud!
            
            var payment = await _paymentDb.Payments.FirstOrDefaultAsync(p => p.OrderCode == req.OrderCode);
            if (payment == null) return NotFound(new { detail = "Không tìm thấy giao dịch với OrderCode này" });

            if (payment.Status == "success") return Ok(new { detail = "Giao dịch đã được xác nhận trước đó" });

            if (req.Status.ToLower() == "success" || req.Status.ToLower() == "paid")
            {
                payment.Status = "success";
                payment.TransactionId = req.TransactionId;
                payment.PaidAt = DateTime.UtcNow;

                var booking = await _db.Bookings.FindAsync(payment.BookingId);
                if (booking != null)
                {
                    booking.Status = "paid";

                    // Thêm học viên vào lớp sau khi thanh toán thành công
                    if (booking.ScheduleId.HasValue)
                    {
                        var schedule = await _db.TutorSchedules.FindAsync(booking.ScheduleId.Value);
                        if (schedule != null)
                        {
                            if (!string.IsNullOrEmpty(schedule.Label))
                            {
                                var classRoom = await _db.ClassRooms.FirstOrDefaultAsync(c => c.TutorId == schedule.TutorId && c.Name == schedule.Label);
                                if (classRoom != null)
                                {
                                    bool alreadyInClass = await _db.ClassRoomStudents.AnyAsync(cs => cs.ClassRoomId == classRoom.Id && cs.StudentId == booking.StudentId);
                                    if (!alreadyInClass)
                                    {
                                        _db.ClassRoomStudents.Add(new ClassRoomStudent {
                                            ClassRoomId = classRoom.Id,
                                            StudentId = booking.StudentId,
                                            JoinedAt = DateTime.UtcNow
                                        });
                                    }
                                }
                            }
                            else
                            {
                                bool alreadyAssigned = await _db.TutorSchedules.AnyAsync(x => x.TutorId == schedule.TutorId && string.IsNullOrEmpty(x.Label) && x.ScheduleDate == schedule.ScheduleDate && x.StartPeriod == schedule.StartPeriod && x.StudentId == booking.StudentId);
                                if (!alreadyAssigned)
                                {
                                    _db.TutorSchedules.Add(new TutorSchedule {
                                        TutorId = schedule.TutorId,
                                        ScheduleDate = schedule.ScheduleDate,
                                        DayOfWeek = schedule.DayOfWeek,
                                        StartPeriod = schedule.StartPeriod,
                                        EndPeriod = schedule.EndPeriod,
                                        StudentId = booking.StudentId,
                                        Label = schedule.Label,
                                        Location = schedule.Location,
                                        Color = schedule.Color
                                    });
                                }
                            }
                        }
                    }
                }

                await _paymentDb.SaveChangesAsync();
                await _db.SaveChangesAsync();
                return Ok(new { message = "Xác nhận thanh toán thành công, học viên đã được vào lớp" });
            }


            return BadRequest(new { detail = "Trạng thái không hợp lệ" });
        }
    }
}
