<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch course information if ID is set
$course = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM courses WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
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

    // Refresh the page to show updated records
    header('Location: course_record.php');
    exit();
}

// Handle Create
if (isset($_POST['create'])) {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $query = "INSERT INTO courses (course_name, description) VALUES (:course_name, :description)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Refresh the page to show updated records
    header('Location: course_record.php');
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM courses WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Handle Archive
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];

    $query = "UPDATE courses SET is_archived = 1 WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Refresh the page to show updated records
    header('Location: course_record.php');
    exit();
}

// Fetch courses
$query = "SELECT * FROM courses WHERE is_archived = 0"; // Exclude archived courses
$stmt = $conn->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <div class="table-container">
        <h1 class="table-title">Courses</h1>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Description</th>
            </tr>';

foreach ($courses as $row) {
    $content .= '
            <tr>
                <td>' . htmlspecialchars($row['course_name']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
               
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <a href="../admin/printcourse.php" class="button">Print Records</a>
        </div>
    </div>


    ';

// Include the admin layout
include 'staff_layout.php';
?>
