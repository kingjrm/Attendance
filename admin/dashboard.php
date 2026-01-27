<?php
require_once '../includes/auth.php';
require_once '../config/config.php';

$currentDate = date('Y-m-d');

// Get stats
$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$presentToday = $pdo->query("SELECT COUNT(*) FROM attendance WHERE date = '$currentDate' AND status = 'Present'")->fetchColumn();
$lateToday = $pdo->query("SELECT COUNT(*) FROM attendance WHERE date = '$currentDate' AND status = 'Late'")->fetchColumn();
$absentToday = $pdo->query("SELECT COUNT(*) FROM attendance WHERE date = '$currentDate' AND status = 'Absent'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Attendance Admin</h2>
            <ul>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="students.php">Students</a></li>
                <li><a href="classes.php">Classes & Sections</a></li>
                <li><a href="attendance.php">Attendance Logs</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Dashboard</h1>
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <div class="number"><?php echo $totalStudents; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Present Today</h3>
                    <div class="number"><?php echo $presentToday; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Late Today</h3>
                    <div class="number"><?php echo $lateToday; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Absent Today</h3>
                    <div class="number"><?php echo $absentToday; ?></div>
                </div>
            </div>
            <h2>Recent Attendance</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT a.*, s.student_id, s.name, c.name as class_name, sec.name as section_name 
                                        FROM attendance a 
                                        JOIN students s ON a.student_id = s.id 
                                        LEFT JOIN classes c ON s.class_id = c.id 
                                        LEFT JOIN sections sec ON s.section_id = sec.id 
                                        ORDER BY a.date DESC, a.time_in DESC LIMIT 20");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['student_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['class_name']}</td>
                                <td>{$row['section_name']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['time_in']}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['method']}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>