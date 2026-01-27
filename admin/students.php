<?php
require_once '../includes/auth.php';
require_once '../config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_student'])) {
        $studentId = $_POST['student_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $classId = $_POST['class_id'];
        $sectionId = $_POST['section_id'];
        $qrCode = $studentId; // Use student ID as QR code
        $status = 'approved'; // Admin added students are auto-approved

        $stmt = $pdo->prepare("INSERT INTO students (student_id, name, email, password, class_id, section_id, qr_code, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$studentId, $name, $email, $password, $classId, $sectionId, $qrCode, $status]);
        $message = 'Student added successfully.';
    } elseif (isset($_POST['approve_student'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE students SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Student approved successfully.';
    } elseif (isset($_POST['reject_student'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE students SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Student rejected.';
    }
}

// Get students
$students = $pdo->query("SELECT s.*, c.name as class_name, sec.name as section_name FROM students s LEFT JOIN classes c ON s.class_id = c.id LEFT JOIN sections sec ON s.section_id = sec.id ORDER BY s.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

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
                <li><a href="dashboard.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a></li>
                <li><a href="students.php" class="active">
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
                <li><a href="settings.php">
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
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
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
                        <th>Email</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo $student['student_id']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td><?php echo $student['class_name']; ?></td>
                            <td><?php echo $student['section_name']; ?></td>
                            <td><?php echo ucfirst($student['status']); ?></td>
                            <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=<?php echo urlencode($student['qr_code']); ?>" alt="QR Code"></td>
                            <td>
                                <?php if ($student['status'] == 'pending'): ?>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                        <button type="submit" name="approve_student" class="btn" style="background: #10b981;">Approve</button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                        <button type="submit" name="reject_student" class="btn btn-danger">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                        <button type="submit" name="delete_student" class="btn btn-danger">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>