<?php
// Include the database connection
require_once '../dbconnection.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create a database connection
$db = new DatabaseConnection();
$pdo = $db->getConnection();

// Fetch all submissions
$sqlSubmissions = "SELECT s.*, a.title AS assignment_title, CONCAT(st.firstname, ' ', st.lastname) AS student_name 
                    FROM submissions s
                    JOIN assignments a ON s.assignment_id = a.id
                    JOIN students st ON s.student_id = st.id";
$stmtSubmissions = $pdo->prepare($sqlSubmissions);
$stmtSubmissions->execute();
$submissions = $stmtSubmissions->fetchAll(PDO::FETCH_ASSOC);

// Set page title
$pageTitle = "All Submissions";

ob_start();
?>

<h2>All Submissions</h2>
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
