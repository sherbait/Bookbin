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

<div class="container-fluid text-center">
    <div class="row content">
        <div class="col-sm-2 sidenav">
            <div class="list-group">
                <a href="profile.php" class="list-group-item">My Profile</a>
                <a href="#" class="list-group-item active">Edit Personal Info</a>
                <a href="update_password.php" class="list-group-item">Change Password</a>
                <a href="trade_history.php" class="list-group-item">Trade History</a>
            </div>
        </div>
        <div class="col-sm-10 text-left">
            <h2>Update Personal Information</h2>
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
                    <label class="control-label col-sm-2" for="first_name">First Name:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="first_name" placeholder="Required" name="first_name"
                               value="<?php echo $first_name?>">
                    </div>
                    <?php
                    if (!empty($first_name_err))
                        echo "<span class='alert alert-danger'>{$first_name_err}</span>";
                    ?>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="middle_name">Middle Name:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="middle_name" placeholder="Optional" name="middle_name"
                               value="<?php echo $middle_name?>">
                    </div>
                    <?php
                    if (!empty($middle_name_err))
                        echo "<span class='alert alert-danger'>{$middle_name_err}</span>";
                    ?>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="last_name">Last Name:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="last_name" placeholder="Required" name="last_name"
                               value="<?php echo $last_name?>">
                    </div>
                    <?php
                    if (!empty($last_name_err))
                        echo "<span class='alert alert-danger'>{$last_name_err}</span>";
                    ?>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="email" placeholder="Required" name="email"
                               value="<?php echo $email?>">
                    </div>
                    <?php
                    if (!empty($email_err))
                        echo "<span class='alert alert-danger'>{$email_err}</span>";
                    ?>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="phone">Cell Phone / Landline:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="phone" placeholder="Required" name="phone"
                               data-toggle="tooltip" data-placement="right" title="Numbers only"
                               value="<?php echo $phone?>">
                    </div>
                    <?php
                    if (!empty($phone_err))
                        echo "<span class='alert alert-danger'>{$phone_err}</span>";
                    ?>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="address">Address:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="address" placeholder="Required" name="address"
                               value="<?php echo $address?>">
                    </div>
                    <?php
                    if (!empty($address_err))
                        echo "<span class='alert alert-danger'>{$address_err}</span>";
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


