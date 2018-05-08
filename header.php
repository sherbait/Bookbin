<?php
// Initialize the session
session_start();
?>

<?php
require "./php/settings.php";
include "./php/bookpoints.php";
include "./php/userid.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bookbin | Swap Your Books Now</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img id="bookbin_logo" src="img/bookbin_logo_plain.png" alt="home"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Top Picks
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="highly_requested.php">Highly Requested Books</a></li>
                        <li><a href="recently_requested.php">Recently Requested Books</a></li>
                    </ul>
                </li>
                <?php
                if (isset($_SESSION["username"])) {
                    echo "<li class=\"dropdown\">
                    <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">My Lists
                        <span class=\"caret\"></span></a>
                    <ul class=\"dropdown-menu\">
                        <li><a href=\"trade_list.php\">Trade List</a></li>
                        <li><a href=\"wish_list.php\">Wish List</a></li>
                    </ul>
                </li>";
                }
                ?>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($_SESSION["username"])) {
                    // Dropdown for notification
                    echo "<li class='dropdown'>";
                    echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>";
                    echo "<span class='label label-pill label-danger count'></span>";
                    echo "Notification</a>";
                    echo "<ul class='dropdown-menu'></ul>";
                    echo "</li>";
                    // Links to user profile and button
                    echo "<li><a href='profile.php'><span class='glyphicon glyphicon-user'></span> {$_SESSION['username']}</a></li>";
                    echo "<li><a href='logout.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a></li>";
                } elseif ($_SERVER['REQUEST_URI'] === "/login.php" || $_SERVER['REQUEST_URI'] === "/register.php") {
                    // Don't show the nav bar during login or registration
                } else {
                    //echo "<li><a href='login.php' class='btn btn-success navbar-btn' role='button'>Login</a></li>";
                    //echo "<li><a href='register.php' class='btn btn-danger navbar-btn' role='button'>Register</a></li>";
                    echo "<li><a href='register.php'><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>";
                    echo "<li><a href='login.php'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>";
                }
                ?>
            </ul>
            <form class="navbar-form navbar-right" action="search.php">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search book by title/author" name="term">
                    </>
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>


<?php
/*// Display username and bookpoints if the user is logged in
if (isset($_SESSION["username"])) {

    echo "Hi, " . $_SESSION["username"] . ", BP: " . $_SESSION["bookpoint"] . " | ";

} elseif ($_SERVER['REQUEST_URI'] === "/login.php" || $_SERVER['REQUEST_URI'] === "/register.php") {
    // Don't show the nav bar during login or registration
} else {

    // Search bar: <search name= must be "term">
    echo "<form action='search.php'>";
    echo "<input type='search' placeholder='Search books by title, author, or ISBN' name='term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";
    // Login button
    echo "<a href='login.php'><input name='submit' value='Login' type='submit'></a>";
    // Register button
    echo "<a href='register.php'><input name='submit' value='Sign Up' type='submit'></a>";

}
*/?>
<!-- end header: </body> tag in footer.php -->