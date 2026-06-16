using System.Text;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.EntityFrameworkCore;
using Microsoft.IdentityModel.Tokens;
using LanyingAPI.Models;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddControllers();
builder.Services.AddHttpClient();
builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseSqlite(builder.Configuration.GetConnectionString("DefaultConnection") ?? "Data Source=lanyinghsk.db"));

builder.Services.AddDbContext<PaymentDbContext>(options =>
    options.UseSqlite(builder.Configuration.GetConnectionString("PaymentConnection") ?? "Data Source=payments.db"));

builder.Services.AddAuthentication(JwtBearerDefaults.AuthenticationScheme)
    .AddJwtBearer(options =>
    {
        options.TokenValidationParameters = new TokenValidationParameters
        {
            ValidateIssuer = false,
            ValidateAudience = false,
            ValidateLifetime = true,
            ValidateIssuerSigningKey = true,
            IssuerSigningKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes("supersecretkey_lanyinghsk_1234567890"))
        };
    });

builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll", builder =>
    {
        builder.AllowAnyOrigin().AllowAnyMethod().AllowAnyHeader();
    });
});

var app = builder.Build();
app.UseStaticFiles(); // Hỗ trợ phục vụ file tĩnh cho upload
app.UseCors("AllowAll");
app.UseAuthentication();
app.UseAuthorization();
app.MapControllers();

// Apply migrations automatically
using (var scope = app.Services.CreateScope())
{
    var db = scope.ServiceProvider.GetRequiredService<AppDbContext>();
    
    // Seed DB from build output if CWD DB is empty
    var baseDb = System.IO.Path.Combine(System.AppDomain.CurrentDomain.BaseDirectory, "lanyinghsk.db");
    var cwdDb = System.IO.Path.GetFullPath("lanyinghsk.db");
    if (System.IO.File.Exists(baseDb) && (!System.IO.File.Exists(cwdDb) || new System.IO.FileInfo(cwdDb).Length < 100000))
    {
        System.IO.File.Copy(baseDb, cwdDb, true);
    }
    
    var basePayDb = System.IO.Path.Combine(System.AppDomain.CurrentDomain.BaseDirectory, "payments.db");
    var cwdPayDb = System.IO.Path.GetFullPath("payments.db");
    if (System.IO.File.Exists(basePayDb) && (!System.IO.File.Exists(cwdPayDb) || new System.IO.FileInfo(cwdPayDb).Length < 10000))
    {
        System.IO.File.Copy(basePayDb, cwdPayDb, true);
    }
    
    db.Database.EnsureCreated();
    try {
        db.Database.ExecuteSqlRaw("ALTER TABLE ClassRooms ADD COLUMN DeleteRequested INTEGER NOT NULL DEFAULT 0;");
    } catch { } // Bỏ qua nếu cột đã tồn tại
}

app.MapGet("/api/debugdb", (AppDbContext db) => {
    var dbPath = System.IO.Path.GetFullPath("lanyinghsk.db");
    var size = new System.IO.FileInfo(dbPath).Exists ? new System.IO.FileInfo(dbPath).Length : -1;
    var count = db.Tutors.Count();
    var outDir = System.IO.Directory.GetCurrentDirectory();
    
    var outDbPath = System.IO.Path.Combine(System.AppDomain.CurrentDomain.BaseDirectory, "lanyinghsk.db");
    var outSize = new System.IO.FileInfo(outDbPath).Exists ? new System.IO.FileInfo(outDbPath).Length : -1;

    return new { dbPath, size, count, outDir, outDbPath, outSize };
});

app.Run();
