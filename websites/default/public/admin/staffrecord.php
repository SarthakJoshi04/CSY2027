<?php
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
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

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
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "UPDATE staff SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, password = :password WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM staff WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Fetch staff
$query = "SELECT * FROM staff";
$stmt = $conn->prepare($query);
$stmt->execute();
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Records</title>
    <link rel="stylesheet" href="../stylee.css">
</head>
<body>
    <h1>Staff Records</h1>
    <form method="POST">
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="lastname" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="create">Add Staff</button>
    </form>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($staff as $row) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['firstname']); ?></td>
            <td><?php echo htmlspecialchars($row['lastname']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td>
                <a href="staffrecord.php?edit=<?php echo htmlspecialchars($row['id']); ?>">Edit</a>
                <a href="staffrecord.php?delete=<?php echo htmlspecialchars($row['id']); ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
