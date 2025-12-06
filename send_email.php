<?php
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAIL_USER'];
    $mail->Password   = $_ENV['MAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = $_ENV['MAIL_PORT'];

    $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
    $mail->addAddress('test@example.com');

    // CC:
    $mail->addCC('test.one@example.com');
    $mail->addCC('test.two@example.com');
    $mail->addCC('test.three@example.com');

    // BCC:
    // $mail->addBCC('test.one@example.com');
    // $mail->addBCC('test.two@example.com');
    // $mail->addBCC('test.three@example.com');

    $mail->isHTML(true);                                  
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer and SMTP.';
    $mail->AltBody = 'This is the plain text version of the email content.';
    $mail->addCustomHeader('x-liara-tag', 'test-tag'); 


    $mail->send();
    echo 'Email has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
