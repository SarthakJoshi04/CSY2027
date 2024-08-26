<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started
} // Start the session
include 'dbconnection.php'; 

 // Include the database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Create a new database connection instance
    $db = new DatabaseConnection();
    $conn = $db->getConnection();

    // Prepare and execute the query to fetch user data
    $sql = "SELECT * FROM students WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password and check if the user exists
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, login successful
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        $_SESSION['username'] = $user['username']; // Store username in session
        header("Location: /student/mycourse.php"); // Redirect to student_layout.php
        exit();
    } else {
        // Authentication failed
        echo "<script>alert('Invalid username or password'); window.location.href='student_login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body class="login_body">
    <div class="right-container">
        <div class="right-content"></div>
        <div class="stubbler"></div>
    </div>

    <div class="login-container">
        <div class="login-form">
            <h2>STUDENT LOGIN</h2>
            <form action="student_login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="#" class="forgot-password">Forget Password?</a>
                <button type="submit">LOGIN</button>
                <a href="staff_login.php" class="staff-login">Login as Staff?</a>
            </form>
        </div>
        <div class="stubble"></div>
    </div>
</body>
</html>
