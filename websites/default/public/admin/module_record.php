<!-- module_record.php -->

<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Create
if (isset($_POST['create'])) {
    $module_name = $_POST['module_name'];
    $description = $_POST['description'];
    $course_id = $_POST['course_id'];
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
    $course_id = $_POST['course_id'];
    $query = "UPDATE modules SET module_name = :module_name, description = :description, course_id = :course_id WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':module_name', $module_name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM modules WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Fetch modules
$query = "SELECT * FROM modules";
$stmt = $conn->prepare($query);
$stmt->execute();
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <h1>Modules</h1>
    <form method="POST">
        <input type="text" name="module_name" placeholder="Module Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="course_id" placeholder="Course ID" required>
        <button type="submit" name="create">Add Module</button>
    </form>
    <table>
        <tr>
            <th>Module Name</th>
            <th>Description</th>
            <th>Course ID</th>
            <th>Actions</th>
        </tr>';

foreach ($modules as $row) {
    $content .= '
        <tr>
            <td>' . htmlspecialchars($row['module_name']) . '</td>
            <td>' . htmlspecialchars($row['description']) . '</td>
            <td>' . htmlspecialchars($row['course_id']) . '</td>
            <td>
                <a href="module_record.php?edit=' . htmlspecialchars($row['id']) . '">Edit</a>
                <a href="module_record.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
            </td>
        </tr>';
}

$content .= '
    </table>';

// Include the admin layout
include 'admin_layout.php';
?>
