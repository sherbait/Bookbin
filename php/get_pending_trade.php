<?php
$pending_trade_info = array();
$sql = "SELECT * FROM match_info WHERE sender=? AND pending_trade!=0";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pending_trade_info[] = $row;
            }
        }
    } else {
        echo "Failed to prepare statement.";
    }
}