<?php

// Must be connected to database to use $conn variable

if (isset($_SESSION["username"])) {
    $sql = "SELECT bookpoint FROM user WHERE username=?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $_SESSION["username"];

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $bookpoints);
                if (mysqli_stmt_fetch($stmt)) {
                    $_SESSION["bookpoint"] = $bookpoints;
                }
            } else {
                echo "ERROR: Bookpoint is NULL";
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: Something went wrong in [bookpoint retrieval]";
    }
}
