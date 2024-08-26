<!-- student_landing.php -->
<?php
$pageTitle = "Student Landing Page";
$activePage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylee.css">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../path/to/fullcalendar.css"> <!-- Add your calendar CSS -->
    <script src="../path/to/moment.min.js"></script> <!-- Add your calendar JS dependency -->
    <script src="../path/to/fullcalendar.min.js"></script> <!-- Add your calendar JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    // Example events, replace with dynamic data if needed
                    { title: 'Event 1', start: '2024-09-01' },
                    { title: 'Event 2', start: '2024-09-07' }
                ]
            });
            calendar.render();
        });
    </script>
</head>
<body class="admin-body">
    <div class="sidebar">
        <div class="sidebar-header">
            <h2 class="studentheader">STUDENT</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="<?php echo $activePage == 'student_landing.php' ? 'active_side' : ''; ?>">
                <a href="student_landing.php">Landing Page</a>
            </li>
            <!-- Add other links here -->
        </ul>
    </div>

    <div class="main-content">
        <header class="admin-header">
            <h1><?php echo $pageTitle; ?></h1>
            <div class="admin_logo">
                <img src="../images/Uni-logo.png" alt="Woodland University College Logo">
            </div>
        </header>

        <!-- Main content area -->
        <main>
            <section class="announcements">
                <h2>Announcements</h2>
                <ul>
                    <li>Welcome to the new semester! Please check the course schedule.</li>
                    <li>Reminder: Submit your assignments by next Friday.</li>
                    <!-- Add more announcements here -->
                </ul>
            </section>

            <section class="calendar-container">
                <h2>Upcoming Events</h2>
                <div id="calendar"></div>
            </section>
            <iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&ctz=Asia%2FKathmandu&bgcolor=%23ffffff&showCalendars=0&showTabs=0&showPrint=0&showTitle=0&src=YzYwNGNiZmYzMmQ5MDBhMTU4YTk5YWYxOTgyZDVlZTIxZGM2Yzg4MjlmYTU1MDM0YzUzMDcwYmUzMmE4MGY5Y0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&color=%23EF6C00" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        </main>
    </div>
</body>
</html>
