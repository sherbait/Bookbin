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

<div class="container-fluid text-center">
    <div class="row content">
        <div class="col-sm-2 sidenav">
            <div class="list-group">
                <a href="profile.php" class="list-group-item">My Profile</a>
                <a href="#" class="list-group-item">Edit Personal Info</a>
                <a href="update_password.php" class="list-group-item active">Change Password</a>
                <a href="trade_history.php" class="list-group-item">Trade History</a>
            </div>
        </div>
        <div class="col-sm-10 text-left">
            <h2>Change Password</h2>
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
                <?php
                if (!empty($message)) {
                    echo "<div class='form-group'></div>";
                    echo "<div class='form-group'>";
                    echo "<span class='alert alert-success col-sm-offset-4'>{$message}</span>";
                    echo "</div>";
                    echo "<div class='form-group'></div>";
                }
                ?>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="old_password">Old Password:</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="old_password"
                               placeholder="Enter old password" name="old_password">
                    </div>
                    <?php
                    if (!empty($old_password_err))
                        echo "<span class='alert alert-danger'>{$old_password_err}</span>";
                    ?>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="new_password">New Password:</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="new_password"
                               placeholder="Enter new password" name="new_password">
                    </div>
                    <?php
                    if (!empty($new_password_err))
                        echo "<span class='alert alert-danger'>{$new_password_err}</span>";
                    ?>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="confirm_new_password">Confirm New Password:</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="confirm_new_password"
                               placeholder="Enter new password again" name="confirm_new_password">
                    </div>
                    <?php
                    if (!empty($confirm_new_password_err))
                        echo "<span class='alert alert-danger'>{$confirm_new_password_err}</span>";
                    ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-10">
                        <input class="btn btn-default" type="submit" value="Submit">
                        <input class="btn btn-default" type="reset" value="Reset">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>

<?php   include "footer.php"    ?>


