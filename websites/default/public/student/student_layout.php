<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started
}

// Check if the user is logged in and is a student
if (!isset($_SESSION['username'])) {
    header('Location: ../student_login.php'); // Redirect to login page if not logged in
    exit();
}

$pageTitle = "Student Dashboard";
$activePage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylee.css">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body class="student-body">
    <div class="sidebar">
        <div class="sidebar-header">
            <h2 class="studentheader">STUDENT</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="<?php echo $activePage == 'mycourse.php' ? 'active_side' : ''; ?>">
                <a href="mycourse.php">My Courses</a>
            </li>
            <li class="<?php echo $activePage == 'module.php' ? 'active_side' : ''; ?>">
                <a href="module.php">Module</a>
            </li>
            <li class="<?php echo $activePage == 'assignments.php' ? 'active_side' : ''; ?>">
                <a href="assignments.php">Assignments</a>
            </li>
            <li class="<?php echo $activePage == 'calendar.php' ? 'active_side' : ''; ?>">
                <a href="calendar.php">Calendar</a>
            </li>
            <li class="<?php echo $activePage == 'grades.php' ? 'active_side' : ''; ?>">
                <a href="grades.php">Grades</a>
            </li>
           
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="student-header">
            <h1><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="student_logo">
                <img src="../images/Uni-logo.png" alt="Woodland University College Logo">
            </div>
        </header>

        <!-- Main content area -->
        <main>
            <?php echo isset($content) ? $content : ''; ?>
        </main>
    </div>
</body>
</html>
