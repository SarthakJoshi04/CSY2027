<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch archived staff members
try {
    $query = "SELECT * FROM staff WHERE is_archived = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $archivedStaff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() === 0) {
        // If no archived staff are found
        $content = '<h1 class="table-title">Archived Staff</h1><p>No archived staff records found.</p>';
    } else {
        // Set the content for this page
        $content = '
            <div class="table-container">
                <h1 class="table-title">Archived Staff</h1>
                <table id="staffTable">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>';

        foreach ($archivedStaff as $row) {
            $content .= '
                <tr>
                    <td>' . htmlspecialchars($row['firstname']) . '</td>
                    <td>' . htmlspecialchars($row['lastname']) . '</td>
                    <td>' . htmlspecialchars($row['email']) . '</td>
                    <td>' . htmlspecialchars($row['username']) . '</td>
                    <td>
                        <a href="restore.php?id=' . htmlspecialchars($row['id']) . '&type=staff" class="button">Restore</a>
                        <a href="permanentdelete.php?id=' . htmlspecialchars($row['id']) . '&type=staff" class="button">Delete Permanently</a>
                    </td>
                </tr>';
        }

        $content .= '
                </table>
                <div class="button-group">
                    <a href="staffrecord.php" class="button">Back to Staff Records</a>
                </div>
            </div>';
    }
} catch (PDOException $e) {
    // Handle SQL errors
    $content = '<h1 class="table-title">Error</h1><p>An error occurred while fetching archived staff records: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

// Include the admin layout
include 'admin_layout.php';
?>
