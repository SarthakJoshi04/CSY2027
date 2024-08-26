<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM assignments WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($assignment) {
        echo json_encode($assignment);
    } else {
        echo json_encode(['error' => 'Assignment not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
