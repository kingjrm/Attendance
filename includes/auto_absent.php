<?php
// auto_absent.php - Run this script daily after absent_after time to mark absent students
require_once '../config/config.php';

$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Get all students
$stmt = $pdo->query("SELECT id FROM students");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($students as $student) {
    $studentId = $student['id'];
    
    // Check if attendance already recorded for today
    $stmt = $pdo->prepare("SELECT id FROM attendance WHERE student_id = ? AND date = ?");
    $stmt->execute([$studentId, $currentDate]);
    if ($stmt->rowCount() > 0) {
        continue; // Already recorded
    }
    
    // Get the attendance window for the student's class/section
    $stmt = $pdo->prepare("SELECT aw.absent_after FROM attendance_windows aw 
                          JOIN students s ON (aw.class_id = s.class_id AND aw.section_id = s.section_id) 
                          WHERE s.id = ?");
    $stmt->execute([$studentId]);
    $window = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($window && $currentTime >= $window['absent_after']) {
        // Mark as absent
        $stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, 'Absent')");
        $stmt->execute([$studentId, $currentDate]);
    }
}

echo "Auto-absent process completed.";
?>