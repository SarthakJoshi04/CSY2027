<?php
// Start output buffering
ob_start();

// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch archived courses
$query = "SELECT * FROM courses WHERE is_archived = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$archivedCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <div class="table-container">
        <h1 class="table-title">Archived Courses</h1>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>';

foreach ($archivedCourses as $row) {
    $content .= '
            <tr>
                <td>' . htmlspecialchars($row['course_name']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
                <td>
                    <a class="button" href="restore.php?id=' . htmlspecialchars($row['id']) . '&type=course" class="button">Restore</a>
                    <a class="button" href="permanentdelete.php?id=' . htmlspecialchars($row['id']) . '&type=course">Delete Permanently</a>
                </td>
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <a href="course_record.php" class="button">Back to Courses</a>
        </div>
    </div>';

// Include the admin layout
include 'admin_layout.php';

// End output buffering and flush output
ob_end_flush();
?>
