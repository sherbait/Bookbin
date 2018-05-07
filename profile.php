<?php
include "header.php";
include "./php/session.php";
include "./php/user_info.php";
?>

    <div class="container-fluid text-center">
        <div class="row content">
            <div class="col-sm-2 sidenav">
                <div class="list-group">
                    <a href="#" class="list-group-item active">My Profile</a>
                    <a href="update_account.php" class="list-group-item">Edit Personal Info</a>
                    <a href="update_password.php" class="list-group-item">Change Password</a>
                    <a href="trade_history.php" class="list-group-item">Trade History</a>
                </div>
            </div>
            <div class="col-sm-8 text-left">
                <div class="row-content">
                <h2><?php echo "{$first_name} {$middle_name} {$last_name}'s Profile" ?></h2>
                <div class="col-sm-2">
                    <img src="img/profile_placeholder.jpg" class="img-thumbnail img-responsive" alt="Profile Image" id="profile_thumb">
                </div>
                <div class="col-sm-10">
                    <form class="form-horizontal">
                            <label class="control-label col-sm-2" for="username">Username:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $username ?></p>
                            </div>
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $email ?></p>
                            </div>
                            <label class="control-label col-sm-2" for="bookpoint">Bookpoints:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $bookpoint ?></p>
                            </div>
                            <label class="control-label col-sm-2" for="date_created">Member Since:</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo date_format(date_create($created),"M Y") ?></p>
                            </div>
                    </form>
                </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>


<?php   include "footer.php"    ?>