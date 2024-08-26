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

// Fetch submissions for the logged-in student
$student_id = $_SESSION['user_id']; // Assuming you store the student ID in the session
$sqlSubmissions = "SELECT s.*, a.title AS assignment_title, CONCAT(st.firstname, ' ', st.lastname) AS student_name 
                    FROM submissions s
                    JOIN assignments a ON s.assignment_id = a.id
                    JOIN students st ON s.student_id = st.id
                    WHERE s.student_id = :student_id";
$stmtSubmissions = $pdo->prepare($sqlSubmissions);
$stmtSubmissions->bindParam(':student_id', $student_id);
$stmtSubmissions->execute();
$submissions = $stmtSubmissions->fetchAll(PDO::FETCH_ASSOC);

// Set page title
$pageTitle = "My Submissions";

ob_start();
?>

<h2>My Submissions</h2>
<?php if (!empty($submissions)): ?>
    <table>
        <tr>
            <th>Assignment Title</th>
            <th>Submitted By</th>
            <th>Submission Date</th>
            <th>File</th>
        </tr>
        <?php foreach ($submissions as $submission): ?>
            <tr>
                <td><?php echo htmlspecialchars($submission['assignment_title'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($submission['student_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($submission['submission_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><a href="../submissions/<?php echo htmlspecialchars($submission['file_path'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">View File</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No submissions found.</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
include 'staff_layout.php';
?>
