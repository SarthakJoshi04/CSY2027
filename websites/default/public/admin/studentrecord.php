<!-- studentrecord.php -->
<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

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
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM students WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Fetch students
$query = "SELECT * FROM students WHERE is_archived = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <h1>Student Records</h1>
    <a href="addstudent.php" class="button">Add Student</a>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>';

foreach ($students as $row) {
    $content .= '
        <tr>
            <td>' . htmlspecialchars($row['firstname']) . '</td>
            <td>' . htmlspecialchars($row['lastname']) . '</td>
            <td>' . htmlspecialchars($row['email']) . '</td>
            <td>' . htmlspecialchars($row['username']) . '</td>
            <td>
                <a href="editstudent.php?id=' . htmlspecialchars($row['id']) . '">Edit</a>
                <a href="studentrecord.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
                <a href="archive.php?id=' . htmlspecialchars($row['id']) . '">Archive</a>
            </td>
        </tr>';
}

$content .= '
    </table>
    <a href="print.php">Print Records</a> | 
    <a href="archivedstudents.php">View Archived Students</a>';

// Include the admin layout
include 'admin_layout.php';
?>
