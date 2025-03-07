<?php

$servername = "localhost";  // Usually localhost for Plesk
$username = "Blogs"; // Database username
$password = "Bhupa@898"; // Database password
$dbname = "admin_blog";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Handle image upload (same as before)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_type = $image['type'];
        $image_size = $image['size'];
        
        // Define allowed file types
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($image_type, $allowed_types)) {
            // Set the upload directory
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
            $image_path = $upload_dir . basename($image_name);

            $db_image_path = 'uploads/' . basename($image_name);
            
            // Move the uploaded file
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                echo "Image uploaded successfully.<br>";
            } else {
                echo "Failed to upload the image.<br>";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF are allowed.<br>";
        }
    } else {
        echo "No image uploaded or there was an error with the upload.<br>";
    }

    // Retrieve other form data
    $main_heading = htmlspecialchars($_POST['main_heading']);
    $main_content = htmlspecialchars($_POST['main_content']);
    $subheadings = isset($_POST['subheading']) ? $_POST['subheading'] : [];
    $subheading_content = isset($_POST['subheading_content']) ? $_POST['subheading_content'] : [];

   
    // Connect to SQLite database
    // Insert the data into the database
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Using SQLite database
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert main post 
        $stmt = $conn->prepare("INSERT INTO posts (title, image, content) VALUES (?, ?, ?)");
        $stmt->execute([$main_heading, $db_image_path, $main_content]);

        // Get the post ID of the inserted blog post
        $post_id = $conn->lastInsertId();
        // Insert subheadings and their content
        for ($i = 0; $i < count($subheadings); $i++) {
            if (!empty($subheadings[$i]) && !empty($subheading_content[$i])) {
                $subheading = htmlspecialchars($subheadings[$i]);
                $content = htmlspecialchars($subheading_content[$i]);
                
                $subheading_stmt = $conn->prepare("INSERT INTO subheadings (post_id, subheading, content) VALUES (?, ?, ?)");
                $subheading_stmt->execute([$post_id, $subheading, $content]);
            }
        }
        echo "Post and subheadings added successfully!";
        header('Location: /blog-admin.html');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // $stmt->close();
    $conn = null;
    $conn->close();
}
?>
