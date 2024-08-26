<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch course information if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare and execute the query
    $query = "SELECT * FROM courses WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Fetch the course data
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return the course data as JSON
    if ($course) {
        echo json_encode($course);
    } else {
        echo json_encode(['error' => 'Course not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>
