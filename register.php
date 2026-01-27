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
            <p>Already have an account? <a href="student_login.php">Login here</a></p>
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
    $studentId = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $classId = $_POST['class_id'];
    $sectionId = $_POST['section_id'];
    $qrCode = $studentId; // Use student ID as QR code

    try {
        $stmt = $pdo->prepare("INSERT INTO students (student_id, name, email, password, class_id, section_id, qr_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$studentId, $name, $email, $password, $classId, $sectionId, $qrCode]);
        header('Location: register.php?message=Registration successful. Please wait for admin approval.');
        exit;
    } catch (PDOException $e) {
        header('Location: register.php?message=Registration failed: ' . $e->getMessage());
        exit;
    }
}
?>