<?php
    include "header.php";
    include "./php/session.php";
    include "./php/user_info.php";
?>
    <div class="container-fluid text-center">
        <div class="row content">
            <div class="col-sm-2 sidenav">
                <div class="list-group">
                    <a href="profile.php" class="list-group-item">My Profile</a>
                    <a href="#" class="list-group-item">Edit Personal Info</a>
                    <a href="update_password.php" class="list-group-item">Change Password</a>
                    <a href="trade_history.php" class="list-group-item active">Trade History</a>
                </div>
            </div>
            <div class="col-sm-10 text-left">
                <h2>Trade History</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Transaction Date</th>
                            <th scope="col">Earned</th>
                            <th scope="col">Spent</th>
                            <th scope="col">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            // TODO retrieve trade history here

                            $count = 1;
                            foreach ($completed_trades as $completed_trade)
                            {
                                echo "<th scope='row'>{$count}</th>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>

<?php   include "footer.php"    ?>