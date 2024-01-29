<?php

include_once 'database_conf.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
require 'vendor/autoload.php'; 

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

// IN DEVELOPMENT MODE, USE THIS
$env = parse_ini_file('.env');
$bucket   = $env['LIARA_BUCKET_NAME'];
$key      = $env['LIARA_ACCESS_KEY'];
$secret   = $env['LIARA_SECRET_KEY'];
$region   = $env['LIARA_REGION'];
$endpoint = $env['LIARA_ENDPOINT']; 

// // IN PRODUCTION MODE, USE THIS
// $env = parse_ini_file('.env');
// $bucket   = getemv('LIARA_BUCKET_NAME');
// $key      = getemv('LIARA_ACCESS_KEY');
// $secret   = getemv('LIARA_SECRET_KEY');
// $region   = getemv('LIARA_REGION');
// $endpoint = getemv('LIARA_ENDPOINT'); 

// Create an S3Client
$s3 = new S3Client([
    'version' => 'latest',
    'region' => $region,
    'credentials' => [
        'key' => $key,
        'secret' => $secret,
    ],
    'endpoint' => $endpoint, 
]);

// Handle file upload and form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    } else {
        // Upload image to S3
        $keyName = time() . '.' . $imageFileType; // Unique key name
        $target_file = $_FILES["image"]["tmp_name"];
        try {
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $keyName,
                'Body'   => fopen($target_file, 'rb'),
                'ACL'    => 'public-read', // Set ACL to public-read for public access
            ]);

            // File uploaded successfully, now insert data into database
            $imageUrl = $result['ObjectURL']; // Get image URL from S3 response

            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $content = mysqli_real_escape_string($conn, $_POST['content']);
            $image = mysqli_real_escape_string($conn, $imageUrl);
            
            $sql = "INSERT INTO posts (title, content, image)
            VALUES ('$title', '$content', '$image')";
            
            if ($conn->query($sql) === TRUE) {
                // Redirect to index.php after successful post submission
                redirectTo("index.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            echo "Image uploaded successfully: $imageUrl";
        } catch (S3Exception $e) {
            echo "Error uploading image: " . $e->getMessage();
            }
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

