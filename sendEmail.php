<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mailHost = $_ENV['MAIL_HOST'];
$mailPort = $_ENV['MAIL_PORT'];
$mailUser = $_ENV['MAIL_USER'];
$mailPassword = $_ENV['MAIL_PASSWORD'];
$mailSecurity = $_ENV['MAIL_SECURITY'];

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth   = true;
$mail->SMTPSecure = $mailSecurity;
$mail->Port       = $mailPort;
$mail->Host       = $mailHost;
$mail->Username   = $mailUser;
$mail->Password   = $mailPassword;


$mail->From     = "your email";
$mail->FromName = "your email name";

$mail->addAddress("destination email", "destination name");
$mail->isHTML(false);

$mail->Subject = "Mailing with PHPMailer";
$mail->Body = "Congratulation";
$mail->AltBody = "Congratulation";

try {
    $mail->send();
    echo "Message has been sent successfully";
} catch (Exception $e) {
    error_log("Mailer Error: $mail->ErrorInfo", 0);
}