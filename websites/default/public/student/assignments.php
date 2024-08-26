<?php
// Include the database connection
require_once '../dbconnection.php';

// Start the session
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['username'])) {
    header('Location: student_login.php'); // Redirect to login page if not logged in
    exit();
}

// Create a database connection
$db = new DatabaseConnection();
$pdo = $db->getConnection();

// Fetch assignments
$sqlAssignments = "SELECT * FROM assignments ORDER BY file_path DESC LIMIT 50";
$stmtAssignments = $pdo->prepare($sqlAssignments);
$stmtAssignments->execute();
$assignments = $stmtAssignments->fetchAll(PDO::FETCH_ASSOC);

// Set page title
$pageTitle = "Assignments";

ob_start();
?>
<!-- Include student_layout.php -->
<?php include 'student_layout.php'; ?>

<div class="main-content">
    <header class="student-header">
        <h1>Assignments</h1>
    </header>

    <main>
        <h2>Assignments</h2>
        <?php if (!empty($assignments)): ?>
            <ul>
                <?php foreach ($assignments as $assignment): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($assignment['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($assignment['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Due Date: <?php echo htmlspecialchars($assignment['due_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php if (!empty($assignment['file_path'])): ?>
                            <a href="download.php?file=<?php echo urlencode($assignment['file_path']); ?>">Download File</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No assignments found.</p>
        <?php endif; ?>
    </main>
</div>

<?php
$content = ob_get_clean();
?>

<!-- Output the content from the buffer -->
<?php echo $content; ?>
