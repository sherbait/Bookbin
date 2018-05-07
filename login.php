<?php
include "header.php";
?>

<?php
// Redirect user to homepage, with an attempt to access the login page while logged in
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
        $username_err = "Please enter your username.";
    }
    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    // Authenticate the credentials
    if (empty($username_err) && empty($password_err)){
        // Prepare the statements: one for authentication, another for bookpoints retrieval
        $sql = "SELECT username, password FROM user WHERE BINARY username=?";

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
                            $password_err = "Invalid username and/or password.";
                        }
                    }
                } else {
                    $username_err = "Invalid username and/or password.";
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


<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <h2 class="col-sm-offset-1">Login</h2>
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
                <?php
                if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === '/register.php') {
                    echo "<div class='alert alert-success'>You have successfully created an account.</div>";
                }
                if (!empty($username_err)) {
                    echo "<div class='alert alert-danger'>{$username_err}</div>";
                }
                if (!empty($password_err)) {
                    echo "<div class='alert alert-danger'>{$password_err}</div>";
                }
                ?>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="username">Username:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" value="<?php echo $username?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2"  for="password">Password:</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" value="<?php echo $password?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input class="btn btn-default" type="submit" value="Submit">
                    </div>
                </div>
                <div class="text-center">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include "footer.php"; ?>


