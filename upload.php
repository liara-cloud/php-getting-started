<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استقرار برنامه‌ی آپلود فایل در لیارا</title>
    <link href="./assets/img/icon.svg" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="./assets/css/upload.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <form action="saveFile.php" method="POST" enctype="multipart/form-data">
            <h3 class="text-center mb-5">File Upload in PHP</h3>
            <?php
            if (isset($_SESSION['message']['success']) && $_SESSION['message']['success']) :
                echo "<div class='alert alert-success'><strong>"
                    . $_SESSION['message']['success'] .
                    "</strong></div>";
                unset($_SESSION['message']['success']);
            endif;
            ?>

            <?php
            if (isset($_SESSION['message']['failed']) && $_SESSION['message']['failed']) :
                echo "<div class='alert alert-danger'><strong>"
                    . $_SESSION['message']['failed'] .
                    "</strong></div>";
                unset($_SESSION['message']['failed']);
            endif;
            ?>

            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input" id="chooseFile">
                <label class="custom-file-label" for="chooseFile">Select file</label>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                Upload Files
            </button>
        </form>
        <div class="container-fluid bg-light" style="margin: 15px 0 0 0">
            <?php
            $pictures = glob("uploads/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
            foreach ($pictures as $picture) :
                $pictureName = str_replace("uploads/", "", $picture);
                echo "<p>$pictureName <a href='download.php?name=$pictureName' target='_blank'>Download</a></p>";
            endforeach;
            ?>
        </div>
    </div>

    <script>
        const input = document.getElementById('chooseFile');
        input.addEventListener('change', changeFileName);

        function changeFileName(event) {
            const label = document.getElementsByClassName('custom-file-label');
            label[0].innerHTML = event.srcElement.files[0].name;
        }
    </script>
</body>

</html>