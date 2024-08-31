<?php

// Start output buffering
ob_start();

// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch available courses
$queryCourses = "SELECT * FROM courses WHERE is_archived = 0";
$stmtCourses = $conn->prepare($queryCourses);
$stmtCourses->execute();
$courses = $stmtCourses->fetchAll(PDO::FETCH_ASSOC);

// Fetch available modules
$queryModules = "SELECT * FROM modules WHERE is_archived = 0";
$stmtModules = $conn->prepare($queryModules);
$stmtModules->execute();
$modules = $stmtModules->fetchAll(PDO::FETCH_ASSOC);

// Handle Create
if (isset($_POST['create'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $course_id = $_POST['course_id'];
    $module_id = $_POST['module_id'];

    $query = "INSERT INTO students (firstname, lastname, email, username, password, course_id, module_id) VALUES (:firstname, :lastname, :email, :username, :password, :course_id, :module_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->execute();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $course_id = $_POST['course_id'];
    $module_id = $_POST['module_id'];

    $query = "UPDATE students SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, password = :password, course_id = :course_id, module_id = :module_id WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->execute();
    
    // Redirect after update
    header('Location: studentrecord.php');
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM students WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Redirect after delete
    header('Location: studentrecord.php');
    exit();
}

// Fetch students
$query = "SELECT students.*, courses.course_name, modules.module_name FROM students 
          LEFT JOIN courses ON students.course_id = courses.id 
          LEFT JOIN modules ON students.module_id = modules.id 
          WHERE students.is_archived = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch student details for AJAX request
if (isset($_GET['student_id'])) {
    $id = $_GET['student_id'];
    $query = "SELECT * FROM students WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($student);
    exit();
}

// Set the content for this page
$content = '
    <div class="table-container">
        <h1 class="table-title">Student Records</h1>
        <table id="studentTable">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Course</th>
                <th>Module</th>
                <th>Actions</th>
            </tr>';

foreach ($students as $row) {
    $content .= '
            <tr>
                <td>' . htmlspecialchars($row['firstname']) . '</td>
                <td>' . htmlspecialchars($row['lastname']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['username']) . '</td>
                <td>' . htmlspecialchars($row['course_name']) . '</td>
                <td>' . htmlspecialchars($row['module_name']) . '</td>
                <td>
                    <button onclick="openEditDialog(' . htmlspecialchars($row['id']) . ')">Edit</button>
                    <a href="studentrecord.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
                    <a href="archive.php?id=' . htmlspecialchars($row['id']) . '">Archive</a>
                </td>
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <button id="addStudentBtn" class="button">Add Student</button>
            <a href="archivedstudents.php" class="button">View Archived Students</a>
            <a href="print.php" class="button">Print Records</a>
        </div>
    </div>

    <!-- Add Student Dialog -->
    <div id="addStudentDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddDialog()">&times;</span>
            <h1>Add New Student</h1>
            <form id="addStudentForm" method="POST">
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="course_id" required>
                    <option value="">Select Course</option>';

foreach ($courses as $course) {
    $content .= '<option value="' . htmlspecialchars($course['id']) . '">' . htmlspecialchars($course['course_name']) . '</option>';
}

$content .= '
                </select>
                <select name="module_id" required>
                    <option value="">Select Module</option>';

foreach ($modules as $module) {
    $content .= '<option value="' . htmlspecialchars($module['id']) . '">' . htmlspecialchars($module['module_name']) . '</option>';
}

$content .= '
                </select>
                <button type="submit" name="create">Add Student</button>
            </form>
        </div>
    </div>

    <!-- Edit Student Dialog -->
    <div id="editStudentDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditDialog()">&times;</span>
            <h1>Edit Student</h1>
            <form id="editStudentForm" method="POST">
                <input type="hidden" name="id" id="editStudentId">
                <input type="text" name="firstname" id="editFirstName" placeholder="First Name" required>
                <input type="text" name="lastname" id="editLastName" placeholder="Last Name" required>
                <input type="email" name="email" id="editEmail" placeholder="Email" required>
                <input type="text" name="username" id="editUsername" placeholder="Username" required>
                <select name="course_id" id="editCourseId" required>
                    <option value="">Select Course</option>';

foreach ($courses as $course) {
    $content .= '<option value="' . htmlspecialchars($course['id']) . '">' . htmlspecialchars($course['course_name']) . '</option>';
}

$content .= '
                </select>
                <select name="module_id" id="editModuleId" required>
                    <option value="">Select Module</option>';

foreach ($modules as $module) {
    $content .= '<option value="' . htmlspecialchars($module['id']) . '">' . htmlspecialchars($module['module_name']) . '</option>';
}

$content .= '
                </select>
                <button type="submit" name="update">Update Student</button>
            </form>
        </div>
    </div>
';

include 'admin_layout.php';
?>

<script>
document.getElementById('addStudentBtn').addEventListener('click', openAddDialog);

function openAddDialog() {
    document.getElementById('addStudentDialog').style.display = 'block';
}

function closeAddDialog() {
    document.getElementById('addStudentDialog').style.display = 'none';
}

function openEditDialog(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'studentrecord.php?student_id=' + id, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var student = JSON.parse(xhr.responseText);
            document.getElementById('editStudentId').value = student.id;
            document.getElementById('editFirstName').value = student.firstname;
            document.getElementById('editLastName').value = student.lastname;
            document.getElementById('editEmail').value = student.email;
            document.getElementById('editUsername').value = student.username;
            document.getElementById('editCourseId').value = student.course_id;
            document.getElementById('editModuleId').value = student.module_id;
            document.getElementById('editStudentDialog').style.display = 'block';
        }
    };
    xhr.send();
}

function closeEditDialog() {
    document.getElementById('editStudentDialog').style.display = 'none';
}
</script>

