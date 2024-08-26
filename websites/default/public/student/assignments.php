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

// Define the target directory for submissions
$submission_dir = "../submissions/";

// Create the directory if it doesn't exist
if (!is_dir($submission_dir)) {
    mkdir($submission_dir, 0755, true);
}

// Handle file submission
if (isset($_POST['submit_assignment'])) {
    $assignment_id = $_POST['assignment_id'];
    $student_id = $_SESSION['user_id']; // Assuming you store the student ID in the session
    $student_name = $_SESSION['username']; // Get student name from session or another source

    if (!empty($_FILES['submission_file']['name'])) {
        $target_file = $submission_dir . basename($_FILES["submission_file"]["name"]);
        if (move_uploaded_file($_FILES["submission_file"]["tmp_name"], $target_file)) {
            $file_path = basename($_FILES["submission_file"]["name"]);
            
            // Save submission details to the database
            $query = "INSERT INTO submissions (assignment_id, student_id, student_name, file_path) VALUES (:assignment_id, :student_id, :student_name, :file_path)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':assignment_id', $assignment_id);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':student_name', $student_name);
            $stmt->bindParam(':file_path', $file_path);
            $stmt->execute();
            
            echo "Assignment submitted successfully.";
        } else {
            echo "File upload failed.";
        }
    }
}

// Fetch assignments
$sqlAssignments = "SELECT * FROM assignments ORDER BY due_date DESC LIMIT 50";
$stmtAssignments = $pdo->prepare($sqlAssignments);
$stmtAssignments->execute();
$assignments = $stmtAssignments->fetchAll(PDO::FETCH_ASSOC);

// Set page title
$pageTitle = "Assignments";

ob_start();
?>

<h2>Assignments</h2>

<!-- Display Assignments -->
<h3>Available Assignments</h3>
<?php if (!empty($assignments)): ?>
    <table>
        <thead>
            <tr>
                <th>Assignment Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>File</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignments as $assignment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($assignment['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($assignment['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($assignment['due_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if (!empty($assignment['file_path'])): ?>
                            <a href="download.php?file=<?php echo urlencode($assignment['file_path']); ?>">Download File</a>
                        <?php else: ?>
                            No File
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="openSubmitDialog(<?php echo htmlspecialchars($assignment['id']); ?>)">Submit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No assignments found.</p>
<?php endif; ?>

<!-- Submit Assignment Dialog -->
<div id="submitAssignmentDialog" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeSubmitDialog()">&times;</span>
        <h1>Submit Assignment</h1>
        <form id="submitAssignmentForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="assignment_id" id="submitAssignmentId">
            <input type="file" name="submission_file" required>
            <button type="submit" name="submit_assignment">Submit Assignment</button>
        </form>
    </div>
</div>

<script>
    var submitModal = document.getElementById("submitAssignmentDialog");
    var closeSubmit = document.getElementsByClassName("close")[0];

    function openSubmitDialog(assignmentId) {
        document.getElementById("submitAssignmentId").value = assignmentId;
        submitModal.style.display = "block";
    }

    function closeSubmitDialog() {
        submitModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == submitModal) {
            submitModal.style.display = "none";
        }
    }
</script>

<?php
$content = ob_get_clean();
include 'student_layout.php';
?>
