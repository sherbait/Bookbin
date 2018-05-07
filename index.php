<?php
include "header.php";
include "php/functions.php";
?>

<div class="container-fluid">
    <div class="jumbotron text-center mx-sm-5">
        <h1>What is Bookbin?</h1>
        <p>Bookbin is a Philippine-based book swapping platform for book lovers!</p>
        <blockquote>Got plenty of books you have already finished reading and willing to give away?<br>Why not trade them on Bookbin for books that you want to read?<BR>
            You don't need to pay for books. You only need to pay for shipping fees.</blockquote>


        <?php
        if (!isset($_SESSION["username"]) || empty($_SESSION["username"]))
        echo "<a href='register.php' class='btn btn-danger btn-lg' role='button'>Register</a>";
        ?>
    </div>
</div>

<div class="container-fluid">
    <div class="jumbotron text-center">
        <h1>How It Works</h1>
        <h2>Step 1: Create an account.</h2>
        <h2>Step 2: Search for boooks to add to your Traade and Wish List..</h2>
        <h2>Step 4: Find a match for your requested book.</h2>
    </div>
</div>

<?php
include "footer.php";
?>


