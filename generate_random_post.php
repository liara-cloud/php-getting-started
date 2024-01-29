<?php
include_once 'database_conf.php';

// Function to handle redirect
function redirectTo($location) {
    header("Location: $location");
    exit();
}

// Read data from JSON file
$json_data = file_get_contents('random_posts.json');
$posts = json_decode($json_data, true);

// Connect to the database (assuming $conn is already defined in database_conf.php)
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select a random post from the array
$random_post = $posts[array_rand($posts)];

$title = mysqli_real_escape_string($conn, $random_post['title']);
$content = mysqli_real_escape_string($conn, $random_post['content']);
$image = mysqli_real_escape_string($conn, $random_post['image']);

// Insert post into database
$sql = "INSERT INTO posts (title, content, image) VALUES ('$title', '$content', '$image')";

if ($conn->query($sql) === TRUE) {
    echo "New post inserted successfully: $title<br>";
    redirectTo("index.php");
} else {
    echo "Error inserting post: " . $conn->error . "<br>";
}

// Close database connection
mysqli_close($conn);
?>
