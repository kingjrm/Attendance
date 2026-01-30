<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">
    <div class="container">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 017.499 22c-1.39 0-2.733-.11-4.049-.326a.988.988 0 01-.394-.233.996.996 0 01-.195-.293A47.416 47.416 0 013.68 10.5c0-.338.045-.677.138-1.006a4.61 4.61 0 01.289-1.225 1.002 1.002 0 01.886-.585c.566.043 1.093.14 1.612.289a47.562 47.562 0 013.966 1.353c.743.318 1.443.707 2.096 1.159a4.611 4.611 0 011.678 2.148c.147.54.303 1.085.459 1.631a60.585 60.585 0 01.394 3.742c0 .754-.044 1.508-.132 2.257a.988.988 0 01-.309.732.996.996 0 01-.886.383 47.503 47.503 0 01-4.049-.326c-.465-.088-.93-.177-1.394-.266a47.416 47.416 0 01-3.732-.895 1.002 1.002 0 01-.686-.949 60.585 60.585 0 01.491-6.347c.285-1.663.715-3.292 1.278-4.896a4.61 4.61 0 012.148-2.5 4.61 4.61 0 012.5-.459c.74.09 1.476.206 2.21.346a47.562 47.562 0 013.966.777c.744.194 1.478.441 2.195.74a4.611 4.611 0 012.5 2.148c.306.74.585 1.488.835 2.244a60.436 60.436 0 01.491 3.742z" fill="#667eea"/>
            </svg>
        </div>
        <h1>Student Login</h1>
            <form action="login.php" method="post">
                <div class="form-group email">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group lock">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>
            <div class="login-link">
                <p>Don't have an account? <a href="register.php">Sign Up</a></p>
            </div>
            <?php if (isset($_GET['error'])): ?>
                <div class="feedback error" style="display: block; margin-top: 1rem;">Invalid credentials or account not approved.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ? AND status = 'approved'");
    $stmt->execute([$email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_logged_in'] = true;
        $_SESSION['student_id'] = $student['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }
}
?>