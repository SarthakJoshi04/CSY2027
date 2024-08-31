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
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $course_id = $_POST['course_id'];
    $module_id = $_POST['module_id'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $parents = $_POST['parents'];

    $query = "INSERT INTO students (firstname, lastname, email, username, password, course_id, module_id, date_of_birth, gender, contact, address, parents) 
              VALUES (:firstname, :lastname, :email, :username, :password, :course_id, :module_id, :date_of_birth, :gender, :contact, :address, :parents)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':parents', $parents);
    $stmt->execute();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $course_id = $_POST['course_id'];
    $module_id = $_POST['module_id'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $parents = $_POST['parents'];

    $query = "UPDATE students SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, 
              course_id = :course_id, module_id = :module_id, date_of_birth = :date_of_birth, gender = :gender, 
              contact = :contact, address = :address, parents = :parents";

    // Only update the password if it was provided
    if ($password) {
        $query .= ", password = :password";
    }

    $query .= " WHERE id = :id";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    if ($password) {
        $stmt->bindParam(':password', $password);
    }
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':module_id', $module_id);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':parents', $parents);
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
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Parents</th>
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
                <td>' . htmlspecialchars($row['date_of_birth']) . '</td>
                <td>' . htmlspecialchars($row['gender']) . '</td>
                <td>' . htmlspecialchars($row['contact']) . '</td>
                <td>' . htmlspecialchars($row['address']) . '</td>
                <td>' . htmlspecialchars($row['parents']) . '</td>
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
            <!-- Add/Edit Student Form -->
            <tr>
                <td colspan="12">
                    <div id="studentFormDialog" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeFormDialog()">&times;</span>
                            <h1 id="formTitle">Add New Student</h1>
                            <form id="studentForm" method="POST">
                                <input type="hidden" name="id" id="studentId">
                                <input type="text" name="firstname" id="firstname" placeholder="First Name" required>
                                <input type="text" name="lastname" id="lastname" placeholder="Last Name" required>
                                <input type="email" name="email" id="email" placeholder="Email" required>
                                <input type="text" name="username" id="username" placeholder="Username" required>
                                <input type="password" name="password" id="password" placeholder="Password (optional)">
                                <input type="date" name="date_of_birth" id="date_of_birth" placeholder="Date of Birth" required>
                                <select name="gender" id="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <input type="text" name="contact" id="contact" placeholder="Contact" required>
                                <textarea name="address" id="address" placeholder="Address" required></textarea>
                                <textarea name="parents" id="parents" placeholder="Parents Information" required></textarea>
                                <select name="course_id" id="course_id" required>
                                    <option value="">Select Course</option>';
foreach ($courses as $course) {
    $content .= '<option value="' . htmlspecialchars($course['id']) . '">' . htmlspecialchars($course['course_name']) . '</option>';
}
$content .= '
                                </select>
                                <select name="module_id" id="module_id" required>
                                    <option value="">Select Module</option>';
foreach ($modules as $module) {
    $content .= '<option value="' . htmlspecialchars($module['id']) . '">' . htmlspecialchars($module['module_name']) . '</option>';
}
$content .= '
                                </select>
                                <button type="submit" name="create" id="createBtn">Create</button>
                                <button type="submit" name="update" id="updateBtn">Update</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="button-group">
            <button onclick="openFormDialog()" class="button">Add Student</button>
            <a href="print.php" class="button">Print Records</a>
            <a href="archivedstudents.php" class="button">View Archived Staff</a>
        </div>
    </div>
    <script>
        // JavaScript for handling the form dialog
        function openFormDialog() {
            document.getElementById("studentFormDialog").style.display = "block";
            document.getElementById("studentForm").reset();
            document.getElementById("formTitle").innerText = "Add New Student";
            document.getElementById("createBtn").style.display = "inline-block";
            document.getElementById("updateBtn").style.display = "none";
        }

        function openEditDialog(id) {
            fetch("studentrecord.php?student_id=" + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("studentId").value = data.id;
                    document.getElementById("firstname").value = data.firstname;
                    document.getElementById("lastname").value = data.lastname;
                    document.getElementById("email").value = data.email;
                    document.getElementById("username").value = data.username;
                    document.getElementById("date_of_birth").value = data.date_of_birth;
                    document.getElementById("gender").value = data.gender;
                    document.getElementById("contact").value = data.contact;
                    document.getElementById("address").value = data.address;
                    document.getElementById("parents").value = data.parents;
                    document.getElementById("course_id").value = data.course_id;
                    document.getElementById("module_id").value = data.module_id;

                    document.getElementById("formTitle").innerText = "Edit Student";
                    document.getElementById("createBtn").style.display = "none";
                    document.getElementById("updateBtn").style.display = "inline-block";

                    document.getElementById("studentFormDialog").style.display = "block";
                });
        }

        function closeFormDialog() {
            document.getElementById("studentFormDialog").style.display = "none";
        }
    </script>
';

// Include layout
include 'admin_layout.php';
?>
