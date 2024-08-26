<?php
// Include the database connection
require_once '../dbconnection.php';

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started
}

// Check if the user is logged in and is a student
if (!isset($_SESSION['username'])) {
    header('Location: student_login.php'); // Redirect to login page if not logged in
    exit();
}

// Create a database connection
$db = new DatabaseConnection();
$pdo = $db->getConnection();

// Fetch student information
$username = $_SESSION['username'];
$sql = "SELECT * FROM students WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if student data was retrieved
if (!$student) {
    die("Student data not found.");
}

// Set page title
$pageTitle = "Calendar";

// Start output buffering
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <main>
        <h2>Calendar</h2>
        <iframe src="https://calendar.google.com/calendar/embed?src=your_calendar_id&ctz=Your/Timezone" 
                style="border: 0" width="800" height="500" frameborder="0" scrolling="no"></iframe>
    </main>
</body>
</html>

<?php
// Capture the output
$content = ob_get_clean();

// Include the layout file
include 'student_layout.php';

?>
