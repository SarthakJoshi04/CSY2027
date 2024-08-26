<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started
}

// Check if the user is logged in and has the role 'staff'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header('Location: /staff/staff_login.php'); // Redirect to staff login if not logged in
    exit();
}


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
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('#searchButton').addEventListener('click', function() {
                let searchTerm = document.querySelector('#searchInput').value;
                if (searchTerm.length > 2) {
                    window.location.href = `search_result.php?query=${encodeURIComponent(searchTerm)}`;
                } else {
                    alert('Please enter at least 3 characters.');
                }
            });

            document.querySelector('#searchInput').addEventListener('input', function() {
                let searchTerm = this.value;
                if (searchTerm.length > 2) {
                    fetch(`autocomplete.php?term=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            let suggestionsList = document.querySelector('#suggestions');
                            suggestionsList.innerHTML = '';

                            data.forEach(item => {
                                let listItem = document.createElement('li');
                                listItem.textContent = item;
                                suggestionsList.appendChild(listItem);
                            });
                        })
                        .catch(error => console.error('Error fetching autocomplete data:', error));
                }
            });
        });
    </script> -->
</head>
<body class="admin-body">
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>STAFF</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="<?php echo $activePage == '../admin/studentrecord.php' ? 'active' : ''; ?>">
                <a href='../staff/studentrecord.php'>Student Record</a>
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
            
            <li><a href="../index.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="admin-header">
            <h1><?php echo $pageTitle; ?></h1>
            <div class="admin_logo">
                <img src="../images/Uni-logo.png" alt="Woodland University College Logo">
            </div>
            <!-- <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search...">
                <button id="searchButton">Search</button>
                <ul id="suggestions"></ul>
            </div> -->
        </header>

        <!-- Main content area -->
        <main>
            <?php echo isset($content) ? $content : ''; ?>
        </main>
    </div>
</body>
</html>
