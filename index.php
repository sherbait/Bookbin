<?php
include "header.php";
include "php/functions.php";
?>

<div class="container-fluid">
    <div class="jumbotron text-center mx-sm-5">
        <h1>What is Bookbin?</h1>
        <p><strong>Bookbin is a Philippine-based online book swapping platform for book lovers!</strong></p>
        <h4>Got plenty of books you have already finished reading and are willing to give away?<br>
            Why not give them away on Bookbin in exchange for books that you want to read?<br>
                Join us for free today!
        </h4>
        <!--<a href="https://www.freepik.com/free-photos-vectors/background">Background vector created by Visnezh - Freepik.com</a>-->

        <?php
        if (!isset($_SESSION["username"]) || empty($_SESSION["username"]))
        echo "<a href='register.php' class='btn btn-danger btn-lg' role='button'>Register</a>";
        ?>
    </div>
</div>
<div class="container text-center">
    <h2>HOW IT WORKS</h2>
    <br>
    <div class="row">
        <div class="col-md-3">
            <span class="glyphicon glyphicon-search"></span>
            <p>Search for books to add to your Trade and Wish Lists.</p><br>
        </div>
        <div class="col-md-3">
            <span class="glyphicon glyphicon-bell"></span>
            <p>Get notified about trade requests for your books.</p><br>
        </div>
        <div class="col-md-3">
            <span class="glyphicon glyphicon-send"></span>
            <p>Mail your books and earn book points.</p><br>
        </div>
        <div class="col-md-3">
            <span class="glyphicon glyphicon-bitcoin"></span>
            <p>Use your book points to request for books in your Wish List.</p><br>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>


