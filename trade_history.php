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
    <div class="col-sm-8 container text-left">
        <h2>Trade History</h2>
        <p>You will find your past book swaps here.</p>
        <?php
        $trades = array();
        // Retrieve trade history of this user
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM trade_history WHERE receiver_id = ? OR sender_id = ? ORDER BY date_completed DESC";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii",$user_id, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $trades[] = $row;
                    }
                }
            } else {
                echo "Failed to execute statement";
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if (count($trades) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>#</th>";
            echo "<th>Title</th>";
            echo "<th>Transaction Date</th>";
            echo "<th>BP Earned</th>";
            echo "<th>BP Spent</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $count = 1;
            foreach ($trades as $trade)
            {
                echo "<tr>";
                echo "<td>{$count}</td>";
                echo "<td>{$trade['book_title']}</td>";
                echo "<td>" . date_format(date_create($trade['date_completed']), "m/d/y") . "</td>";
                if ($trade['sender_id'] === $user_id) {
                    echo "<td class='text-success'>{$trade['bp_exchanged']}</td>";
                    echo "<td>-</td>";
                } else {
                    echo "<td>-</td>";
                    echo "<td class='text-danger'>{$trade['bp_exchanged']}</td>";
                }
                echo "</tr>";
                $count++;
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-info'>Your trade history is empty.</div>";
        }
        ?>
    </div>
    <div class="col-sm-2"></div>
    </div>
    </div>

<?php   include "footer.php"    ?>