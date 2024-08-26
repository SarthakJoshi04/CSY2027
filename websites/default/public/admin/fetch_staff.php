<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch staff information if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the query
    $query = "SELECT * FROM staff WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the result
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the result as JSON
    if ($staff) {
        header('Content-Type: application/json');
        echo json_encode($staff);
    } else {
        echo json_encode(['error' => 'Staff member not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>
