-- Database schema for Attendance System

CREATE DATABASE IF NOT EXISTS attendance_system;
USE attendance_system;

-- Users table for admin authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin'
);

-- Insert default admin user (password: admin123, hashed)
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Classes table
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Sections table
CREATE TABLE sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    class_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    class_id INT,
    section_id INT,
    qr_code TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL
);

-- Attendance windows table
CREATE TABLE attendance_windows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    section_id INT,
    present_start TIME NOT NULL,
    present_end TIME NOT NULL,
    late_start TIME NOT NULL,
    late_end TIME NOT NULL,
    absent_after TIME NOT NULL,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
);

-- Attendance table
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    date DATE NOT NULL,
    time_in TIME,
    status ENUM('Present', 'Late', 'Absent') NOT NULL,
    method ENUM('QR', 'Manual'),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, date)
);

-- Insert sample data
INSERT INTO classes (name) VALUES ('Class 10'), ('Class 11'), ('Class 12');
INSERT INTO sections (name, class_id) VALUES ('A', 1), ('B', 1), ('A', 2), ('B', 2), ('A', 3), ('B', 3);
INSERT INTO attendance_windows (class_id, section_id, present_start, present_end, late_start, late_end, absent_after) 
VALUES (1, 1, '07:00:00', '08:00:00', '08:01:00', '09:00:00', '09:01:00'),
       (1, 2, '07:00:00', '08:00:00', '08:01:00', '09:00:00', '09:01:00'),
       (2, 3, '07:00:00', '08:00:00', '08:01:00', '09:00:00', '09:01:00'),
       (2, 4, '07:00:00', '08:00:00', '08:01:00', '09:00:00', '09:01:00'),
       (3, 5, '07:00:00', '08:00:00', '08:01:00', '09:00:00', '09:01:00'),
       (3, 6, '07:00:00', '08:00:00', '08:01:00', '09:00:00', '09:01:00');
INSERT INTO students (student_id, name, class_id, section_id, qr_code) VALUES
('22-32139', 'John Doe', 1, 1, '22-32139'),
('22-32140', 'Jane Smith', 1, 1, '22-32140'),
('22-32141', 'Bob Johnson', 1, 2, '22-32141');