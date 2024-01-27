<?php
include_once 'users_db.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start session
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check password requirements
    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[A-Z]+#", $password)) {
        echo "رمز عبور باید حداقل 8 کاراکتر شامل حروف بزرگ و اعداد باشد.";
        exit;
    }

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email='$email'";
    $check_email_result = $conn->query($check_email_query);
    if ($check_email_result->num_rows > 0) {
        $email_error = "این ایمیل قبلاً استفاده شده است.";
    } else {
        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.c1.liara.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sleepy_bell_81p32m';
            $mail->Password   = '04428cb9-6922-48ab-8023-8595530e165d';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('info@alinajmabadi.ir', 'alips');
            $mail->addAddress($email, $first_name . ' ' . $last_name);
            $mail->isHTML(true);
            $mail->Subject = 'welcome';
            $mail->Body    = 'so proud, to having you!';

            $mail->send();
            echo 'ایمیل با موفقیت ارسال شد!';
        } catch (Exception $e) {
            echo "خطا در ارسال ایمیل: {$mail->ErrorInfo}";
        }

        // Store user data in database
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $sql = "INSERT INTO users (first_name, last_name, email, password)
                VALUES ('$first_name', '$last_name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Store user ID in session after successful registration
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];

            // Redirect to new_post.php
            if (isset($_SESSION['return_to'])) {
                $return_to = $_SESSION['return_to'];
                unset($_SESSION['return_to']); // Clear the return_to session variable
                header("Location: $return_to");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            echo "خطا: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت‌نام</title>
</head>
<body>
    <h1>ثبت‌نام</h1>

    <form action="" method="post">
        <label for="first_name">نام:</label><br>
        <input type="text" id="first_name" name="first_name" required><br><br>
        <label for="last_name">نام خانوادگی:</label><br>
        <input type="text" id="last_name" name="last_name" required><br><br>
        <label for="email">ایمیل:</label><br>
        <input type="email" id="email" name="email" required><br>
        <span style="color: red;"><?php if(isset($email_error)) { echo $email_error; } ?></span><br><br>
        <label for="password">رمز عبور:</label><br>
        <input type="password" id="password" name="password" required><br>
        <span style="color: red;"><?php if(isset($password_error)) { echo $password_error; } ?></span><br><br>
        <input type="submit" value="ثبت‌نام">
    </form>
</body>
</html>
