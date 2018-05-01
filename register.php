<?php
    include "header.php";
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

        if (empty($password)) {
            $password_err = "Password is required";
        } else {

            // Check if password is at least 8 characters long
            if (strlen($password) < 8) {
                $password_err = "Must be at least 8 characters";
            }
        }

        if (empty($confirm_password)) {
            $confirm_password_err = "Confirm password is required";
        } else {

            // Check if password entered is the same
            if ($password != $confirm_password) {
                $confirm_password_err = "Password does not match";
            }
        }

        if (empty($email)) {
            $email_err = "Email is required";
        } else {

            // Check if email address has the correct format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email format";
            }
        }

        if (empty($phone)) {
            $phone_err = "Cellphone/Landline is required";
        } else {

            // Check if phone number is all digits (e.g. 09171234567 or 029876543)
            if (!preg_match("/^[0-9]*$/", $phone)) {
                $phone_err = "Only numbers allowed";
            } else {
                // Check if phone number has max 11 digits and minimum 7
                if (strlen($phone) < 7 || strlen($phone) > 11) {
                    $phone_err = "Must be 7-11 digit phone number";
                }
            }
        }

        if (empty($first_name)) {
            $first_name_err = "First name is required";
        } else {

            // Check if name has only letters and spaces
            if (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
                $first_name_err = "Only letters and white space allowed";
            }
        }

        // Middle name is optional

        if (!preg_match("/^[a-zA-Z ]*$/", $middle_name)) {
            $middle_name_err = "Only letters and white space allowed";
        }

        if (empty($last_name)) {
            $last_name_err = "Last name is required";
        } else {

            if (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
                $last_name_err = "Only letters and white space allowed";
            }
        }

        // Address fields
        if (empty($address_no)) {
            $address_no_err = "House/Bldg/Unit No. is required";
        }
        if (empty($address_street)) {
            $address_street_err = "Street/Subdivision is required";
        }
        if (empty($address_city)) {
            $address_city_err = "City is required";
        }
        if (empty($address_province)) {
            $address_province_err = "Province is required";
        }
        if (empty($address_zip)) {
            $address_zip_err = "Zip/Postal code is required";
        } else {

            // Check if zip code is a 4-digit number
            if (!preg_match("/^[0-9]*$/", $address_zip)) {
                $address_zip_err = "4-digit zip code required";
            } else {
                if (strlen($address_zip) != 4) {
                    $address_zip_err = "Zip code must be 4 digits";
                }
            }
        }
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
                $param_address = "$address_no, "."$address_street, "."$address_city, "."$address_province ".$address_zip;
                $param_first_name = $first_name;
                $param_middle_name = $middle_name;
                $param_last_name = $last_name;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to home page
                    header("location: index.php");
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

    // Removes whitespaces and backslashes, also sanitizes data
    function clean_input($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
    <!-- SIGN UP FORM CODE HERE -->
    <div>
        <label>* Username:</label>
        <input type="text" name="username" value="<?php echo $username; ?>">
        <span class="error"><?php echo $username_err; ?></span>
    </div>
    <div>
        <label>* Password:</label>
        <input type="password" name="password" value="<?php echo $password; ?>">
        <span class="error"><?php echo $password_err; ?></span>
    </div>
    <div>
        <label>* Confirm Password:</label>
        <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
        <span class="error"><?php echo $confirm_password_err; ?></span>
    </div>
    <div>
        <label>* Email:</label>
        <input type="text" name="email" value="<?php echo $email; ?>">
        <span class="error"><?php echo $email_err; ?></span>
    </div>
    <div>
        <label>* First Name:</label>
        <input type="text" name="first_name" value="<?php echo $first_name; ?>">
        <span class="error"><?php echo $first_name_err; ?></span>
    </div>
    <div>
        <label>Middle Name:</label>
        <input type="text" name="middle_name" value="<?php echo $middle_name ?>">
        <span class="error"><?php echo $middle_name_err; ?></span>
    </div>
    <div>
        <label>* Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $last_name; ?>">
        <span class="error"><?php echo $last_name_err; ?></span>
    </div>
    <div>
        <label>* Phone:</label>
        <input type="text" name="phone" value="<?php echo $phone; ?>">
        <span class="error"><?php echo $phone_err; ?></span>
    </div>
    <div>
        <label>* House/Bldg./Unit No.:</label>
        <input type="text" name="address_no" value="<?php echo $address_no; ?>">
        <span class="error"><?php echo $address_no_err; ?></span>
    </div>
    <div>
        <label>* Street/Subdivision:</label>
        <input type="text" name="address_street" value="<?php echo $address_street; ?>">
        <span class="error"><?php echo $address_street_err; ?></span>
    </div>
    <div>
        <label>* City:</label>
        <input type="text" name="address_city" value="<?php echo $address_city; ?>">
        <span class="error"><?php echo $address_city_err; ?></span>
    </div>
    <div>
        <label>* Province:</label>
        <select name="address_province">
            <option value="Metro Manila">Metro Manila</option>
        </select>
        <span class="error"><?php echo $address_province_err; ?></span>
    </div>
    <div>
        <label>Zip Code:</label>
        <input type="text" name="address_zip" value="<?php echo $address_zip; ?>">
        <span class="error"><?php echo $address_zip_err; ?></span>
    </div>
    <div>
        <input type="submit" value="Submit">
        <input type="reset" value="Reset">
    </div>
    <p>Already have an account? <a href="login.php">Login</a></p>
</form>


<?php
    include "footer.php";
?>