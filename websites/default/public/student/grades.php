<?php
include '../dbconnection.php';
include 'student_layout.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

$student_id = $_SESSION['student_id'];

$db = new DatabaseConnection();
$conn = $db->getConnection();

$sql = "SELECT courses.course_name, grades.grade, grades.date_assigned 
        FROM grades 
        JOIN courses ON grades.course_id = courses.id 
        WHERE grades.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <h2>Grades</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['course_name']); ?></strong>
                - Grade: <?php echo htmlspecialchars($row['grade']); ?>
                - Date Assigned: <?php echo htmlspecialchars($row['date_assigned']); ?>
            </li>
        <?php endwhile; ?>
    </ul>
</main>

<?php
$stmt->close();
$conn->close();
include 'footer.php';
?>
