<?php
include "header.php";
include "php/session.php";

/* Displays the trade list of the user.
 * TODO fix back-button form resend
 */
?>

<?php
// Variables for displaying messages to the user
$add_book_err = "";
$add_book = $delete_book = "";
$change_condition = $accept_trade = "";

$books = array();   #holds the books from the database that the user has added to their trade list, w/o pending transactions
$pending_books = array(); #stores the books with pending transactions the user has in their trade list
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];   #This is a unique identifier for this user in the database

// Handle the case when user adds or deletes a book via form
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Variables for adding or deleting a book
    $book_google_id = $book_title = $book_url = $condition = "";

    // Check if the user clicked the [Add to Trade List] button in search.php
    if (isset($_POST['edit_trade']) && $_POST['edit_trade'] === "I own this") {

        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];
        $book_url = urlencode($_POST['book_url']);
        $condition = "very good";   #default value

        // Check if the book exists in the user's trade list
        $sql = "SELECT * FROM (SELECT * FROM trade_item INNER JOIN trade_list ON trade_item.id=trade_list.trade_item_id
              WHERE trade_list.user_id=?) AS user_trade_list WHERE google_id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variables
            mysqli_stmt_bind_param($stmt, "is", $user_id, $book_google_id);
            // Attempt to execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Get the result
                $result = mysqli_stmt_get_result($stmt);

                if ($result->num_rows > 0) {    // Should be 1 row if book exists since book_google_id is unique
                    $add_book_err = "Book already exists in your trade list";
                }
            } else {
                echo "Something went wrong retrieving your book list. Please try again later.";
            }
        }

        // Check if the book exists in the user's wish list -- a book can only be in either trade or wish list
        $sql = "SELECT * FROM (SELECT * FROM wish_item INNER JOIN wish_list ON wish_item.id=wish_list.wish_item_id
              WHERE wish_list.user_id=?) AS user_wish_list WHERE google_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variables
            mysqli_stmt_bind_param($stmt, "is", $user_id, $book_google_id);
            // Attempt to execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Get the result
                $result = mysqli_stmt_get_result($stmt);

                if ($result->num_rows > 0) {    // Should be 1 row if book exists since book_google_id is unique
                    $add_book_err = "Book already exists in your wish list.";
                }
            } else {
                echo "Something went wrong retrieving your book list. Please try again later.";
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);

        // Book doesn't exist in the user's trade list or wish list so add it
        if (empty($add_book_err)) {

            mysqli_begin_transaction($conn);

            $sql = "INSERT into trade_item(google_id, title, url) VALUES(?,?,?)";
            // Store the trade_item.id generated from inserting into this table
            $trade_item_id = 0;

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $book_google_id, $book_title, $book_url);
                if (mysqli_stmt_execute($stmt)) {
                    $trade_item_id = mysqli_insert_id($conn);
                } else {
                    echo "@trade_item: Something went wrong adding {$book_title} in your trade list. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);

            $sql = "INSERT into trade_list(user_id, trade_item_id, `condition`) VALUES(?,?,?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "iis", $user_id, $trade_item_id, $condition);
                if (mysqli_stmt_execute($stmt)) {
                    $add_book = "Successfully added book.";
                } else {
                    echo "@trade_list: Something went wrong adding {$book_title} in your trade list. Please try again later.";
                }
            }
            mysqli_commit($conn);
            mysqli_stmt_close($stmt);
        }
    }

    // Check if user wants to delete an item from trade list
    if (isset($_POST['edit_trade']) && $_POST['edit_trade'] === "Delete from Trade List") {
        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];

        // Prepare the statement
        $sql = "DELETE ti, tl  FROM trade_item ti JOIN trade_list tl 
            ON ti.id = tl.trade_item_id WHERE ti.google_id=? AND tl.user_id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variable
            mysqli_stmt_bind_param($stmt, "si", $book_google_id, $user_id);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $delete_book = "Successfully deleted book.";
            } else {
                echo "Something went wrong deleting the book. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Check if user wants to change the condition of the book they own
    if (isset($_POST['condition'])) {
        // Prepare the UPDATE statement
        $sql = "UPDATE trade_items SET `condition`=? WHERE username=? AND google_id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {

            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_condition, $param_username, $param_google_id);
            $param_condition = $_POST['condition'];
            $param_username = $username;
            $param_google_id = $_POST['book_id'];

            // Attempt to execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $change_condition = "Successfully updated the condition of your book.";
            } else {
                echo "ERROR: Something went wrong. Please try again later.";
            }

        } else {
            echo "Failed to prepare";
        }
        mysqli_stmt_close($stmt);
    }

    // User wants to accept the trade
    if (isset($_POST['accept_trade'])) {
        $match_id = $_POST['match_id'];
        $google_id = $_POST['book_id'];
        $sender_id = $_POST['sender_id'];
        $receiver_id = $_POST['receiver_id'];

        // Execute the stored procedure that accepts this trade
        $sql = "CALL AcceptTrade(?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "isii",$match_id,$google_id, $sender_id, $receiver_id);
            if (mysqli_stmt_execute($stmt)) {
                $accept_trade = "Thank you for accepting the trade. 5 Bookpoints have been added to your account, 
                and another 5 will be added when the wisher receives the book.";
            } else
                echo "Failed to execute procedure.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Retrieve the updated user's trade list
$sql = "SELECT * FROM trade_item INNER JOIN trade_list ON trade_item.id=trade_list.trade_item_id
                WHERE trade_list.user_id=? ORDER BY trade_item.title ASC";    // query to get the user's trade list

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind the variables
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    // Attempt to execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Check how many books there are in the user's trade list
        if ($result->num_rows > 0) {
            // The user has a trade list, retrieve the book data
            while ($row = $result->fetch_assoc()) {
                // Store a book with no pending transaction in the regular book array
                if ($row['status'] === 0) {
                    $books[] = $row;
                } else  if ($row['status'] === 5) {
                    // don't add the book because 5 means it's a completed trade
                } else {
                    $pending_books[] = $row;
                }
            }
        }
    } else {
        echo "Something went wrong retrieving your book list. Please try again later.";
    }
}
// Close the statement
mysqli_stmt_close($stmt);
?>

<div class="container">
    <h2>Trade List</h2>
    <p>These are books you own and would like to find them a new home.</p>
    <!-- display any system message here -->
    <?php
    if (!empty($add_book_err)) {
        echo "<div class='alert alert-warning alert-dismissible'>";
        echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
        echo $add_book_err;
        echo "</div>";
    }
    if (!empty($add_book)) {
        echo "<div class='alert alert-success alert-dismissible'>";
        echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
        echo $add_book;
        echo "</div>";
    }
    if (!empty($delete_book)) {
        echo "<div class='alert alert-success alert-dismissible'>";
        echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
        echo $delete_book;
        echo "</div>";
    }
    if (!empty($change_condition)) {
        echo "<div class='alert alert-success alert-dismissible'>";
        echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
        echo $change_condition;
        echo "</div>";
    }
    if (!empty($accept_trade)) {
        echo "<div class='alert alert-success alert-dismissible'>";
        echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
        echo $accept_trade;
        echo "</div>";
    }
    if (count($pending_books) === 0 && count($books) === 0) {
        echo "<div class='alert alert-info'>Your trade list is empty. Add books by using the search bar above.</div>";
    }
    ?>
    <!-- display table for pending trades -->
    <?php
    if (count($pending_books) > 0) {
        echo "<table class='table table-striped table-responsive text-info'>";
        // Get this user's pending trade info
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
            }
        }

        echo "<div class='container text-center text-info'>";
        echo "<h3><strong>Pending Trades</strong></h3>";
        echo "<p>Good news! We have found matches to your trade books.</p>";
        echo "</div>";
        $count = 1;
        // Header
        echo "<thead>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Title</th>";
        echo "<th>Date Added</th>";
        echo "<th>Condition</th>";
        echo "<th>Status</th>";
        echo "<th>Time Left</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        // Content
        echo "<tbody>";
        foreach ($pending_trade_info as $pending_trade) {
            echo "<tr>";
            echo "<td>{$count}</td>";
            // Display title of the book
            echo "<td>{$pending_trade['title']}</td>";
            // Display date the book was added
            echo "<td>" . date_format(date_create($pending_trade['date_trader_added']), "m/d/y") . "</td>";
            // Display condition of the book
            echo "<td>{$pending_trade['send_condition']}</td>";
            // Display the status of the book, countdown timer, and user action if applicable, if user has not accepted the trade request
            if ($pending_trade['date_trader_accepted'] === NULL) {
                echo "<td>pending trade request</td>"; // The user needs to accept or reject the trade request first
                echo "<td><abbr title='This book will be removed from your trade list when timer ends'><span class='timer' data-end='";
                // Find the deadline time
                $match_date = date_create($pending_trade['date_matched']);
                date_add($match_date, date_interval_create_from_date_string("24 hours"));
                $end_date = date(DATE_RFC1123, date_timestamp_get($match_date));
                echo $end_date . "'></span></abbr></td>";
                // Display user action if timer has expired
                if ($match_date < date_create()) {
                    echo "<td class='text-danger'>expired</td>";     // doesn't show since item is auto-removed from user's trade list
                } else {
                    /*echo "<td><abbr title='Once accepted, you have 72 hours to upload a postage receipt'>accept</abbr> |
                                <abbr title='This book will be removed from your trade list'>reject</abbr></td>";*/
                    echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                               <span>
                                <input type='hidden' name='book_id' value='" . $pending_trade['google_id'] . "'>
                                <input type='hidden' name='match_id' value='" . $pending_trade['id'] . "'>
                                <input type='hidden' name='sender_id' value='" . $pending_trade['sender_id'] . "'>
                                <input type='hidden' name='receiver_id' value='" . $pending_trade['receiver_id'] . "'>
                                <abbr title='Accepting this trade request means sending this book away to its new home'>
                                    <input type='submit' class='btn btn-danger btn-xs' name='accept_trade' value='Accept'>
                                </abbr>
                               </span></form>
                             </td>";

                }
            } else {    // User has accepted the trade request, what does he need to do?
                // echo "<td>{$pending_trade['waybill_status']}</td>";
                echo "<td>trade accepted</td>";
                echo "<td>-</td>";
                echo "<td><div><button type='button' class='btn btn-info btn-xs' 
                    data-toggle='modal' data-target='#user_info' onclick='getUserInfo({$pending_trade['receiver_id']})'>Mail</button></div></td>";
            }
            echo "</tr>";
            $count++;
        }
        echo "</tbody>";
        echo "</table>";
        echo "<br><br>";
    }
    ?>
    <div class="container">
        <div class="modal fade" id="user_info" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Mailing Information</h4>
                        <small>This is the name, contact, and address of the user who requested for your book.</small>
                    </div>
                    <div class="modal-body">
                        <div id="user_mail_info"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- display table for unmatched trade list -->
    <?php
    if (count($books) > 0) {
        echo "<table class=\"table table-striped table-responsive\">";
        echo "<div class='container text-center'>";
        echo "<h3><strong>Books Owned</strong></h3>";
        echo "<p>We are waiting to find others who would like to read these books.</p>";
        echo "</div>";
        $count = 1;
        // Headers
        echo "<thead>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Title</th>";
        echo "<th>Date Added</th>";
        echo "<th class='text-center'>Condition</th>";
        echo "<th class='text-center'>Action</th>";
        echo "</tr>";
        echo "</thead>";
        // Content
        echo "<tbody>";
        foreach ($books as $book) {
            echo "<tr>";
            echo "<td>{$count}</td>";
            // Display title of the book
            echo "<td class='col-sm-5'><a href='" . urldecode($book['url']) . "' target='_blank'>{$book['title']}</a></td>";
            // Display date the book was added
            echo "<td>" . date_format(date_create($book['date_added']), "m/d/y") . "</td>";
            // Display condition of the book
            echo "<td class='col-sm-2'><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                            <div>
                                <input type='hidden' name='book_id' value='" . $book['google_id'] . "'>
                                <select class='form-control input-sm' name='condition' onchange='this.form.submit()'>";
            echo "<option value='new' ";
            if ($book['condition'] === 'new') echo "selected";
            echo ">new</option>";
            echo "<option value='like new' ";
            if ($book['condition'] === 'like new') echo "selected";
            echo ">like new</option>";
            echo "<option value='very good' ";
            if ($book['condition'] === 'very good') echo "selected";
            echo ">very good</option>";
            echo "<option value='good' ";
            if ($book['condition'] === 'good') echo "selected";
            echo ">good</option>";
            echo "<option value='acceptable' ";
            if ($book['condition'] === 'acceptable') echo "selected";
            echo ">acceptable</option>";
            echo "</select></div></form></td>";
            // Display delete button
            echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                            <div class='form-group'>
                                <input type='hidden' name='book_id' value='" . $book['google_id'] . "'>
                                <input type='hidden' name='book_title' value='" . $book['title'] . "'>
                                <input class='center-block' type='image' src='./img/delete.png' name='edit_trade'
                                value='Delete from Trade List' alt='delete'
                                title='Delete from Trade List'>
                                </div></form>
                             </td>";
            $count++;

        }
        echo "</tbody>";
        echo "</table>";
    }
    ?>
</div>

<?php
mysqli_close($conn);
include "footer.php"    ?>
