<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Archive
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "UPDATE students SET is_archived = 1 WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirect back to the student records page after archiving
    header('Location: studentrecord.php');
    exit();
} else {
    // If no ID is set, redirect back to the student records page
    header('Location: studentrecord.php');
    exit();
}
?>
