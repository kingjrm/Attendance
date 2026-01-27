<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Student Login</h1>
        <form action="student_login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="login-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p><a href="index.php">Back to Attendance</a></p>
        </div>
        <?php if (isset($_GET['error'])): ?>
            <div class="feedback error" style="display: block; margin-top: 1rem;">Invalid credentials or account not approved.</div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
session_start();
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ? AND status = 'approved'");
    $stmt->execute([$email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_logged_in'] = true;
        $_SESSION['student_id'] = $student['id'];
        header('Location: student_dashboard.php');
        exit;
    } else {
        header('Location: student_login.php?error=1');
        exit;
    }
}
?>