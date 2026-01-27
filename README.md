# Student Attendance System

A full-stack QR-Based Student Attendance System with time restrictions, manual ID entry, auto-absent logic, and class/section filtering.

## Features

- QR code scanning using camera access
- Manual student ID entry
- Time-based attendance windows (Present, Late, Absent)
- Automatic absent marking after cutoff time
- Admin dashboard with statistics and management
- Student, class, and section management
- Attendance logs with filtering
- Configurable time windows per class/section
- Secure admin authentication

## Installation

1. Set up XAMPP or similar PHP/MySQL environment.
2. Place the project in `htdocs/Attendance/`.
3. Update `.env` with your database credentials.
4. Run the SQL schema in `database/schema.sql` to create the database.
5. Access the attendance page at `http://localhost/Attendance/`.
6. Admin login at `http://localhost/Attendance/admin/login.php` (admin/admin123).

## Folder Structure

- `assets/` - CSS, JS, images
- `config/` - Configuration files
- `includes/` - PHP includes
- `database/` - SQL schema
- `admin/` - Admin pages
- `index.php` - Attendance page
- `.env` - Environment variables

## Auto-Absent Logic

Run `includes/auto_absent.php` daily after the absent cutoff time to mark students as absent if no attendance record exists for the day.

## Technologies

- HTML, CSS (Poppins font), JavaScript
- PHP with PDO
- MySQL
- Instascan for QR scanning