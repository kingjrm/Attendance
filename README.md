# Student Attendance System

A full-stack QR-Based Student Attendance System with time restrictions, auto-absent logic, student registration, and admin approval.

## Features

- **Student Registration**: Students can register with email, password, and generate QR codes
- **Admin Approval**: Admins approve/reject student registrations
- **QR Code Scanning**: Camera-based QR scanning with manual ID entry fallback
- **Student Portal**: Students can login to view their attendance history and profile
- **Time Restrictions**: Configurable attendance windows per class/section (Present, Late, Absent)
- **Auto-Absent Logic**: Automatic marking of absent students after cutoff time
- **Admin Dashboard**: Professional dashboard with SVG icons, statistics, and filtering
- **Class/Section Management**: Create and manage classes and sections
- **Attendance Logs**: Detailed logs with date, time, status, method, and advanced filtering
- **Time Window Configuration**: Set custom attendance times per class/section
- **Secure Authentication**: Session-based authentication for both admin and students

## Setup Instructions

1. Import `database/schema.sql` into MySQL to create the database and tables
2. Update `.env` with your database credentials if needed
3. Access the attendance page at `http://localhost/Attendance/`
4. Student registration at `http://localhost/Attendance/register.php`
5. Student login at `http://localhost/Attendance/student/login.php`
6. Admin login at `http://localhost/Attendance/admin/login.php` (admin/admin123)
7. Run `includes/auto_absent.php` daily after 9:01 AM to mark absent students

## Database Updates

The students table has been updated with:
- email (unique)
- password (hashed)
- status (pending/approved/rejected)
- created_at timestamp

## Technologies

- HTML, CSS (Poppins font), JavaScript
- PHP with PDO
- MySQL
- html5-qrcode for QR scanning