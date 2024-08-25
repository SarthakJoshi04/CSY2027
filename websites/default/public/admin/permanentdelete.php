<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Permanent Delete
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM students WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirect back to the archived students page after deletion
    header('Location: archivedstudents.php');
    exit();
} else {
    // If no ID is set, redirect back to the archived students page
    header('Location: archivedstudents.php');
    exit();
}
?>
