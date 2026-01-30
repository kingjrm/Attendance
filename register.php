<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="container">
        <h1>Student Registration</h1>
        <form action="register.php" method="post" class="registration-form">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required>
            </div>
            <div class="form-group">
                <label for="name">Full Name:</label>
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
                    <?php
                    require_once 'config/config.php';
                    $classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($classes as $class) {
                        echo "<option value='{$class['id']}'>{$class['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="section_id">Section:</label>
                <select id="section_id" name="section_id" required>
                    <option value="">Select Section</option>
                    <?php
                    $sections = $pdo->query("SELECT * FROM sections")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($sections as $section) {
                        echo "<option value='{$section['id']}'>{$section['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="student/login.php">Login here</a></p>
            <p><a href="index.php">Back to Attendance</a></p>
        </div>
        <?php if (isset($_GET['message'])): ?>
            <div class="feedback <?php echo strpos($_GET['message'], 'success') !== false ? 'success' : 'error'; ?>" style="display: block; margin-top: 1rem;">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    $studentId = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validation
    if (empty($studentId)) $errors[] = 'Student ID is required.';
    if (empty($name)) $errors[] = 'Full name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($password)) $errors[] = 'Password is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirmPassword) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        // Check if student_id or email exists
        $stmt = $pdo->prepare("SELECT id FROM students WHERE student_id = ? OR email = ?");
        $stmt->execute([$studentId, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Student ID or Email already exists.';
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $qrCode = $studentId; // Use student ID as QR code

        try {
            $stmt = $pdo->prepare("INSERT INTO students (student_id, name, email, password, qr_code) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$studentId, $name, $email, $hashedPassword, $qrCode]);
            header('Location: student/register.php?message=Registration successful. Please wait for admin approval.');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $message = implode(' ', $errors);
        header('Location: student/register.php?message=' . urlencode($message));
        exit;
    }
}
?>