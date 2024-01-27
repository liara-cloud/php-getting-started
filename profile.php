<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Set return_to session variable to current page
    $_SESSION['return_to'] = $_SERVER['PHP_SELF'];

    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Get user information from session
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f3f3;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .profile-info {
            margin-bottom: 40px;
        }

        .profile-field {
            margin-bottom: 20px;
        }

        .profile-field strong {
            display: inline-block;
            width: 120px;
            font-size: 18px;
            color: #555;
        }

        .profile-field span {
            font-size: 18px;
            color: #333;
        }

        .logout-link {
            text-align: center;
            margin-top: 40px;
        }

        a {
            color: #ff8c42;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #ff5e78;
        }
    </style>
</head>
<body>
    <?php include_once 'header_in.php'; ?>
    <div class="container">
        <h1>User Profile</h1>
        <div class="profile-info">
            <div class="profile-field">
                <strong>Name:</strong>
                <span><?php echo $first_name; ?></span>
            </div>
            <div class="profile-field">
                <strong>Last Name:</strong>
                <span><?php echo $last_name; ?></span>
            </div>
            <div class="profile-field">
                <strong>Email:</strong>
                <span><?php echo $email; ?></span>
            </div>
        </div>
        <div class="logout-link"><a href="logout.php">Logout</a></div>
    </div>
</body>
</html>
