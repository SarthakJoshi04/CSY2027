<!-- attendance.php -->
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

// Set the content for this page
$content = '
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
        </tr>';

foreach ($attendances as $row) {
    $content .= '
        <tr>
            <td>' . htmlspecialchars($row['student_id']) . '</td>
            <td>' . htmlspecialchars($row['course_id']) . '</td>
            <td>' . htmlspecialchars($row['date']) . '</td>
            <td>' . htmlspecialchars($row['status']) . '</td>
            <td>
                <a href="attendance.php?edit=' . htmlspecialchars($row['id']) . '">Edit</a>
                <a href="attendance.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
            </td>
        </tr>';
}

$content .= '
    </table>';

// Include the admin layout
include 'admin_layout.php';
?>
