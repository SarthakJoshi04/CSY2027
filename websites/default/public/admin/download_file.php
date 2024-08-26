<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);
    $file_path = "../uploads/" . $file;

    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit();
    } else {
        http_response_code(404);
        echo "File not found.";
    }
} else {
    http_response_code(400);
    echo "No file specified.";
}
?>
