<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch course information if ID is set
$course = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM courses WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $query = "UPDATE courses SET course_name = :course_name, description = :description WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Refresh the page to show updated records
    header('Location: course_record.php');
    exit();
}

// Handle Create
if (isset($_POST['create'])) {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $query = "INSERT INTO courses (course_name, description) VALUES (:course_name, :description)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Refresh the page to show updated records
    header('Location: course_record.php');
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM courses WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Handle Archive
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];

    $query = "UPDATE courses SET is_archived = 1 WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Refresh the page to show updated records
    header('Location: course_record.php');
    exit();
}

// Fetch courses
$query = "SELECT * FROM courses WHERE is_archived = 0"; // Exclude archived courses
$stmt = $conn->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the content for this page
$content = '
    <div class="table-container">
        <h1 class="table-title">Courses</h1>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>';

foreach ($courses as $row) {
    $content .= '
            <tr>
                <td>' . htmlspecialchars($row['course_name']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
                <td>
                    <button class="button" onclick="openEditDialog(' . htmlspecialchars($row['id']) . ')">Edit</button>
                    <a class="button" href="course_record.php?archive=' . htmlspecialchars($row['id']) . '">Archive</a>
                    <a class="button" href="course_record.php?delete=' . htmlspecialchars($row['id']) . '">Delete</a>
                </td>
            </tr>';
}

$content .= '
        </table>
        <div class="button-group">
            <button id="addCourseBtn" class="button">Add Course</button>
            <a href="printcourse.php" class="button">Print Records</a>
            <a href="archivedcourses.php" class="button">View Archived Courses</a>
        </div>
    </div>

    <!-- Add Course Dialog -->
    <div id="addCourseDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddDialog()">&times;</span>
            <h1>Add New Course</h1>
            <form id="addCourseForm" method="POST">
                <input type="text" name="course_name" placeholder="Course Name" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <button type="submit" name="create">Add Course</button>
            </form>
        </div>
    </div>

    <!-- Edit Course Dialog -->
    <div id="editCourseDialog" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditDialog()">&times;</span>
            <h1>Edit Course</h1>
            <form id="editCourseForm" method="POST">
                <input type="hidden" name="id" id="editCourseId">
                <input type="text" name="course_name" id="editCourseName" placeholder="Course Name" required>
                <textarea name="description" id="editDescription" placeholder="Description" required></textarea>
                <button type="submit" name="update">Update Course</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        var addModal = document.getElementById("addCourseDialog");
        var editModal = document.getElementById("editCourseDialog");
        var addBtn = document.getElementById("addCourseBtn");
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
            xhr.open("GET", "fetch_course.php?id=" + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var course = JSON.parse(xhr.responseText);
                    document.getElementById("editCourseId").value = course.id;
                    document.getElementById("editCourseName").value = course.course_name;
                    document.getElementById("editDescription").value = course.description;
                    editModal.style.display = "block";
                }
            };
            xhr.send();
        }
    </script>';

// Include the admin layout
include 'admin_layout.php';
?>
