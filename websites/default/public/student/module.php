<?php
// Include the database connection
require_once '../dbconnection.php';

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started
}

// Check if the user is logged in and is a student
if (!isset($_SESSION['username'])) {
    header('Location: student_login.php'); // Redirect to login page if not logged in
    exit();
}

// Create a database connection
$db = new DatabaseConnection();
$pdo = $db->getConnection();

// Fetch student information
$username = $_SESSION['username'];
$sql = "SELECT * FROM students WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if student data was retrieved
if (!$student) {
    die("Student data not found.");
}

// Fetch courses and modules related to the student
$sqlCourses = "SELECT c.* FROM courses c 
                INNER JOIN students s ON c.id = s.course_id 
                WHERE s.username = :username";
$stmtCourses = $pdo->prepare($sqlCourses);
$stmtCourses->execute(['username' => $username]);
$courses = $stmtCourses->fetchAll(PDO::FETCH_ASSOC);

$sqlModules = "SELECT m.* FROM modules m 
                INNER JOIN students s ON m.id = s.module_id 
                WHERE s.username = :username";
$stmtModules = $pdo->prepare($sqlModules);
$stmtModules->execute(['username' => $username]);
$modules = $stmtModules->fetchAll(PDO::FETCH_ASSOC);

// Set page title
$pageTitle = "My Courses";

ob_start();
?>
<!-- Include student_layout.php -->
<?php include 'student_layout.php'; ?>

<div class="main-content">
    <header class="student-header">
        <h1>My Modules</h1>
    </header>

    <main>
        

        <h2>Modules</h2>
        <?php if (!empty($modules)): ?>
            <ul>
                <?php foreach ($modules as $module): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($module['module_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($module['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No modules found.</p>
        <?php endif; ?>
    </main>
</div>

<?php
$content = ob_get_clean();
?>

<!-- Output the content from the buffer -->
<?php echo $content; ?>
