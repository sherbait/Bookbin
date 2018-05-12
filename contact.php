<?php
include "header.php";
?>

<?php   // SRC: http://www.freecontactform.com/email_form.php

// System message variables
$first_name_err = $last_name_err = $email_err = $comments_err = $telephone_err = "";

if(isset($_POST['email'])) {

    // EDIT THE 2 LINES BELOW AS REQUIRED
    $email_to = "support@bookbin.net";
    $email_subject = "Contact Form Submission";

    if (!isset($_POST['first_name'])) {
        $first_name_err = "Required";
    }
    if (!isset($_POST['last_name'])) {
        $last_name_err = "Required";
    }
    if (!isset($_POST['email'])) {
        $email_err = "Required";
    }
    if (!isset($_POST['comments'])) {
        $comments_err = "Required";
    }

    $first_name = $_POST['first_name']; // required
    $last_name = $_POST['last_name']; // required
    $email_from = $_POST['email']; // required
    $telephone = $_POST['telephone']; // not required
    $comments = $_POST['comments']; // required

    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email_from)) {
        $email_err = 'The Email Address you entered does not appear to be valid.';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $first_name)) {
        $first_name_err = 'The First Name you entered does not appear to be valid.';
    }

    if (!preg_match($string_exp, $last_name)) {
        $last_name_err = 'The Last Name you entered does not appear to be valid.';
    }

    if (strlen($comments) < 2) {
        $comments_err = 'The Comments you entered do not appear to be valid.';
    }

    if (empty($first_name_err) && empty($last_name_err) && empty($telephone_err) && empty($email_err) && empty($comments_err)) {
        $email_message = "Form details below.\n\n";

        function clean_string($string)
        {
            $bad = array("content-type", "bcc:", "to:", "cc:", "href");
            return str_replace($bad, "", $string);
        }


        $email_message .= "First Name: " . clean_string($first_name) . "\n";
        $email_message .= "Last Name: " . clean_string($last_name) . "\n";
        $email_message .= "Email: " . clean_string($email_from) . "\n";
        $email_message .= "Telephone: " . clean_string($telephone) . "\n";
        $email_message .= "Comments: " . clean_string($comments) . "\n";

        // create email headers
        $headers = 'From: ' . $email_from . "\r\n" .
            'Reply-To: ' . $email_from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($email_to, $email_subject, $email_message, $headers);

        echo "<h4 class='container text-center'>Thank you for contacting us. We will be in touch with you very soon.</h4>";
        die();
    }
}
?>


<div class="container">
    <h2>Contact Us</h2>
    <p>We would like to hear from you!</p>
    <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" name="contactform">
        <div class="form-group">
            <label class="control-label col-sm-2" for="first_name">First Name:</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="first_name" placeholder="Required" name="first_name">
            </div>
            <?php
            if (!empty($first_name_err))
                echo "<span class='alert alert-danger'>{$first_name_err}</span>";
            ?>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="last_name">Last Name:</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="last_name" placeholder="Required" name="last_name">
            </div>
            <?php
            if (!empty($last_name_err))
                echo "<span class='alert alert-danger'>{$last_name_err}</span>";
            ?>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="email" placeholder="Required" name="email">
            </div>
            <?php
            if (!empty($email_err))
                echo "<span class='alert alert-danger'>{$email_err}</span>";
            ?>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="telephone">Phone Number:</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="telephone" placeholder="Optional" name="telephone">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="comments">Comments:</label>
            <div class="col-sm-5">
                <textarea class="form-control" name="comments" maxlength="1000" cols="25" rows="6" placeholder="Required"></textarea>
            </div>
            <?php
            if (!empty($comments_err))
                echo "<span class='alert alert-danger'>{$comments_err}</span>";
            ?>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input class="btn btn-default" type="submit" value="Submit">
            </div>
        </div>
    </form>
</div>
