<?php
require_once '../includes/auth.php';
require_once '../config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_class'])) {
        $name = $_POST['class_name'];
        $stmt = $pdo->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->execute([$name]);
        $message = 'Class added successfully.';
    } elseif (isset($_POST['add_section'])) {
        $name = $_POST['section_name'];
        $classId = $_POST['class_id'];
        $stmt = $pdo->prepare("INSERT INTO sections (name, class_id) VALUES (?, ?)");
        $stmt->execute([$name, $classId]);
        $message = 'Section added successfully.';
    } elseif (isset($_POST['delete_class'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Class deleted successfully.';
    } elseif (isset($_POST['delete_section'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM sections WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Section deleted successfully.';
    }
}

// Get classes and sections
$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
$sections = $pdo->query("SELECT s.*, c.name as class_name FROM sections s JOIN classes c ON s.class_id = c.id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes & Sections</title>
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
                <li><a href="students.php">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Students
                </a></li>
                <li><a href="classes.php" class="active">
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
            <h1>Manage Classes & Sections</h1>
            <?php if ($message): ?>
                <div class="feedback success" style="display: block;"><?php echo $message; ?></div>
            <?php endif; ?>
            <div style="display: flex; gap: 2rem;">
                <div style="flex: 1;">
                    <h2>Add Class</h2>
                    <form method="post">
                        <div class="form-group">
                            <label for="class_name">Class Name:</label>
                            <input type="text" id="class_name" name="class_name" required>
                        </div>
                        <button type="submit" name="add_class" class="btn">Add Class</button>
                    </form>
                </div>
                <div style="flex: 1;">
                    <h2>Add Section</h2>
                    <form method="post">
                        <div class="form-group">
                            <label for="section_name">Section Name:</label>
                            <input type="text" id="section_name" name="section_name" required>
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
                        <button type="submit" name="add_section" class="btn">Add Section</button>
                    </form>
                </div>
            </div>
            <h2>Classes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?php echo $class['id']; ?></td>
                            <td><?php echo $class['name']; ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $class['id']; ?>">
                                    <button type="submit" name="delete_class" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Sections</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sections as $section): ?>
                        <tr>
                            <td><?php echo $section['id']; ?></td>
                            <td><?php echo $section['name']; ?></td>
                            <td><?php echo $section['class_name']; ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $section['id']; ?>">
                                    <button type="submit" name="delete_section" class="btn btn-danger">Delete</button>
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