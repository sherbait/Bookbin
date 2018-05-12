<?php
$pending_trade_info = array();
$sql = "SELECT * FROM (SELECT
                 `match`.id,
                 `match`.date_matched,
                 `match`.date_trader_accepted,
                 `match`.date_wisher_accepted,
                 ti.google_id,
                 ti.title,
                 trade_list.date_added AS date_trader_added,
                 trade_list.`condition` as send_condition,
                 u1.username as sender,
                 trade_list.status as pending_trade,
                 wish_list.date_added as date_wisher_added,
                 wish_list.`condition` as receive_condition,
                 u2.username as receiver,
                 wish_list.status as pending_wish,
                 r.filename as waybill,
                 r.status as waybill_status,
                 r2.id as report_id,
                 r2.status as report_status
               FROM `match`
                 INNER JOIN user u1 on u1.id=`match`.trader_id
                 INNER JOIN user u2 on u2.id=`match`.wisher_id
                 INNER JOIN wish_item_match m on `match`.id = m.match_id
                 INNER JOIN trade_item_match m2 on `match`.id = m2.match_id
                 INNER JOIN wish_item i on m.wish_item_id = i.id
                 INNER JOIN wish_list on wish_list.wish_item_id=m.wish_item_id
                 INNER JOIN trade_item ti on m2.trade_item_id = ti.id
                 INNER JOIN trade_list on trade_list.trade_item_id=m2.trade_item_id
                 INNER JOIN receipt_match match2 on `match`.id = match2.match_id
                 INNER JOIN receipt r on match2.receipt_id = r.id
                 INNER JOIN report_match m3 on `match`.id = m3.match_id
                 INNER JOIN report r2 on m3.report_id = r2.id) as match_info WHERE receiver=? AND pending_wish=1";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pending_trade_info[] = $row;
            }
        }
    }
}