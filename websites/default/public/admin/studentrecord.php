<?php
// Start output buffering
ob_start();

// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Handle Create
if (isset($_POST['create'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO students (firstname, lastname, email, username, password) VALUES (:firstname, :lastname, :email, :username, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
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

    $query = "UPDATE students SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, password = :password WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
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
$query = "SELECT * FROM students WHERE is_archived = 0";
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
                    <button class="button" onclick="openEditDialog(' . htmlspecialchars($row['id']) . ')">Edit</button>
                    <a class="button" href="studentrecord.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
                    <a class="button" href="archive.php?id=' . htmlspecialchars($row['id']) . '">Archive</a>
                </td>
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <button id="addStudentBtn" class="button">Add Student</button>
            <a href="archivedstudents.php" class="button">View Archived Students</a>
            <a href="print.php" class="button">Print Students</a>
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
                <input type="password" name="password" id="editPassword" placeholder="Password" required>
                <button type="submit" name="update">Update Student</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        var addModal = document.getElementById("addStudentDialog");
        var editModal = document.getElementById("editStudentDialog");
        var addBtn = document.getElementById("addStudentBtn");
        var closeAdd = document.getElementsByClassName("close")[0];
        var closeEdit = document.getElementsByClassName("close")[1];

        addBtn.onclick = function() {
            addModal.style.display = "block";
        }

        closeAdd.onclick = function() {
            addModal.style.display = "none";
        }

        closeEdit.onclick = function() {
            editModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        }

        function openEditDialog(id) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "studentrecord.php?student_id=" + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var student = JSON.parse(xhr.responseText);
                    if (student.error) {
                        alert(student.error);
                        return;
                    }
                    document.getElementById("editStudentId").value = student.id;
                    document.getElementById("editFirstName").value = student.firstname;
                    document.getElementById("editLastName").value = student.lastname;
                    document.getElementById("editEmail").value = student.email;
                    document.getElementById("editUsername").value = student.username;
                    document.getElementById("editPassword").value = student.password;
                    editModal.style.display = "block";
                }
            };
            xhr.send();
        }
    </script>
';

// Include the admin layout
include 'admin_layout.php';
?>
