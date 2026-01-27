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
    } elseif (isset($_POST['delete_student'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Student deleted successfully.';
    }
}

// Get students stats
$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$approvedStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'approved'")->fetchColumn();
$pendingStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'pending'")->fetchColumn();

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
    <div class="admin-container admin-layout">
        <div class="sidebar admin-sidebar">
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
            <div class="students-header">
                <h1>Manage Students</h1>
                <button type="button" class="btn" onclick="openModal()">Add New Student</button>
            </div>

            <?php if ($message): ?>
                <div class="feedback success" style="display: block; margin-bottom: 2rem;"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="students-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Total Students</h3>
                    <div class="stat-number"><?php echo $totalStudents; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Approved</h3>
                    <div class="stat-number"><?php echo $approvedStudents; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Pending</h3>
                    <div class="stat-number"><?php echo $pendingStudents; ?></div>
                </div>
            </div>

            <div class="students-table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
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
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['class_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($student['section_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $student['status']; ?>">
                                        <?php echo ucfirst($student['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=40x40&data=<?php echo urlencode($student['qr_code']); ?>" alt="QR Code" style="border-radius: 4px;">
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($student['status'] == 'pending'): ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                                <button type="submit" name="approve_student" class="btn btn-sm" style="background: #10b981; color: white;">Approve</button>
                                            </form>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                                <button type="submit" name="reject_student" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                                <button type="submit" name="delete_student" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Student</h2>
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
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
                                <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="section_id">Section:</label>
                        <select id="section_id" name="section_id" required>
                            <option value="">Select Section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo $section['id']; ?>"><?php echo htmlspecialchars($section['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" name="add_student" class="btn">Add Student</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('addStudentModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('addStudentModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addStudentModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>