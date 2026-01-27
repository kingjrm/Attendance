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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="students.php">Students</a></li>
                <li><a href="classes.php">Classes & Sections</a></li>
                <li><a href="attendance.php">Attendance Logs</a></li>
                <li><a href="settings.php" class="active">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
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