<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artistic Navigation Menu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="navbar" id="navbar">
    <a href="#" class="active">Home</a>
    <a href="login.php">Login</a>
    <a href="sign_up.php">Sign Up</a>
    <a href="#">Contact</a>
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
