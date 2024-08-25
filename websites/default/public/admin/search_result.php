<?php
$pageTitle = "Search Results";
$searchQuery = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
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
        <!-- Sidebar content as in admin_layout.php -->
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
            <?php
            if (!empty($searchQuery)) {
                include_once '../dbconnection.php';

                try {
                    $database = new DatabaseConnection();
                    $conn = $database->getConnection();

                    // Adjust the columns and table names according to your schema
                    $stmt = $conn->prepare("
                        SELECT 'students' AS source, id, CONCAT(firstname, ' ', lastname) AS name, email AS details 
                        FROM students 
                        WHERE CONCAT(firstname, ' ', lastname) LIKE :searchTerm
                        UNION
                        SELECT 'staff' AS source, id, CONCAT(firstname, ' ', lastname) AS name, email AS details 
                        FROM staff 
                        WHERE CONCAT(firstname, ' ', lastname) LIKE :searchTerm
                    ");
                    $searchTerm = "%$searchQuery%";
                    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
                    $stmt->execute();

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($results) > 0) {
                        echo "<ul>";
                        foreach ($results as $row) {
                            echo "<li><strong>Table:</strong> " . htmlspecialchars($row['source']) . " - <strong>Name:</strong> " . htmlspecialchars($row['name']) . " - <strong>Details:</strong> " . htmlspecialchars($row['details']) . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No results found for '$searchQuery'.</p>";
                    }
                } catch (PDOException $e) {
                    echo "Database error: " . $e->getMessage();
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "<p>Please enter a search query.</p>";
            }
            ?>
        </main>
    </div>
</body>
</html>
