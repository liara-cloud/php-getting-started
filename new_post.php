<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "emam";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute create table query
if ($conn->query($sql) === TRUE) {
    // echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Check if uploads directory exists, if not, create it
$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    
    // Generate unique file name using current timestamp
    $target_file = $upload_dir . time() . '.' . $imageFileType;

    // Check file size (limit to 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
    // Allow certain file formats
    } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    // if everything is ok, try to upload file
    } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // File uploaded successfully, now insert data into database
        $image = $target_file;

        // Insert post into database
        $sql = "INSERT INTO posts (title, content, image)
        VALUES ('$title', '$content', '$image')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to index.php after successful post submission
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ارسال پست جدید</title>
</head>
<body>
    <h1>ارسال پست جدید</h1>

    <!-- فرم ارسال پست -->
    <form action="" method="post" enctype="multipart/form-data">
        <label for="title">عنوان:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        <label for="content">متن:</label><br>
        <textarea id="content" name="content" required></textarea><br><br>
        <label for="image">انتخاب تصویر (حداکثر 5MB):</label><br>
        <input type="file" id="image" name="image" required><br><br>
        <input type="submit" value="ارسال">
    </form>
</body>
</html>
