<!-- course_record.php -->

<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Create
if (isset($_POST['create'])) {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $query = "INSERT INTO courses (course_name, description) VALUES (:course_name, :description)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $query = "UPDATE courses SET course_name = :course_name, description = :description WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM courses WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Fetch courses
$query = "SELECT * FROM courses";
$stmt = $conn->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <h1>Courses</h1>
    <form method="POST">
        <input type="text" name="course_name" placeholder="Course Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit" name="create">Add Course</button>
    </form>
    <table>
        <tr>
            <th>Course Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>';

foreach ($courses as $row) {
    $content .= '
        <tr>
            <td>' . htmlspecialchars($row['course_name']) . '</td>
            <td>' . htmlspecialchars($row['description']) . '</td>
            <td>
                <a href="course_record.php?edit=' . htmlspecialchars($row['id']) . '">Edit</a>
                <a href="course_record.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
            </td>
        </tr>';
}

$content .= '
    </table>';

// Include the admin layout
include 'admin_layout.php';
?>
