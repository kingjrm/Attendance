<?php
// includes/process_attendance.php
header('Content-Type: application/json');
require_once '../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$identifier = $data['identifier'] ?? '';
$method = $data['method'] ?? '';

if (!$identifier) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Find student by ID or QR code
$stmt = $pdo->prepare("SELECT s.*, aw.present_start, aw.present_end, aw.late_start, aw.late_end, aw.absent_after 
                      FROM students s 
                      LEFT JOIN attendance_windows aw ON (aw.class_id = s.class_id AND aw.section_id = s.section_id) 
                      WHERE s.student_id = ? OR s.qr_code = ?");
$stmt->execute([$identifier, $identifier]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    $errorMessage = ($method == 'QR') ? 'QR code not recognized. Please try again or enter manually.' : 'Student ID not found. Please check and try again.';
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

// Check if already recorded today
$stmt = $pdo->prepare("SELECT id FROM attendance WHERE student_id = ? AND date = ?");
$stmt->execute([$student['id'], $currentDate]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => 'Attendance already recorded for today.']);
    exit;
}

// Determine status based on time
$status = 'Absent';
if ($currentTime >= $student['absent_after']) {
    echo json_encode(['success' => false, 'message' => 'Attendance window closed.']);
    exit;
} elseif ($currentTime >= $student['present_start'] && $currentTime <= $student['present_end']) {
    $status = 'Present';
} elseif ($currentTime >= $student['late_start'] && $currentTime <= $student['late_end']) {
    $status = 'Late';
} else {
    echo json_encode(['success' => false, 'message' => 'Outside attendance window.']);
    exit;
}

// Record attendance
$stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, time_in, status, method) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$student['id'], $currentDate, $currentTime, $status, $method]);

echo json_encode(['success' => true, 'message' => "Attendance recorded: {$status}"]);
?>