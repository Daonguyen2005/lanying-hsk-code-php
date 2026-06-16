using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace LanyingAPI.Migrations
{
    /// <inheritdoc />
    public partial class AddScheduleIdToBooking : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<int>(
                name: "ScheduleId",
                table: "bookings",
                type: "INTEGER",
                nullable: true);
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "ScheduleId",
                table: "bookings");
        }
    }
}
