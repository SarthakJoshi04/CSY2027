<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch student information
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM students WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($student);
} else {
    echo json_encode([]);
}
?>
