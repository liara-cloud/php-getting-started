<?php
$database_url = getenv("DATABASE_URL");

if (!$database_url) {
    die("تنظیمات اتصال به دیتابیس تعیین نشده‌اند.");
}

$db_info = parse_url($database_url);

$db_host = $db_info["host"];
$db_port = $db_info["port"];
$db_user = $db_info["user"];
$db_pass = $db_info["pass"];
$db_name = ltrim($db_info["path"], '/');

// اتصال به دیتابیس
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// بررسی اتصال
if ($conn->connect_error) {
    die("اتصال به دیتابیس ناموفق بود: " . $conn->connect_error);
}

echo "اتصال به دیتابیس با موفقیت برقرار شد.";

// بستن اتصال
$conn->close();
?>
