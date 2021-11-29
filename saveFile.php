<?php
session_start();

if (isset($_POST['submit'])) :
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) :
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

        if (in_array($fileExtension, $allowedfileExtensions)) :
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) :
                $_SESSION['message']['success'] = "File has been uploaded.";
            else :
                $_SESSION['message']['failed'] = "There was some error moving the file to upload directory.";
            endif;
        else :
            $_SESSION['message']['failed'] .= "Allowed file types: " . implode(', ', $allowedfileExtensions);
        endif;
    endif;
endif;

header("Location: /upload.php");
exit;