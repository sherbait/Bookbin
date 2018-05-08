<?php
include "header.php";
include "php/functions.php";
?>

<div class="container-fluid">
    <div class="jumbotron text-center mx-sm-5">
        <h1>What is Bookbin?</h1>
        <p>Bookbin is a Philippine-based online book swapping platform for book lovers!</p>
        <blockquote>Got plenty of books you have already finished reading and are willing to give away?<br>
            Why not give them away on Bookbin in exchange for books that you want to read?<br>
            Join us for free!</blockquote>


        <?php
        if (!isset($_SESSION["username"]) || empty($_SESSION["username"]))
        echo "<a href='register.php' class='btn btn-danger btn-lg' role='button'>Register</a>";
        ?>
    </div>
</div>

<div class="container-fluid">
    <div class="row text-center">
        <h2>How It Works</h2>
        <h3>1: Search for books to add to your Trade and Wish Lists.</h3>
        <h3>2: Get notified about trade requests for your books.</h3>
        <h3>3: Mail your books and earn book points.</h3>
        <h3>4: Use your book points to request for books in your Wish List.</h3>
    </div>
</div>

<?php
include "footer.php";
?>


