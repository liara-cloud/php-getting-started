<?php
include_once 'database_conf.php';
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وبلاگ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include_once 'header.php'; ?>

<div class="container">
    <?php
    // Database connection
    include_once 'database_conf.php';

    // Display posts in descending order of creation time
    $sql = "SELECT * FROM posts ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='post'>";
            echo "<h2>" . $row["title"]. "</h2>";
            echo "<p>" . $row["content"]. "</p>";
            echo "<img src='" . $row["image"]. "'><br><br>";
            echo "</div>";
        }
    } else {
        echo "<p class='error-message'>No Posts to show.</p>";
    }
    ?>
</div>

</body>
</html>
