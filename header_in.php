<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artistic Navigation Menu</title>
    <link rel="stylesheet" href="static/styles.css">
</head>
<body>

<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="navbar" id="navbar">
    <a href="dashboard.php" <?php if ($current_page == 'dashboard.php') echo 'class="active"'; ?>>Home</a>
    <a href="new_post.php" <?php if ($current_page == 'new_post.php') echo 'class="active"'; ?>>Add Post</a>
    <a href="profile.php" <?php if ($current_page == 'profile.php') echo 'class="active"'; ?>>Profile</a>
    <a href="logout.php" >Logout</a>
    <a href="javascript:void(0);" class="icon" onclick="toggleNavbar()">
        <i class="fa fa-bars"></i>
    </a>
</div>

<script>
    function toggleNavbar() {
        var navbar = document.getElementById("navbar");
        if (navbar.className === "navbar") {
            navbar.className += " responsive";
        } else {
            navbar.className = "navbar";
        }
    }
</script>

</body>
</html>
