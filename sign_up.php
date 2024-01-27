<?php
include_once 'users_db.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // بررسی استانداردهای رمز عبور
    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[A-Z]+#", $password)) {
        echo "رمز عبور باید حداقل 8 کاراکتر شامل حروف بزرگ و اعداد باشد.";
        exit;
    }

    // ارسال ایمیل
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.c1.liara.email';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sleepy_bell_81p32m';
        $mail->Password   = '04428cb9-6922-48ab-8023-8595530e165d';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // ایجاد پیام
        $mail->setFrom('info@alinajmabadi.ir', 'alips');
        $mail->addAddress($email, $first_name . ' ' . $last_name);
        $mail->isHTML(true);
        $mail->Subject = 'successfull login - Liara Blog on PHP';
        $mail->Body    = 'Thank you for Registering!';

        $mail->send();
        echo 'ایمیل با موفقیت ارسال شد!';
    } catch (Exception $e) {
        echo "خطا در ارسال ایمیل: {$mail->ErrorInfo}";
    }

    // ذخیره اطلاعات کاربر در دیتابیس
    $password = password_hash($password, PASSWORD_DEFAULT); // هش کردن رمز عبور
    $sql = "INSERT INTO users (first_name, last_name, email, password)
    VALUES ('$first_name', '$last_name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "ثبت‌نام با موفقیت انجام شد!";
    } else {
        echo "خطا: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
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
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">رمز عبور:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="ثبت‌نام">
    </form>
</body>
</html>
