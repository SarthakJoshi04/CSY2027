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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO staff (firstname, lastname, email, username, password) VALUES (:firstname, :lastname, :email, :username, :password)";
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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "UPDATE staff SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, password = :password WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    // Redirect after update
    header('Location: staffrecord.php');
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM staff WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Redirect after delete
    header('Location: staffrecord.php');
    exit();
}

// Handle Archive
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];

    $query = "UPDATE staff SET is_archived = 1 WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Redirect after archiving
    header('Location: staffrecord.php');
    exit();
}

// Fetch staff records
$query = "SELECT * FROM staff WHERE is_archived = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch staff details for AJAX request
if (isset($_GET['staff_id'])) {
    $id = $_GET['staff_id'];
    $query = "SELECT * FROM staff WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($staff);
    exit();
}

// Set the content for this page
$content = <<<HTML
    <div class="table-container">
        <h1 class="table-title">Staff</h1>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
HTML;

foreach ($staffs as $row) {
    $content .= <<<HTML
            <tr>
                <td>{$row['firstname']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['email']}</td>
                <td>{$row['username']}</td>
                <td>
                    <button class="button" onclick="openEditDialog({$row['id']})">Edit</button>
                    <a class="button" href="staffrecord.php?delete={$row['id']}">Delete</a>
                    <a class="button" href="staffrecord.php?archive={$row['id']}">Archive</a>
                </td>
            </tr>
HTML;
}

$content .= <<<HTML
        </table>
        <div class="button-group">
            <button id="addStaffBtn" class="button">Add Staff</button>
            <a href="printstaff.php" class="button">Print Records</a>
            <a href="archivedstaff.php" class="button">View Archived Staff</a>
        </div>
    </div>

    <!-- Add Staff Dialog -->
    <div id="addStaffDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddDialog()">&times;</span>
            <h1>Add New Staff</h1>
            <form id="addStaffForm" method="POST">
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="create">Add Staff</button>
            </form>
        </div>
    </div>

    <!-- Edit Staff Dialog -->
    <div id="editStaffDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditDialog()">&times;</span>
            <h1>Edit Staff</h1>
            <form id="editStaffForm" method="POST">
                <input type="hidden" name="id" id="editStaffId">
                <input type="text" name="firstname" id="editFirstName" placeholder="First Name" required>
                <input type="text" name="lastname" id="editLastName" placeholder="Last Name" required>
                <input type="email" name="email" id="editEmail" placeholder="Email" required>
                <input type="text" name="username" id="editUsername" placeholder="Username" required>
                <input type="password" name="password" id="editPassword" placeholder="Password" required>
                <button type="submit" name="update">Update Staff</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        var addModal = document.getElementById("addStaffDialog");
        var editModal = document.getElementById("editStaffDialog");
        var addBtn = document.getElementById("addStaffBtn");
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
            xhr.open("GET", "staffrecord.php?staff_id=" + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var staff = JSON.parse(xhr.responseText);
                    if (staff.error) {
                        alert(staff.error);
                        return;
                    }
                    document.getElementById("editStaffId").value = staff.id;
                    document.getElementById("editFirstName").value = staff.firstname;
                    document.getElementById("editLastName").value = staff.lastname;
                    document.getElementById("editEmail").value = staff.email;
                    document.getElementById("editUsername").value = staff.username;
                    document.getElementById("editPassword").value = '';
                    editModal.style.display = "block";
                }
            };
            xhr.send();
        }
    </script>
HTML;

// Include the admin layout
include 'admin_layout.php';

// End output buffering and flush output
ob_end_flush();
