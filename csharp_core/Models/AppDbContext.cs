using Microsoft.EntityFrameworkCore;

namespace LanyingAPI.Models
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options) { }

        public DbSet<User> Users { get; set; }
        public DbSet<Tutor> Tutors { get; set; }
        public DbSet<Booking> Bookings { get; set; }
        public DbSet<Question> Questions { get; set; }
        public DbSet<QuestionOption> QuestionOptions { get; set; }
        public DbSet<Message> Messages { get; set; }
        public DbSet<TutorSchedule> TutorSchedules { get; set; }
        public DbSet<Homework> Homeworks { get; set; }
        public DbSet<ClassRoom> ClassRooms { get; set; }
        public DbSet<ClassRoomStudent> ClassRoomStudents { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<User>().ToTable("users");
            modelBuilder.Entity<Tutor>().ToTable("tutors");
            modelBuilder.Entity<Booking>().ToTable("bookings");
            modelBuilder.Entity<Question>().ToTable("questions");
            modelBuilder.Entity<QuestionOption>().ToTable("question_options");
            modelBuilder.Entity<Message>().ToTable("messages");
            modelBuilder.Entity<TutorSchedule>().ToTable("tutor_schedules");
            modelBuilder.Entity<Homework>().ToTable("homeworks");
            modelBuilder.Entity<ClassRoom>().ToTable("classrooms");
            modelBuilder.Entity<ClassRoomStudent>().ToTable("classroom_students");
            modelBuilder.Entity<Payment>().ToTable("payments");
        }
    }
}
