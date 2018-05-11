<?php   include "header.php";   ?>

<?php
 if (isset($_SESSION['username'])) {
     header("location: index.php");
     exit;
 }

 ?>

<div>
    <h3>The page you are looking for is restricted for members only.</h3>
    <a href="register.php">Register</a> or <a href="login.php">Login</a> to access this feature.
</div>

<?php   include "footer.php";   ?>
