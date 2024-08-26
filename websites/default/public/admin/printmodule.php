<?php
// Include database connection
include '../dbconnection.php';

// Create a new instance of DatabaseConnection
$db = new DatabaseConnection();
$conn = $db->getConnection();

// Fetch student records
$query = "SELECT module_name, description, course_id FROM modules";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Print Student Records</title>
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
            }
            h1 {
                text-align: center;
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f5f5f5;
                font-weight: bold;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <h1>Student Records</h1>
    <table>
        <tr>
            <th>Modules</th>
            <th>Description</th>
            <th>Course ID</th>
            
        </tr>
        <?php foreach ($students as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['module_name']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['course_id']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
