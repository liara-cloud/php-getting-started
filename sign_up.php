<?php
include_once 'users_db.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

$env = parse_ini_file('.env');

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
    $password_valid = true;
    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[A-Z]+#", $password)) {
        $password_error = "Password must be at least 8 characters including capital letters and numbers";
        $password_valid = false;
    }

    // Clear password field if it's not valid
    if (!$password_valid) {
        $password = ""; // Clear password field
    }

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email='$email'";
    $check_email_result = $conn->query($check_email_query);
    if ($check_email_result->num_rows > 0) {
        $email_error = "This email is already used.";
    } else {
        // Display password error message if not valid
        if (!$password_valid) {
            $password_error = "Password must be at least 8 characters including capital letters and numbers";
        } else {
            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $env['MAIL_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $env['MAIL_USER'];
                $mail->Password   = $env['MAIL_PASS'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $env['MAIL_PORT'];

                $mail->setFrom($env['MAIL_FROM'], $env['MAIL_NAME']);
                $mail->addAddress($email, $first_name . ' ' . $last_name);
                $mail->isHTML(true);
                $mail->Subject = 'Welcome to Liara PHP Blog';
                $mail->Body    = 'So proud, to have you!';

                $mail->send();
                echo 'Email successfully sent!';

                // Store user data in database
                $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                $sql = "INSERT INTO users (first_name, last_name, email, password)
                        VALUES ('$first_name', '$last_name', '$email', '$password')";
                if ($conn->query($sql) === TRUE) {
                    // Store user ID in session after successful registration
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['email'] = $email;

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
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } catch (Exception $e) {
                echo "Error sending email: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<?php include_once 'users_db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت‌نام</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center; /* Center text */
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left; /* Right-align labels */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        p {
            margin-top: 20px;
            font-size: 16px;
            color: #555;
        }

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include_once 'header.php'; ?>
    <br><br>
    <br><br>
    <form action="" method="post">
        <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name" required><br><br>
        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" required><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <span style="color: red;"><?php if(isset($email_error)) { echo $email_error; } ?></span><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required><br>
        <span style="color: red;"><?php if(isset($password_error)) { echo $password_error; } ?></span><br><br>
        <input type="submit" value="Sign Up">
    </form>
    
    <p>Have an account? <a href="login.php">Login</a></p>
</body>
</html>
