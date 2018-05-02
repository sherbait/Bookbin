<?php
    include "header.php";
    include "./php/session.php";
    include "./php/user_info.php";
    include "./php/functions.php";
?>

<?php
    // Bookbin message
    $message = "";

    // Account variables
    $email_err = $phone_err = $address_err = $first_name_err = $middle_name_err = $last_name_err = "";

    // Process form data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Clean input
        $email = clean_input($_POST["email"]);
        $phone = clean_input($_POST["phone"]);
        $first_name = clean_input($_POST["first_name"]);
        $middle_name = clean_input($_POST["middle_name"]);
        $last_name = clean_input($_POST["last_name"]);
        $address = clean_input($_POST["address"]);

        // Validate input
        $email_err = validate_email($email);
        $phone_err = validate_phone($phone);
        $first_name_err = validate_fname($first_name);
        $middle_name_err = validate_mname($middle_name);
        $last_name_err = validate_lname($last_name);
        $address_err = validate_address($address);

        // If no errors, proceed to updating the database
        if (empty($email_err) && empty($phone_err) && empty($first_name_err) && empty($middle_name_err) &&
            empty($last_name_err) && empty($address_err)) {

            // Prepare the UPDATE statement
            $sql = "UPDATE user 
                    SET email=?, phone=?, address=?, first_name=?, middle_name=?, last_name=? 
                    WHERE username=?";

            if ($stmt = mysqli_prepare($conn, $sql)) {

                // Bind the parameters
                mysqli_stmt_bind_param($stmt, "sssssss", $param_email, $param_phone, $param_address,
                    $param_first_name, $param_middle_name, $param_last_name, $param_username);

                $param_email = $email;
                $param_phone = $phone;
                $param_address = $address;
                $param_first_name = $first_name;
                $param_middle_name = $middle_name;
                $param_last_name = $last_name;
                $param_username = $username;

                // Attempt to execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    $message = "Successfully updated information";
                } else {
                    echo "ERROR: Something went wrong updating the account. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }

?>
<h2>Update Account Information</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
    <?php echo $message; ?>
    <div>
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo $first_name; ?>">
        <span class="error"><?php echo $first_name_err; ?></span>
    </div>
    <div>
        <label>Middle Name:</label>
        <input type="text" name="middle_name" value="<?php echo $middle_name; ?>">
        <span class="error"><?php echo $middle_name_err; ?></span>
    </div>
    <div>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $last_name; ?>">
        <span class="error"><?php echo $last_name_err; ?></span>
    </div>
    <div>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>">
        <span class="error"><?php echo $email_err; ?></span>
    </div>
    <div>
        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $phone; ?>">
        <span class="error"><?php echo $phone_err; ?></span>
    </div>
    <div>
        <label>Address:</label>
        <input type="text" name="address" value="<?php echo $address; ?>">
        <span class="error"><?php echo $address_err; ?></span>
    </div>
    <div>
        <input type="submit" value="Submit">
    </div>
</form>

<?php   include "footer.php"    ?>


