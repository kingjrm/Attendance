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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="students.php">Students</a></li>
                <li><a href="classes.php" class="active">Classes & Sections</a></li>
                <li><a href="attendance.php">Attendance Logs</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
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