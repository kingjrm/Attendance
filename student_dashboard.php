<?php
session_start();
if (!isset($_SESSION['student_logged_in']) || !$_SESSION['student_logged_in']) {
    header('Location: student_login.php');
    exit;
}

require_once 'config/config.php';

$studentId = $_SESSION['student_id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$studentId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Get attendance history
$attendance = $pdo->prepare("SELECT a.*, DATE_FORMAT(a.date, '%M %d, %Y') as formatted_date FROM attendance a WHERE student_id = ? ORDER BY date DESC");
$attendance->execute([$studentId]);
$records = $attendance->fetchAll(PDO::FETCH_ASSOC);

// Calculate stats
$totalDays = count($records);
$presentCount = array_filter($records, fn($r) => $r['status'] == 'Present');
$lateCount = array_filter($records, fn($r) => $r['status'] == 'Late');
$absentCount = array_filter($records, fn($r) => $r['status'] == 'Absent');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Student Portal</h2>
            <ul>
                <li><a href="student_dashboard.php" class="active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a></li>
                <li><a href="student_profile.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Profile
                </a></li>
                <li><a href="student_logout.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Logout
                </a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h1>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Total Days</h3>
                    <div class="number"><?php echo $totalDays; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Present</h3>
                    <div class="number"><?php echo count($presentCount); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Late</h3>
                    <div class="number"><?php echo count($lateCount); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 14L21 3M21 3H15M21 3V9M3 3H9V21H3V3Z" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Absent</h3>
                    <div class="number"><?php echo count($absentCount); ?></div>
                </div>
            </div>
            <h2>Your Attendance History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Status</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo $record['formatted_date']; ?></td>
                            <td><?php echo $record['time_in'] ?: '-'; ?></td>
                            <td><?php echo $record['status']; ?></td>
                            <td><?php echo $record['method'] ?: '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>