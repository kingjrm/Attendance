<?php
require_once '../includes/auth.php';
require_once '../config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $classId = $_POST['class_id'];
    $sectionId = $_POST['section_id'];
    $presentStart = $_POST['present_start'];
    $presentEnd = $_POST['present_end'];
    $lateStart = $_POST['late_start'];
    $lateEnd = $_POST['late_end'];
    $absentAfter = $_POST['absent_after'];

    // Check if exists, update or insert
    $stmt = $pdo->prepare("SELECT id FROM attendance_windows WHERE class_id = ? AND section_id = ?");
    $stmt->execute([$classId, $sectionId]);
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE attendance_windows SET present_start = ?, present_end = ?, late_start = ?, late_end = ?, absent_after = ? WHERE class_id = ? AND section_id = ?");
        $stmt->execute([$presentStart, $presentEnd, $lateStart, $lateEnd, $absentAfter, $classId, $sectionId]);
        $message = 'Attendance window updated successfully.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO attendance_windows (class_id, section_id, present_start, present_end, late_start, late_end, absent_after) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$classId, $sectionId, $presentStart, $presentEnd, $lateStart, $lateEnd, $absentAfter]);
        $message = 'Attendance window added successfully.';
    }
}

// Get current windows
$windows = $pdo->query("SELECT aw.*, c.name as class_name, s.name as section_name FROM attendance_windows aw JOIN classes c ON aw.class_id = c.id JOIN sections s ON aw.section_id = s.id")->fetchAll(PDO::FETCH_ASSOC);

// Get classes and sections
$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
$sections = $pdo->query("SELECT * FROM sections")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Attendance Admin</h2>
            <ul>
                <li><a href="dashboard.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a></li>
                <li><a href="students.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Students
                </a></li>
                <li><a href="classes.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Classes & Sections
                </a></li>
                <li><a href="attendance.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Attendance Logs
                </a></li>
                <li><a href="settings.php" class="active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Settings
                </a></li>
                <li><a href="logout.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Logout
                </a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Attendance Time Windows</h1>
            <?php if ($message): ?>
                <div class="feedback success" style="display: block;"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="class_id">Class:</label>
                    <select id="class_id" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="section_id">Section:</label>
                    <select id="section_id" name="section_id" required>
                        <option value="">Select Section</option>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?php echo $section['id']; ?>"><?php echo $section['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="present_start">Present Start:</label>
                    <input type="time" id="present_start" name="present_start" required>
                </div>
                <div class="form-group">
                    <label for="present_end">Present End:</label>
                    <input type="time" id="present_end" name="present_end" required>
                </div>
                <div class="form-group">
                    <label for="late_start">Late Start:</label>
                    <input type="time" id="late_start" name="late_start" required>
                </div>
                <div class="form-group">
                    <label for="late_end">Late End:</label>
                    <input type="time" id="late_end" name="late_end" required>
                </div>
                <div class="form-group">
                    <label for="absent_after">Absent After:</label>
                    <input type="time" id="absent_after" name="absent_after" required>
                </div>
                <button type="submit" class="btn">Save Settings</button>
            </form>
            <h2>Current Time Windows</h2>
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Present Start</th>
                        <th>Present End</th>
                        <th>Late Start</th>
                        <th>Late End</th>
                        <th>Absent After</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($windows as $window): ?>
                        <tr>
                            <td><?php echo $window['class_name']; ?></td>
                            <td><?php echo $window['section_name']; ?></td>
                            <td><?php echo $window['present_start']; ?></td>
                            <td><?php echo $window['present_end']; ?></td>
                            <td><?php echo $window['late_start']; ?></td>
                            <td><?php echo $window['late_end']; ?></td>
                            <td><?php echo $window['absent_after']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>