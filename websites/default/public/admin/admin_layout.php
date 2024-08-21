<?php
$pageTitle = "Dashboard";
$activePage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylee.css">
    <title><?php echo $pageTitle; ?></title>
</head>
<body class="admin-body">
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>ADMIN</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="<?php echo $activePage == 'studentrecord.php' ? 'active' : ''; ?>">
                <a href="studentrecord.php">Student Record</a>
            </li>
            <li class="<?php echo $activePage == 'staffrecord.php' ? 'active' : ''; ?>">
                <a href="staffrecord.php">Staff Record</a>
            </li>
            <li class="<?php echo $activePage == 'course_record.php' ? 'active' : ''; ?>">
                <a href="course_record.php">Course Record</a>
            </li>
            <li class="<?php echo $activePage == 'module_record.php' ? 'active' : ''; ?>">
                <a href="module_record.php">Module Record</a>
            </li>
            <li class="<?php echo $activePage == 'assignment.php' ? 'active' : ''; ?>">
                <a href="assignment.php">Assignments</a>
            </li>
            <li class="<?php echo $activePage == 'attendance.php' ? 'active' : ''; ?>">
                <a href="attendance.php">Attendance</a>
            </li>
            <li><a href="../index.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="admin-header">
            <h1><?php echo $pageTitle; ?></h1>
            <div class="admin_logo">
                <img src="/images/Uni-logo.png" alt="Woodland University College Logo">
            </div>
        </header>

        <!-- Main content area -->
        <main>
            <?php echo isset($content) ? $content : ''; ?>
        </main>
    </div>
</body>
</html>
