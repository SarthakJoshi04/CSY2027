<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Check if id and type are set
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    try {
        if ($type == 'staff') {
            // Update is_archived to 0 for staff
            $query = "UPDATE staff SET is_archived = 0 WHERE id = :id";
        } elseif ($type == 'student') {
            // Update is_archived to 0 for students
            $query = "UPDATE students SET is_archived = 0 WHERE id = :id";
        } elseif ($type == 'course') {
            // Update is_archived to 0 for courses
            $query = "UPDATE courses SET is_archived = 0 WHERE id = :id";
        } elseif ($type == 'module') {
            // Update is_archived to 0 for modules
            $query = "UPDATE modules SET is_archived = 0 WHERE id = :id";
        } else {
            throw new Exception('Invalid type parameter.');
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Redirect after restore
        header('Location: archived' . ($type == 'staff' ? 'staff.php' : ($type == 'student' ? 'students.php' : ($type == 'course' ? 'courses.php' : 'modules.php'))));
        exit();
    } catch (PDOException $e) {
        // Handle SQL errors
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    } catch (Exception $e) {
        // Handle other errors
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    }
} else {
    echo 'Invalid request.';
}
?>
