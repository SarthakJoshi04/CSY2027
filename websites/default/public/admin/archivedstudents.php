<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch archived students
$query = "SELECT * FROM students WHERE is_archived = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$archivedStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <h1>Archived Students</h1>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>';

foreach ($archivedStudents as $row) {
    $content .= '
        <tr>
            <td>' . htmlspecialchars($row['firstname']) . '</td>
            <td>' . htmlspecialchars($row['lastname']) . '</td>
            <td>' . htmlspecialchars($row['email']) . '</td>
            <td>' . htmlspecialchars($row['username']) . '</td>
            <td>
                <a href="restore.php?id=' . htmlspecialchars($row['id']) . '">Restore</a>
                <a href="permanentdelete.php?id=' . htmlspecialchars($row['id']) . '">Delete Permanently</a>
            </td>
        </tr>';
}

$content .= '</table>';

// Include the admin layout
include 'admin_layout.php';
?>
