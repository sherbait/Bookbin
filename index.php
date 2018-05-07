<?php
include "header.php";
include "php/functions.php";
?>

<div class="container-fluid">
    <div class="jumbotron text-center">
        <h1>What is Bookbin?</h1>
        <p>Bookbin is a Philippine-based book swapping platform for book lovers.</p>

        <!-- TODO Add more info here-->

        <?php
        if (!isset($_SESSION["username"]) || empty($_SESSION["username"]))
        echo "<a href='register.php' class='btn btn-danger btn-lg' role='button'>Register</a>";
        ?>
    </div>
</div>

<?php
include "footer.php";
?>


