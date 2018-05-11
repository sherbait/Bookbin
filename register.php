<?php
include "header.php";
include "php/functions.php";
?>

<?php
// Redirect user to homepage, with an attempt to access the register page while logged in
if (isset($_SESSION["username"])) {
    header("location: profile.php");
    exit;
}
?>

<?php
// New user variables
$username = $email = $phone = $first_name = $middle_name = $last_name = "";
$password = $confirm_password = "";
$address_no = $address_street = $address_city = $address_province = $address_zip = "";
// These will store error messages
$username_err = $email_err = $phone_err = $first_name_err = $middle_name_err = $last_name_err = "";
$password_err = $confirm_password_err = "";
$address_no_err = $address_street_err = $address_city_err = $address_province_err = $address_zip_err = "";

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = clean_input($_POST["username"]);
    $password = clean_input($_POST["password"]);
    $confirm_password = clean_input($_POST["confirm_password"]);
    $email = clean_input($_POST["email"]);
    $phone = clean_input($_POST["phone"]);
    $first_name = clean_input($_POST["first_name"]);
    $middle_name = clean_input($_POST["middle_name"]);
    $last_name = clean_input($_POST["last_name"]);
    $address_no = clean_input($_POST["address_no"]);
    $address_street = clean_input($_POST["address_street"]);
    $address_city = clean_input($_POST["address_city"]);
    $address_province = clean_input($_POST["address_province"]);
    $address_zip = clean_input($_POST["address_zip"]);

    /* VALIDATION SECTION BEGINS*/
    if (empty($username)) {
        $username_err = "Username is required";
    } else {

        // Check if username contains only letters and numbers
        if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $username_err = "Only letters and numbers allowed";
        } else {
            // Check if username is between 6-20 characters long
            if (strlen($username) < 6 || strlen($username) > 20) {
                $username_err = "Must be 6-20 characters long";
            } else {
                // Prepare SELECT statement to check if username exists in the database
                $sql = "SELECT id FROM user WHERE username=?";

                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind username parameter
                    mysqli_stmt_bind_param($stmt, "s", $param_username);
                    // Set parameter
                    $param_username = $username;
                    // Attempt to execute statement
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);
                        // Query returned a row so the username exists
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            $username_err = "This username is no longer available";
                        }
                    } else {
                        echo "ERROR: Something went wrong in [username db check]. Please try again later.";
                    }
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    $password_err = validate_password($password);
    $confirm_password_err = confirm_password($password, $confirm_password);
    $email_err = validate_email($email);
    $phone_err = validate_phone($phone);
    $first_name_err = validate_fname($first_name);
    $middle_name_err = validate_mname($middle_name);
    $last_name_err = validate_lname($last_name);
    $address_no_err = validate_address_no($address_no);
    $address_street_err = validate_address_street($address_street);
    $address_city_err = validate_address_city($address_city);
    $address_province_err = validate_address_province($address_province);
    $address_zip_err = validate_address_zip($address_zip);
    /* VALIDATION SECTION ENDS */

    // Check if there are errors generated before inserting into database
    if (empty($username_err) && empty($email_err) && empty($phone_err) && empty($first_name_err) &&
        empty($middle_name_err) && empty($last_name_err) && empty($password_err) && empty($confirm_password_err)
        && empty($address_no_err) && empty($address_street_err) && empty($address_city_err) &&
        empty($address_province_err) && empty($address_zip_err)) {

        // Prepare INSERT statement
        $sql = "INSERT INTO user(username, password, email, phone, address, first_name, middle_name, last_name) 
                    VALUES(?,?,?,?,?,?,?,?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to prepared statement parameters
            mysqli_stmt_bind_param($stmt, "ssssssss",
                $param_username,
                $param_password,
                $param_email,
                $param_phone,
                $param_address,
                $param_first_name,
                $param_middle_name,
                $param_last_name);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;
            $param_phone = $phone;
            // TODO: separate address values into tables
            $param_address = "$address_no, "."$address_street, "."$address_city, "."$address_province ".$address_zip;
            $param_first_name = $first_name;
            $param_middle_name = $middle_name;
            $param_last_name = $last_name;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to home page
                header("location: login.php");
            } else {
                echo "ERROR: Something went wrong in [create new user]. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close db connection
    mysqli_close($conn);
}
?>

    <div class = "container">
    <div class="panel panel-default">
    <div class="panel-body">
        <h2 class="col-sm-offset-1">New Account</h2>
        <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <div class="form-group">
                <label class="control-label col-sm-2" for="username">Username:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="username" placeholder="Required" name="username"
                           data-toggle="tooltip" data-placement="right" title="6-20 letters or numbers only"
                           value="<?php echo $username?>">
                </div>
                <?php
                if (!empty($username_err))
                    echo "<span class='alert alert-danger'>{$username_err}</span>";
                ?>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="password">Password:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="password" placeholder="Required" name="password"
                           data-toggle="tooltip" data-placement="right" title="At least 8 letters or numbers"
                           value="<?php echo $password?>">
                </div>
                <?php
                if (!empty($password_err))
                    echo "<span class='alert alert-danger'>{$password_err}</span>";
                ?>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="confirm_password">Confirm Password:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="confirm_password" placeholder="Required" name="confirm_password"
                           data-toggle="tooltip" data-placement="right" title="Re-enter your password"
                           value="<?php echo $confirm_password?>">
                </div>
                <?php
                if (!empty($confirm_password_err))
                    echo "<span class='alert alert-danger'>{$confirm_password_err}</span>";
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
                <label class="control-label col-sm-2" for="address_no">House/Bldg./Unit No.:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="address_no" placeholder="Required" name="address_no"
                           data-toggle="tooltip" data-placement="right" title="N/A if none"
                           value="<?php echo $address_no?>">
                </div>
                <?php
                if (!empty($address_no_err))
                    echo "<span class='alert alert-danger'>{$address_no_err}</span>";
                ?>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="address_street">Street/Subdivision:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="address_street" placeholder="Required" name="address_street"
                           value="<?php echo $address_street?>">
                </div>
                <?php
                if (!empty($address_street_err))
                    echo "<span class='alert alert-danger'>{$address_street_err}</span>";
                ?>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="address_city">City:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="address_city" placeholder="Required" name="address_city"
                           value="<?php echo $address_city?>">
                </div>
                <?php
                if (!empty($address_city_err))
                    echo "<span class='alert alert-danger'>{$address_city_err}</span>";
                ?>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="address_province">Province:</label>
                <div class="col-sm-6">
                    <select class="form-control" id="address_province" name="address_province">
                        <option>Metro Manila</option>
                    </select>
                </div>
                <?php
                if (!empty($address_province_err))
                    echo "<span class='alert alert-danger'>{$address_province_err}</span>";
                ?>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="address_zip">Zip code:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="address_zip" placeholder="Required" name="address_zip"
                           value="<?php echo $address_zip?>">
                </div>
                <?php
                if (!empty($address_zip_err))
                    echo "<span class='alert alert-danger'>{$address_zip_err}</span>";
                ?>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-10">
                    <input class="btn btn-default" type="submit" value="Submit">
                    <input class="btn btn-default" type="reset" value="Reset">
                </div>
            </div>
            <div class="text-center">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>
    </div>
    </div>

<?php include "footer.php"; ?>