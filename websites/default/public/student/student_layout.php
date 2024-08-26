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
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body class="admin-body">
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
        <header class="admin-header">
            <h1><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="admin_logo">
                <img src="../images/Uni-logo.png" alt="Woodland University College Logo">
            </div>
        </header>
        <main>
            <!-- Dynamic content will be inserted here -->
            <?php echo isset($content) ? $content : ''; ?>
        </main>
    </div>
</body>
</html>
