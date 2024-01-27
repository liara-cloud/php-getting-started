<?php
include_once 'users_db.php';

// Start session
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user data from database based on email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];

            // Redirect based on return_to session variable
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
            echo "رمز عبور اشتباه است.";
        }
    } else {
        echo "کاربری با این ایمیل یافت نشد.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود</title>
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
<br></br>
<br></br>

    <form action="" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    
    <p>Not a user yet? <a href="sign_up.php">Register</a></p>
</body>
</html>
