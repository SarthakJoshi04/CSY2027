<?php
session_start();
include('dbconnection.php');

$db = new DatabaseConnection();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for admin credentials
    if ($username === 'admin' && $password === 'LETMEIN') {
        $_SESSION['role'] = 'admin';
        header('Location: /admin/admin_layout.php'); // Redirect to the admin dashboard
        exit();
    } else {
        // Check for staff credentials in the database
        $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $staff = $stmt->fetch();

        if ($staff && password_verify($password, $staff['password'])) {
            $_SESSION['role'] = 'staff';
            header('Location: /staff/staff_layout.php'); // Redirect to staff dashboard
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body class="staff_login">
    <div class="login-container-staff">
        <div class="login-form-staff">
            <h2>STAFF LOGIN</h2>
            <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="#" class="forgot-password-staff">Forget Password?</a>
                <button type="submit">LOGIN</button>
            </form>
            <a href="student_login.php" class="student-login">Login as Student?</a>
        </div>
        <div class="stubble-staff"></div>
    </div>

    <div class="right-container-staff">
        <div class="right-content-staff">
            <!-- Add any additional content here -->
        </div>
        <div class="stubbler-staff"></div>
    </div>
</body>
</html>
