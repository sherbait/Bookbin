<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    </head>
    <body>

<?php
include "php/settings.php";

$q = intval($_GET['q']);

$sql = "SELECT first_name, last_name, phone, address FROM user WHERE id=?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i",$q);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               // echo "<div class='form-group'>";
                echo "<div class='form-group'>";
                echo "<span><label class='control-label col-sm-3'>First Name: </label>{$row['first_name']}<br>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label class='control-label col-sm-3'>Last Name: </label>{$row['last_name']}<br>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label class='control-label col-sm-3'>Phone: </label>{$row['phone']}<br>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label class='control-label col-sm-3'>Address: </label>{$row['address']}";
                echo "</div>";
            }
        }
    }
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

</body>
</html>
