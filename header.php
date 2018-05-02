<?php
// Initialize the session
session_start();
?>

<?php
require "./php/settings.php";
include "./php/bookpoints.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/bookbin_stylesheet.css">
    <title>Bookbin</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php"><img id="bookbin_logo" src="img/bookbin_logo_plain.png" class="d-inline-block align-top" alt="bookbin"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    // Display username and bookpoints if the user is logged in
    echo $_SERVER['REQUEST_URI'];

    if (isset($_SESSION["username"])) {
        echo "<div class=\"collapse navbar-collapse\" id=\"navbarNavAltMarkup\">";
        echo "<ul class=\"navbar-nav mr-auto mt-2 mt-lg-0\">";
        echo "<li class=\"nav-item\">";
        echo "<a class=\"nav-item nav-link\" href=\"trade.php\">Trade</a>";
        echo "</li>";
        echo "<li class=\"nav-item\">";
        echo "<a class=\"nav-item nav-link\" href=\"wish.php\">Wish</a>";
        echo "</li>";
        echo "</ul>";
        echo "<form class=\"form-inline my-2 my-lg-0\" action='search.php' id='form_search'>";
        echo "<input class=\"form_input\" type='search' placeholder=\"Search books...\" aria-label=\"Search\" name='term' id='form_search_term'>";
        echo "<button class=\"form_button\" type=\"submit\">Search</button>";
        echo "</form>";
        echo "<span class=\"navbar-text\">";
        echo "Hi, " . $_SESSION["username"] . ", BP: " . $_SESSION["bookpoint"] . " | ";
        echo "</span>";
        echo "<a href=\"profile.php\"><img class=\"nav_icon\" src=\"img/profile_placeholder.jpg\" alt=\"\profile pic\"></a>";
        echo "<a href=\"notifications.php\"><img class=\"nav_icon\" src=\"img/bell2.png\" alt=\"\notification\"></a>";
        echo "<a class=\"nav-item nav-link\" href=\"logout.php\"><input class=\"form_button\" name=\"submit\" value=\"Log Out\" type=\"submit\"></a>";
        echo "</div>";
    } elseif ($_SERVER['REQUEST_URI'] === "/login.php" || $_SERVER['REQUEST_URI'] === "/register.php") {
        // Don't show the nav bar during login or registration
    } else {
        echo "<div class=\"navbar-nav\">";
        echo "<a class=\"nav-item nav-link\" href=\"login.php\"><input id=\"form_button\" name=\"submit\" value=\"Log In\" type=\"submit\"></a>";
        echo "<a class=\"nav-item nav-link\" href=\"register.php\"><input id=\"form_button\" name=\"submit\" value=\"Sign Up\" type=\"submit\"></a>";
        echo "</div>";
    }
    ?>
</nav>
<!-- end header: </body> tag in footer.php -->