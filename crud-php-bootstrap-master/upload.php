<?php
// Directory to save uploaded files
$uploadDir = 'uploads/';

// Create the uploads directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];

    // Sanitize file name
    $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
     
    // Set the destination path
    $destPath = $uploadDir . $fileName;

    // Move the uploaded file to the destination directory
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        echo "File uploaded successfully! <a href='$destPath'>View File</a>";
    } else {
        echo "There was an error uploading the file.";
    }
} else {
    echo "No file was uploaded.";
}
?>
