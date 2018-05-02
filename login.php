<?php
include "header.php";
?>

<?php
// Redirect user to homepage, with an attempt to access the register page while logged in
if (isset($_SESSION["username"])) {
    header("location: index.php");
    exit;
}
?>

<?php
// User variables
$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check if user entered values
    if (empty($username)) {
        $username_err = "Please enter your username";
    }
    if (empty($password)) {
        $password_err = "Please enter your password";
    }

    // Authenticate the credentials
    if (empty($username_err) && empty($password_err)){
        // Prepare the statements: one for authentication, another for bookpoints retrieval
        $sql = "SELECT username, password FROM user WHERE username=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            // Attempt to execute statement
            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_store_result($stmt);

                // Username found in the database
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Check if the password is correct
                    mysqli_stmt_bind_result($stmt,$username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session with the user
                            //session_start();
                            $_SESSION["username"] = $username;

                            // No error encountered, redirect the user
                            header("location: index.php");
                        } else {
                            $password_err = "Invalid username/password";
                        }
                    }
                } else {
                    $username_err = "Username does not exist";
                }
            } else {
                echo "ERROR: Something went wrong in login. Please try again later.";
            }
        }
        // Close the statement
        mysqli_stmt_close($stmt);
    }
    // Close the connection
    mysqli_close($conn);
}

?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
    <label>Username: </label>
    <input id="form_input" name="username" placeholder="Username" type="text">
    <span class="error"><?php echo $username_err ?></span>
    <label>Password: </label>
    <input id="form_input" name="password" placeholder="Password" type="password">
    <span class="error"><?php echo $password_err ?></span>
    <input id="form_button" name="submit" value="Log in" type="submit">
    <p>Don't have an account? <a href="register.php">Register</a></p>
</form>


<?php include "footer.php"; ?>

