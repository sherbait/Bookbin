<?php
    // Initialize the session
    session_start();
?>

<?php
    require "./php/settings.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Trade your books now!</title>
    </head>
    <body>
        <header>
            <!-- code here -->
            <?php
                // Display username and password if the user is logged in
                if (isset($_SESSION["username"])) {
                    echo "<div>";
                    echo "Hi, " . $_SESSION["username"] . ", welcome to Bookbin! | ";
                    echo "<a href=\"logout.php\">Logout</a>";
                    echo "</div>";
                } else {
                    echo "<div>";
                    echo "<a href=\"login.php\">Login | </a>";
                    echo "<a href=\"register.php\">Register</a>";
                    echo "</div>";
                }
            ?>

        </header>
    <!-- end header -->