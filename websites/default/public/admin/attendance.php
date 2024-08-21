<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Create
if (isset($_POST['create'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $query = "INSERT INTO attendance (student_id, course_id, date, status) VALUES (:student_id, :course_id, :date, :status)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $query = "UPDATE attendance SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM attendance WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Fetch attendance
$query = "SELECT * FROM attendance";
$stmt = $conn->prepare($query);
$stmt->execute();
$attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="../stylee.css">
</head>
<body>
    <h1>Attendance</h1>
    <form method="POST">
        <input type="number" name="student_id" placeholder="Student ID" required>
        <input type="number" name="course_id" placeholder="Course ID" required>
        <input type="date" name="date" required>
        <select name="status" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
        </select>
        <button type="submit" name="create">Record Attendance</button>
    </form>
    <table>
        <tr>
            <th>Student ID</th>
            <th>Course ID</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($attendances as $row) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
            <td><?php echo htmlspecialchars($row['course_id']); ?></td>
            <td><?php echo htmlspecialchars($row['date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <a href="attendance.php?edit=<?php echo htmlspecialchars($row['id']); ?>">Edit</a>
                <a href="attendance.php?delete=<?php echo htmlspecialchars($row['id']); ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
