<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch module information if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare and execute the query
    $query = "SELECT modules.*, courses.name as course_name FROM modules JOIN courses ON modules.course_id = courses.id WHERE modules.id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Fetch the module data
    $module = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return the module data as JSON
    echo json_encode($module);
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>
