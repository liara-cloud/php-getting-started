<?php
include_once 'database_conf.php';

// Start session
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['return_to'] = $_SERVER['PHP_SELF'];
    header("Location: login.php");
    exit();
}

// Function to handle redirect
function redirectTo($location) {
    header("Location: $location");
    exit();
}

// Handle file upload and form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    
    // Generate unique file name using current timestamp
    $upload_dir = "uploads/";
    $target_file = $upload_dir . time() . '.' . $imageFileType;

    // Check if uploads directory exists, if not, create it
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Check file size (limit to 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
    // Allow certain file formats
    } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    // if everything is ok, try to upload file
    } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // File uploaded successfully, now insert data into database
        $image = mysqli_real_escape_string($conn, $target_file);

        // Insert post into database
        $sql = "INSERT INTO posts (title, content, image)
        VALUES ('$title', '$content', '$image')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to index.php after successful post submission
            redirectTo("index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post 🥳</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 80px auto 20px; /* Adjust margin to accommodate fixed header */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 18px;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        .form-group input[type="text"],
        .form-group textarea {
            width: calc(100% - 40px);
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            resize: vertical; /* Allow vertical resizing */
        }
        .form-group input[type="file"] {
            width: calc(100% - 40px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            cursor: pointer; /* Add pointer cursor */
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group input[type="text"]:focus,
        .form-group textarea:focus,
        .form-group input[type="file"]:focus {
            outline: none;
            border-color: #4CAF50;
        }
    </style>
</head>
<body>
    <?php include_once 'header_in.php'; ?>
    <div class="container">
        <h1>New Post 🥳</h1>

        <form action="" method="post" enctype="multipart/form-data" class="post-form">
            <div class="form-group">
                <label for="title">Subject:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Text:</label>
                <textarea id="content" name="content" rows="6" required></textarea> <!-- Set rows for textarea -->
            </div>
            <div class="form-group">
                <label for="image">Choose Image (Maximum: 5M):</label>
                <input type="file" id="image" name="image" required>
            </div>
            <input type="submit" value="Publish 🔥">
        </form>
    </div>
</body>
</html>
