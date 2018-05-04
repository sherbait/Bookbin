<?php
    include "header.php";
    include "./php/session.php";
    include "./php/user_info.php";
?>

<!-- Code here to display profile information. Select whichever is relevant for this page. -->
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-2">
            <ul class="navbar-nav ml-auto">
                <li><a href="#">Profile</a></li>
                <li><a href="update_account.php">Account Settings</a></li>
                <li><a href="#">Trade History</a></li>
                <li><a href="#">Notifications</a></li>
            </ul>
        </div>
        <div class="col-sm-10">
            <label>Username: </label><?php echo $username ?><br>
            <label>Bookpoints: </label><?php echo $bookpoint ?><br>
            <label>First Name: </label><?php echo $first_name ?><br>
            <label>Middle Name: </label><?php echo $middle_name ?><br>
            <label>Last Name: </label><?php echo $last_name ?><br>
            <label>Email: </label><?php echo $email ?><br>
            <label>Contact No: </label><?php echo $phone ?><br>
            <label>Address: </label><?php echo $address ?><br>
            <label>Member since: </label><?php echo date_format(date_create($created),"M Y") ?><br>
        </div>
        </div>
    </div>
</div>

<?php   include "footer.php"    ?>