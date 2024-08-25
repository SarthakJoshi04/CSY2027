<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Restore
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "UPDATE students SET is_archived = 0 WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirect back to the archived students page after restoring
    header('Location: archivedstudents.php');
    exit();
} else {
    // If no ID is set, redirect back to the archived students page
    header('Location: archivedstudents.php');
    exit();
}
?>
