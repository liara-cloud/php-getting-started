<?php
include_once 'users_db.php';

// Start session
session_start();

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
            
            // Redirect to new_post.php
            header("Location: new_post.php");
            exit();
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
</head>
<body>
    <h1>ورود</h1>

    <form action="" method="post">
        <label for="email">ایمیل:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">رمز عبور:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="ورود">
    </form>
    
    <p>اگر ثبت نام نکرده‌اید، <a href="signup.php">اینجا</a> را کلیک کنید.</p>
</body>
</html>
