<?php
if (isset($_POST['upload'])) {
    $uploadDir = 'uploads/';  // Folder name to save images
    $uploadedFile = $uploadDir . basename($_FILES['image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($uploadedFile, PATHINFO_EXTENSION));

    // Check if the file is a valid image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        $errorMessage = "The selected file is not an image.";
    }

    // Check if the file already exists
    if (file_exists($uploadedFile)) {
        $uploadOk = 0;
        $errorMessage = "Sorry, a file with this name already exists.";
    }

    // Check if the file size exceeds the limit (e.g., 2 megabytes)
    if ($_FILES['image']['size'] > 2000000) {
        $uploadOk = 0;
        $errorMessage = "Sorry, the file size exceeds the allowed limit.";
    }

    // If all checks are successful, upload the file
    if ($uploadOk == 1) {
        // Create a new name based on the upload time and a unique identifier
        $newFileName = 'image_' . date('YmdHis') . '_' . uniqid() . '.' . $imageFileType;
        $newFilePath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $newFilePath)) {
            $successMessage = "File uploaded successfully.";
            $imageLink = "<a href='{$newFilePath}' target='_blank'>{$newFileName}</a>";
        } else {
            $errorMessage = "Sorry, there was an issue uploading the file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            text-align: center;
            margin: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        form {
            margin: 40px;
            padding: 60px;
            background-color: #555;
            border-radius: 24px;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
            display: inline-block;
        }
        label {
            display: block;
            margin-bottom: 30px;
            color: #fff;
            font-size: 24px;
        }
        input[type="file"] {
            margin-bottom: 40px;
            display: none;
        }
        .custom-file-upload {
            border: 3px solid #fff;
            display: inline-block;
            padding: 24px 48px;
            cursor: pointer;
            color: #fff;
            font-size: 24px;
        }
        button {
            background-color: #3498db;
            color: #fff;
            padding: 24px 48px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 24px;
        }
        button:hover {
            background-color: #2980b9;
        }
        .message {
            margin-top: 40px;
            padding: 30px;
            border-radius: 24px;
            font-size: 20px;
        }
        .success {
            background-color: #2ecc71;
            color: #fff;
            border: 3px solid #27ae60;
        }
        .error {
            background-color: #e74c3c;
            color: #fff;
            border: 3px solid #c0392b;
        }
    </style>
</head>
<body>
    <h1>Image Upload</h1>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <label class="custom-file-upload">
            <input type="file" name="image" accept="image/*" required>
            Choose File
        </label>
        <br>
        <button type="submit" name="upload">Upload</button>
    </form>

    <?php
    if (isset($errorMessage)) {
        echo "<div class='message error'>$errorMessage</div>";
    }

    if (isset($successMessage)) {
        echo "<div class='message success'>$successMessage<br>Image Link: $imageLink</div>";
    }
    ?>
</body>
</html>
