<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch student information if ID is set
$student = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM students WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "UPDATE students SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, password = :password WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Redirect back to the student records page after updating
    header('Location: studentrecord.php');
    exit();
}

// Set the content for this page
$content = '
    <h1>Edit Student</h1>';

if ($student) {
    $content .= '
    <form method="POST">
        <input type="hidden" name="id" value="' . htmlspecialchars($student['id']) . '">
        <input type="text" name="firstname" value="' . htmlspecialchars($student['firstname']) . '" placeholder="First Name" required>
        <input type="text" name="lastname" value="' . htmlspecialchars($student['lastname']) . '" placeholder="Last Name" required>
        <input type="email" name="email" value="' . htmlspecialchars($student['email']) . '" placeholder="Email" required>
        <input type="text" name="username" value="' . htmlspecialchars($student['username']) . '" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="update">Update Student</button>
    </form>';
} else {
    $content .= '<p>Student not found.</p>';
}

$content .= '
    <a href="studentrecord.php">Back to Student Records</a>';

// Include the admin layout
include 'admin_layout.php';
?>
