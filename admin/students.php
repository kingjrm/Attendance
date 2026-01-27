<?php
require_once '../includes/auth.php';
require_once '../config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_student'])) {
        $studentId = $_POST['student_id'];
        $name = $_POST['name'];
        $classId = $_POST['class_id'];
        $sectionId = $_POST['section_id'];
        $qrCode = $studentId; // Simple QR code as student ID

        $stmt = $pdo->prepare("INSERT INTO students (student_id, name, class_id, section_id, qr_code) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$studentId, $name, $classId, $sectionId, $qrCode]);
        $message = 'Student added successfully.';
    } elseif (isset($_POST['delete_student'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Student deleted successfully.';
    }
}

// Get students
$students = $pdo->query("SELECT s.*, c.name as class_name, sec.name as section_name FROM students s LEFT JOIN classes c ON s.class_id = c.id LEFT JOIN sections sec ON s.section_id = sec.id")->fetchAll(PDO::FETCH_ASSOC);

// Get classes and sections for dropdown
$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
$sections = $pdo->query("SELECT * FROM sections")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Attendance Admin</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="students.php" class="active">Students</a></li>
                <li><a href="classes.php">Classes & Sections</a></li>
                <li><a href="attendance.php">Attendance Logs</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Manage Students</h1>
            <?php if ($message): ?>
                <div class="feedback success" style="display: block;"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="student_id">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
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
                <button type="submit" name="add_student" class="btn">Add Student</button>
            </form>
            <h2>Students List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo $student['student_id']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['class_name']; ?></td>
                            <td><?php echo $student['section_name']; ?></td>
                            <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?php echo urlencode($student['qr_code']); ?>" alt="QR Code"></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                    <button type="submit" name="delete_student" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>