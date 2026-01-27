<?php
session_start();
if (!isset($_SESSION['student_logged_in']) || !$_SESSION['student_logged_in']) {
    header('Location: ../student_login.php');
    exit;
}

require_once '../config/config.php';

$studentId = $_SESSION['student_id'];
$stmt = $pdo->prepare("SELECT s.*, c.name as class_name, sec.name as section_name FROM students s LEFT JOIN classes c ON s.class_id = c.id LEFT JOIN sections sec ON s.section_id = sec.id WHERE s.id = ?");
$stmt->execute([$studentId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    if (!password_verify($currentPassword, $student['password'])) {
        $message = 'Current password is incorrect.';
    } else {
        $updateFields = ['name' => $name, 'email' => $email];
        $params = [$name, $email];

        if (!empty($newPassword)) {
            $updateFields['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $setClause = implode(', ', array_map(fn($k) => "$k = ?", array_keys($updateFields)));
        $stmt = $pdo->prepare("UPDATE students SET $setClause WHERE id = ?");
        $params[] = $studentId;
        $stmt->execute($params);
        $message = 'Profile updated successfully.';
        // Refresh student data
        $stmt = $pdo->prepare("SELECT s.*, c.name as class_name, sec.name as section_name FROM students s LEFT JOIN classes c ON s.class_id = c.id LEFT JOIN sections sec ON s.section_id = sec.id WHERE s.id = ?");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Student Portal</h2>
            <ul>
                <li><a href="student_dashboard.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a></li>
                <li><a href="student_profile.php" class="active">
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
            <h1>Your Profile</h1>
            <?php if ($message): ?>
                <div class="feedback <?php echo strpos($message, 'success') !== false ? 'success' : 'error'; ?>" style="display: block;"><?php echo $message; ?></div>
            <?php endif; ?>
            <div class="profile-info">
                <div class="qr-code-section">
                    <h3>Your QR Code</h3>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($student['qr_code']); ?>" alt="QR Code">
                    <p>Student ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                </div>
                <form method="post" class="profile-form">
                    <div class="form-group">
                        <label for="student_id">Student ID:</label>
                        <input type="text" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Class:</label>
                        <input type="text" value="<?php echo htmlspecialchars($student['class_name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Section:</label>
                        <input type="text" value="<?php echo htmlspecialchars($student['section_name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password (leave blank to keep current):</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>