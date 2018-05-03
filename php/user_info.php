<?php
/**
 * Retrieves all user info from the database and stores them in variables, except $username and $bookpoint
 * which are stored in the SESSION. Must be connected to the database to use.
 */

// User account variables
$username = $_SESSION["username"];
$bookpoint = $_SESSION["bookpoint"];
$email = $phone = $address  = $first_name = $middle_name = $last_name = $created = "";
// Password stored in the database is hashed
// Call password_verify() to compare password entered by the user to the database password
$hashed_password = "";

// Prepare statement
$sql = "SELECT password, email, phone, address, first_name, middle_name, last_name, created FROM user WHERE username=?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind the variables
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {

        // Get the resulting row containing user profile info
        $result = mysqli_stmt_get_result($stmt);

        // There should only be one row for the user
        if ($result->num_rows === 1) {

            // Fetch the row as an associative array
            $row = $result->fetch_assoc();

            // Set the variables
            $hashed_password = $row['password'];
            $email = $row['email'];
            $phone = $row['phone'];
            $address = $row['address'];
            $first_name = $row['first_name'];
            $middle_name = $row['middle_name'];
            $last_name = $row['last_name'];
            $created = $row['created'];
        }
    }
} else {
    echo "ERROR: Something went wrong in profile. Please try again later.";
}

// Close the statement
mysqli_stmt_close($stmt);