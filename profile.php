<?php
session_start();

// اگر کاربر لاگین نکرده باشد، به صفحه لاگین هدایت شود
if (!isset($_SESSION['user_id'])) {
    // Set return_to session variable to current page
    $_SESSION['return_to'] = $_SERVER['PHP_SELF'];

    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// اطلاعات کاربر از جلسه دریافت شده
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$email = $_SESSION['email'];

// این قسمت می‌تواند با استفاده از اطلاعات دیگری که مایلید نمایش دهید، گسترش یابد

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل کاربری</title>
</head>
<body>
    <h1>پروفایل کاربری</h1>
    <p>نام: <?php echo $first_name; ?></p>
    <p>نام خانوادگی: <?php echo $last_name; ?></p>
    <p>ایمیل: <?php echo $email; ?></p>
    <p><a href="logout.php">خروج</a></p>
</body>
</html>
