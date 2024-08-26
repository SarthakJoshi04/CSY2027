<?php
// Start output buffering
ob_start();

// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Create
if (isset($_POST['create'])) {
    $module_name = $_POST['module_name'];
    $description = $_POST['description'];
    $course_name = $_POST['course_name'];

    // Fetch course_id based on course_name
    $query = "SELECT id FROM courses WHERE course_name = :course_name";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    $course_id = $course['id'];

    $query = "INSERT INTO modules (module_name, description, course_id) VALUES (:module_name, :description, :course_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':module_name', $module_name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $module_name = $_POST['module_name'];
    $description = $_POST['description'];
    $course_name = $_POST['course_name'];

    // Fetch course_id based on course_name
    $query = "SELECT id FROM courses WHERE course_name = :course_name";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    $course_id = $course['id'];

    $query = "UPDATE modules SET module_name = :module_name, description = :description, course_id = :course_id WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':module_name', $module_name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
    
    // Redirect after update
    header('Location: module_record.php');
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM modules WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Redirect after delete
    header('Location: module_record.php');
    exit();
}

// Handle Archive
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];

    // Check if ID exists
    $query = "SELECT COUNT(*) FROM modules WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        echo 'Module not found.';
        exit();
    }

    $query = "UPDATE modules SET is_archived = 1 WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Redirect after archive
    header('Location: module_record.php');
    exit();
}

// Fetch modules with course names
$query = "SELECT m.*, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id WHERE m.is_archived = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch courses for dropdown
$query = "SELECT course_name FROM courses";
$stmt = $conn->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch module details for AJAX request
if (isset($_GET['module_id'])) {
    $id = $_GET['module_id'];
    $query = "SELECT m.*, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id WHERE m.id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $module = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($module);
    exit();
}

// Set the content for this page
$content = '
    <div class="table-container">
        <h1 class="table-title">Modules</h1>
        <table>
            <tr>
                <th>Module Name</th>
                <th>Description</th>
                <th>Course Name</th>
            </tr>';

foreach ($modules as $row) {
    $content .= '
            <tr>
                <td>' . htmlspecialchars($row['module_name']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
                <td>' . htmlspecialchars($row['course_name']) . '</td>
               
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <a href="../admin/printmodule.php" class="button">Print Records</a>
    
        </div>
    </div>

    <!-- Add Module Dialog -->
    <div id="addModuleDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddDialog()">&times;</span>
            <h1>Add New Module</h1>
            <form id="addModuleForm" method="POST">
                <input type="text" name="module_name" placeholder="Module Name" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <select name="course_name" required>
                    <option value="" disabled selected>Select Course</option>';

foreach ($courses as $course) {
    $content .= '<option value="' . htmlspecialchars($course['course_name']) . '">' . htmlspecialchars($course['course_name']) . '</option>';
}




// Include the admin layout
include 'staff_layout.php';

// End output buffering and flush output
ob_end_flush();
?>
