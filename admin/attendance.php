<?php
require_once '../includes/auth.php';
require_once '../config/config.php';

$filterDate = $_GET['date'] ?? date('Y-m-d');
$filterClass = $_GET['class'] ?? '';
$filterSection = $_GET['section'] ?? '';

$query = "SELECT a.*, s.student_id, s.name, c.name as class_name, sec.name as section_name 
          FROM attendance a 
          JOIN students s ON a.student_id = s.id 
          LEFT JOIN classes c ON s.class_id = c.id 
          LEFT JOIN sections sec ON s.section_id = sec.id 
          WHERE a.date = ?";
$params = [$filterDate];

if ($filterClass) {
    $query .= " AND s.class_id = ?";
    $params[] = $filterClass;
}
if ($filterSection) {
    $query .= " AND s.section_id = ?";
    $params[] = $filterSection;
}

$query .= " ORDER BY a.time_in DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get classes and sections for filter
$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
$sections = $pdo->query("SELECT * FROM sections")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Logs</title>
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
                <li><a href="attendance.php" class="active">Attendance Logs</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Attendance Logs</h1>
            <form method="get">
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" value="<?php echo $filterDate; ?>">
                    </div>
                    <div class="form-group">
                        <label for="class">Class:</label>
                        <select id="class" name="class">
                            <option value="">All Classes</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>" <?php echo $filterClass == $class['id'] ? 'selected' : ''; ?>><?php echo $class['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="section">Section:</label>
                        <select id="section" name="section">
                            <option value="">All Sections</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo $section['id']; ?>" <?php echo $filterSection == $section['id'] ? 'selected' : ''; ?>><?php echo $section['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn">Filter</button>
                    </div>
                </div>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Status</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance as $record): ?>
                        <tr>
                            <td><?php echo $record['student_id']; ?></td>
                            <td><?php echo $record['name']; ?></td>
                            <td><?php echo $record['class_name']; ?></td>
                            <td><?php echo $record['section_name']; ?></td>
                            <td><?php echo $record['date']; ?></td>
                            <td><?php echo $record['time_in']; ?></td>
                            <td><?php echo $record['status']; ?></td>
                            <td><?php echo $record['method']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>