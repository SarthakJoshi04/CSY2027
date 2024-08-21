<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Create
if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $query = "INSERT INTO assignments (assignment_title, description, due_date) VALUES (:title, :description, :due_date)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->execute();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $query = "UPDATE assignments SET assignment_title = :title, description = :description, due_date = :due_date WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->execute();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM assignments WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Fetch assignments
$query = "SELECT * FROM assignments";
$stmt = $conn->prepare($query);
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <h1>Assignments</h1>
    <form method="POST">
        <input type="text" name="title" placeholder="Assignment Title" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="date" name="due_date" required>
        <button type="submit" name="create">Create Assignment</button>
    </form>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Due Date</th>
            <th>Actions</th>
        </tr>';

foreach ($assignments as $row) {
    $content .= '
        <tr>
            <td>' . htmlspecialchars($row['assignment_title']) . '</td>
            <td>' . htmlspecialchars($row['description']) . '</td>
            <td>' . htmlspecialchars($row['due_date']) . '</td>
            <td>
                <a href="assignment.php?edit=' . htmlspecialchars($row['id']) . '">Edit</a>
                <a href="assignment.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
            </td>
        </tr>';
}

$content .= '
    </table>';

// Include the admin layout
include 'admin_layout.php';
?>
