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
    <a href="index.php" <?php if ($current_page == 'index.php') echo 'class="active"'; ?>>Home</a>
    <a href="login.php" <?php if ($current_page == 'login.php') echo 'class="active"'; ?>>Login</a>
    <a href="sign_up.php" <?php if ($current_page == 'sign_up.php') echo 'class="active"'; ?>>Sign Up</a>
    <a href="generate_random_post.php" <?php if ($current_page == 'generate_random_post.php') echo 'class="active"'; ?>>Generate Random Post</a>
    <a href="about.php" <?php if ($current_page == 'about.php') echo 'class="active"'; ?>>About Us</a>
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
