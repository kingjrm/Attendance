<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="container">
        <h1>Student Attendance</h1>
        <div class="attendance-form">
            <div class="qr-scanner">
                <div id="preview"></div>
            </div>
            <div class="manual-entry">
                <label for="student-id">Or Enter Student ID:</label>
                <input type="text" id="student-id" placeholder="e.g., 22-32139">
                <button id="submit-manual">Submit</button>
            </div>
        </div>
        <div id="feedback" class="feedback"></div>
    </div>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="assets/js/attendance.js"></script>
</body>
</html>