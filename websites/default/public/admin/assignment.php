<?php
ob_start(); // Start output buffering

// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Define the target directory
$target_dir = "../uploads/";

// Create the directory if it doesn't exist
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

// Fetch courses for the dropdown
$query = "SELECT id, course_name FROM courses";
$stmt = $conn->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch assignment information if ID is set (for editing)
$assignment = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM assignments WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $course_id = $_POST['course_id'];
    $file_path = $_POST['existing_file']; // Store existing file path by default

    // Handle file upload
    if (!empty($_FILES['file']['name'])) {
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = basename($_FILES["file"]["name"]);
        } else {
            echo "File upload failed.";
        }
    }

    $query = "UPDATE assignments SET title = :title, description = :description, due_date = :due_date, course_id = :course_id, file_path = :file_path WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':file_path', $file_path);
    $stmt->execute();

    header('Location: assignment.php');
    exit();
}

// Handle Create
if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $course_id = $_POST['course_id'];
    $file_path = '';

    // Handle file upload
    if (!empty($_FILES['file']['name'])) {
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = basename($_FILES["file"]["name"]);
        } else {
            echo "File upload failed.";
        }
    }

    $query = "INSERT INTO assignments (title, description, due_date, course_id, file_path) VALUES (:title, :description, :due_date, :course_id, :file_path)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':file_path', $file_path);
    $stmt->execute();

    header('Location: assignment.php');
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM assignments WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Fetch assignments
$query = "SELECT * FROM assignments";
$stmt = $conn->prepare($query);
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <div class="table-container">
        <h1 class="table-title">Assignments</h1>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Course</th>
                <th>File</th>
                <th>Actions</th>
            </tr>';

foreach ($assignments as $row) {
    $title = htmlspecialchars($row['title'] ?? '');
    $description = htmlspecialchars($row['description'] ?? '');
    $due_date = htmlspecialchars($row['due_date'] ?? '');
    $course_id = htmlspecialchars($row['course_id'] ?? '');
    $file_path = htmlspecialchars($row['file_path'] ?? '');

    // Fetch course name for the assignment
    $course_name = '';
    if ($course_id) {
        $query = "SELECT course_name FROM courses WHERE id = :course_id"; // Adjust 'name' to 'course_name'
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        $course_name = htmlspecialchars($course['course_name'] ?? '');
    }

    $content .= '
            <tr>
                <td>' . $title . '</td>
                <td>' . $description . '</td>
                <td>' . $due_date . '</td>
                <td>' . $course_name . '</td>
                <td>' . ($file_path ? '<a href="../uploads/' . $file_path . '" target="_blank">View File</a>' : 'No File') . '</td>
                <td>
                    <button onclick="openEditDialog(' . htmlspecialchars($row['id']) . ')">Edit</button>
                    <a href="assignment.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
                </td>
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <button id="addAssignmentBtn" class="button">Add Assignment</button>
        </div>
    </div>

    <!-- Add Assignment Dialog -->
    <div id="addAssignmentDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddDialog()">&times;</span>
            <h1>Add New Assignment</h1>
            <form id="addAssignmentForm" method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Assignment Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="date" name="due_date" required>
                <select name="course_id" required>
                    <option value="">Select Course</option>';

foreach ($courses as $course) {
    $content .= '<option value="' . htmlspecialchars($course['id']) . '">' . htmlspecialchars($course['course_name']) . '</option>'; // Adjust 'name' to 'course_name'
}

$content .= '
                </select>
                <input type="file" name="file">
                <button type="submit" name="create">Add Assignment</button>
            </form>
        </div>
    </div>

    <!-- Edit Assignment Dialog -->
    <div id="editAssignmentDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditDialog()">&times;</span>
            <h1>Edit Assignment</h1>
            <form id="editAssignmentForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editAssignmentId">
                <input type="text" name="title" id="editTitle" placeholder="Assignment Title" required>
                <textarea name="description" id="editDescription" placeholder="Description" required></textarea>
                <input type="date" name="due_date" id="editDueDate" required>
                <select name="course_id" id="editCourseId" required>
                    <option value="">Select Course</option>';

foreach ($courses as $course) {
    $content .= '<option value="' . htmlspecialchars($course['id']) . '">' . htmlspecialchars($course['course_name']) . '</option>'; // Adjust 'name' to 'course_name'
}

$content .= '
                </select>
                <input type="hidden" name="existing_file" id="editExistingFile">
                <input type="file" name="file">
                <button type="submit" name="update">Update Assignment</button>
            </form>
        </div>
    </div>

    <script>
        var addModal = document.getElementById("addAssignmentDialog");
        var editModal = document.getElementById("editAssignmentDialog");
        var addBtn = document.getElementById("addAssignmentBtn");
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
            xhr.open("GET", "fetch_assignment.php?id=" + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var assignment = JSON.parse(xhr.responseText);
                    document.getElementById("editAssignmentId").value = assignment.id;
                    document.getElementById("editTitle").value = assignment.title;
                    document.getElementById("editDescription").value = assignment.description;
                    document.getElementById("editDueDate").value = assignment.due_date;
                    document.getElementById("editCourseId").value = assignment.course_id;
                    document.getElementById("editExistingFile").value = assignment.file_path;
                    // Show the modal
                    editModal.style.display = "block";
                } else {
                    console.error("Error fetching assignment data:", xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error("Request failed");
            };
            xhr.send();
        }

        function closeAddDialog() {
            addModal.style.display = "none";
        }

        function closeEditDialog() {
            editModal.style.display = "none";
        }
    </script>';

include 'admin_layout.php';

ob_end_flush(); // Send output to the browser

?>

