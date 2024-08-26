<?php
// Start output buffering
ob_start();

// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch archived modules
$query = "SELECT m.*, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id WHERE m.is_archived = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$archivedModules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <div class="table-container">
        <h1 class="table-title">Archived Modules</h1>
        <table>
            <tr>
                <th>Module Name</th>
                <th>Description</th>
                <th>Course Name</th>
                <th>Actions</th>
            </tr>';

foreach ($archivedModules as $row) {
    $content .= '
            <tr>
                <td>' . htmlspecialchars($row['module_name']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
                <td>' . htmlspecialchars($row['course_name']) . '</td>
                <td>
                    <a href="restore.php?id=' . htmlspecialchars($row['id']) . '&type=module" class="button">Restore</a>
                    <a href="permanentdelete.php?id=' . htmlspecialchars($row['id']) . '" class="button">Delete Permanently</a>
                </td>
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <a href="module_record.php" class="button">Back to Modules</a>
        </div>
    </div>';

// Include the admin layout
include 'admin_layout.php';

// End output buffering and flush output
ob_end_flush();
?>
