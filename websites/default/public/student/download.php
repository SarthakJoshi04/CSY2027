<?php
// Include the database connection
require_once '../dbconnection.php';

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started
}

// Check if the user is logged in and is a student
if (!isset($_SESSION['username'])) {
    header('Location: student_login.php'); // Redirect to login page if not logged in
    exit();
}

// Create a database connection
$db = new DatabaseConnection();
$pdo = $db->getConnection();

// Handle file download
if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Get the file name from query string
    $filePath = '../uploads/' . $file; // Adjust path as needed

    // Check if file exists
    if (file_exists($filePath)) {
        // Determine the MIME type based on file extension
        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        switch ($fileExtension) {
            case 'doc':
                $mimeType = 'application/msword';
                break;
            case 'docx':
                $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            default:
                $mimeType = 'application/octet-stream'; // Default MIME type
                break;
        }

        // Set headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        // Read the file and output it
        readfile($filePath);
        exit();
    } else {
        // File not found
        die("The file does not exist.");
    }
} else {
    // No file specified
    die("No file specified.");
}
