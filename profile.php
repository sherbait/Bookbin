<?php
    include "header.php";
    include "./php/session.php";
    include "./php/user_info.php";
    include "profile_sidebar.php";
?>

<!-- Code here to display profile information. Select whichever is relevant for this page. -->
<div class="container">
    <div class="row">
        <div class="col-sm-2"></div>
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


<?php   include "footer.php"    ?>