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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <link rel="stylesheet" href="../stylee.css">
</head>
<body>
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
        </tr>
        <?php foreach ($assignments as $row) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['assignment_title']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['due_date']); ?></td>
            <td>
                <a href="assignment.php?edit=<?php echo htmlspecialchars($row['id']); ?>">Edit</a>
                <a href="assignment.php?delete=<?php echo htmlspecialchars($row['id']); ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
