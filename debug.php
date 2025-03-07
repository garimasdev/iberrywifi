<?php
    // Save this as debug_path.php and access it through your browser
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $current_dir = __DIR__;
    $script_filename = $_SERVER['SCRIPT_FILENAME'];
    $server_name = $_SERVER['SERVER_NAME'];

    echo "<h2>Path Debug Information</h2>";
    echo "Document Root: " . $document_root . "<br>";
    echo "Current Directory: " . $current_dir . "<br>";
    echo "Script Filename: " . $script_filename . "<br>";
    echo "Server Name: " . $server_name . "<br>";

    // Also try to check if the uploads directory exists and is writable
    $uploads_path = $document_root . '/uploads/';
    echo "Uploads Path: " . $uploads_path . "<br>";
    echo "Uploads directory exists: " . (file_exists($uploads_path) ? 'Yes' : 'No') . "<br>";
    echo "Uploads directory is writable: " . (is_writable($uploads_path) ? 'Yes' : 'No') . "<br>";
?>