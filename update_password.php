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
    $old_password = $new_password = $confirm_new_password = "";
    $old_password_err = $new_password_err = $confirm_new_password_err = "";

    // Process form data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Clean input
        $old_password = clean_input($_POST["old_password"]);
        $new_password = clean_input($_POST["new_password"]);
        $confirm_new_password = clean_input($_POST["confirm_new_password"]);

        // Validate input
        $old_password_err = validate_password($old_password);
        $new_password_err = validate_password($new_password);
        $confirm_new_password_err = confirm_password($new_password, $confirm_new_password);

        // If no errors, proceed to updating the database
        if (empty($old_password_err) && empty($new_password_err) && empty($confirm_new_password_err)) {

            // Prepare the UPDATE statement
            $sql = "UPDATE user SET password=? WHERE username=?";

            if ($stmt = mysqli_prepare($conn, $sql)) {

                // Bind the parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_username = $username;

                // Check if the old password matches the password in the database
                if (password_verify($old_password, $hashed_password)) {
                    // Attempt to execute the statement
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "Successfully updated information";
                    } else {
                        echo "ERROR: Something went wrong updating the account. Please try again later.";
                    }
                } else {
                    $old_password_err = "Incorrect password";
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }

?>
<h2>Change Password</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
    <?php echo $message; ?>
    <div>
        <label>Old Password:</label>
        <input type="password" name="old_password">
        <span class="error"><?php echo $old_password_err; ?></span>
    </div>
    <div>
        <label>New Password:</label>
        <input type="password" name="new_password">
        <span class="error"><?php echo $new_password_err; ?></span>
    </div>
    <div>
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_new_password">
        <span class="error"><?php echo $confirm_new_password_err; ?></span>
    </div>
    <div>
        <input type="submit" value="Submit">
    </div>
</form>

<?php   include "footer.php"    ?>


