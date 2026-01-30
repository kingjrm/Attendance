-- Database updates for Attendance System to support subjects and per-subject attendance logs

USE attendance_system;

-- Add subjects table
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    class_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Add subject_id to attendance table
ALTER TABLE attendance ADD COLUMN subject_id INT;
ALTER TABLE attendance ADD FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE;

-- Insert sample subjects (assuming classes 1,2,3 are Class 10,11,12)
INSERT INTO subjects (name, class_id) VALUES
('Mathematics', 1),
('English', 1),
('Science', 1),
('History', 1),
('Mathematics', 2),
('English', 2),
('Physics', 2),
('Chemistry', 2),
('Mathematics', 3),
('English', 3),
('Biology', 3),
('Computer Science', 3);

-- Update sample attendance to include subject_id (assuming some subjects)
-- For existing records, assign random subjects for demo
UPDATE attendance SET subject_id = 1 WHERE id % 4 = 0;
UPDATE attendance SET subject_id = 2 WHERE id % 4 = 1;
UPDATE attendance SET subject_id = 3 WHERE id % 4 = 2;
UPDATE attendance SET subject_id = 4 WHERE id % 4 = 3;