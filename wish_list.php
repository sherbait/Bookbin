<?php
include "header.php";
include "./php/session.php";
?>

<?php
// Variables for displaying messages to the user
$add_book_err = "";
$add_book = $delete_book = $accept_book = "";

$books = array();   #holds the books from the database that the user has added to their wish list
$pending_books = array();   #holds the books from the user's wish list that user expects to receive
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];   #This is a unique identifier for this user in the database
$bookpoints = $_SESSION['bookpoint'];

// Handle the case when user adds or deletes a book via form
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Variables for adding or deleting a book
    $book_google_id = $book_title = $book_url = $condition = "";

    // Check if the user clicked the [Add to Wish List] button in search.php
    if (isset($_POST['edit_wish']) && $_POST['edit_wish'] === "I want to read this") {

        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];
        $book_url = $_POST['book_url'];
        $condition = "";   #default value

        // Check if the book exists in the user's wish list
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

        // Check if the book exists in the user's trade list -- a book can only be in either trade or wish list
        $sql = "SELECT * FROM (SELECT * FROM trade_item INNER JOIN trade_list ON trade_item.id=trade_list.trade_item_id
              WHERE trade_list.user_id=?) AS user_trade_list WHERE google_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variables
            mysqli_stmt_bind_param($stmt, "is", $user_id, $book_google_id);
            // Attempt to execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Get the result
                $result = mysqli_stmt_get_result($stmt);

                if ($result->num_rows > 0) {    // Should be 1 row if book exists since book_google_id is unique
                    $add_book_err = "Book already exists in your trade list.";
                }
            } else {
                echo "Something went wrong retrieving your book list. Please try again later.";
            }
        }

        // Check if the user has enough book points to request a book
        $sql = "SELECT count(username) AS book_count FROM wish_items WHERE username=? AND pending_wish=0";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $row = $result->fetch_assoc();  // There should only be 1 row
                // Calculate the minimum required BP to add this book
                $min = ($row['book_count'] * 10) + 10;
                if ($bookpoints < $min) {
                    $add_book_err = "Not enough bookpoints. Send a book to earn bookpoints or delete an existing book from your wish list.";
                }
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);

        // Book doesn't exist in the user's wish list or trade list so add it
        if (empty($add_book_err)) {

            // Create a transaction since we're inserting into 2 tables
            mysqli_begin_transaction($conn);

            $sql = "INSERT into wish_item(google_id, title, url) VALUES(?,?,?)";
            // Store the wish_item.id generated from inserting into this table
            $wish_item_id = 0;

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $book_google_id, $book_title, $book_url);
                if (mysqli_stmt_execute($stmt)) {
                    $wish_item_id = mysqli_insert_id($conn);
                } else {
                    echo "Something went wrong adding {$book_title}. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);

            $sql = "INSERT into wish_list(user_id, wish_item_id, `condition`) VALUES(?,?,?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "iis", $user_id, $wish_item_id, $condition);
                if (mysqli_stmt_execute($stmt)) {
                    $add_book = "Successfully added book.";
                } else {
                    echo "Something went wrong adding {$book_title}. Please try again later.";
                }
            }
            mysqli_commit($conn);
            mysqli_stmt_close($stmt);
        }
    } elseif (isset($_POST['edit_wish']) && $_POST['edit_wish'] === "Delete from Wish List") {
        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];

        // Prepare the statement
        $sql = "DELETE wi, wl  FROM wish_item wi JOIN wish_list wl 
            ON wi.id = wl.wish_item_id WHERE wi.google_id=? AND wl.user_id=?";

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
    }

    // User has confirmed that the book has arrived
    if (isset($_POST['accept_book'])) {
        $match_id = $_POST['match_id'];
        $sender_id = $_POST['sender_id'];

        // Execute the stored procedure that accepts this trade
        $sql = "CALL AcceptBook(?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii",$match_id,$sender_id);
            if (mysqli_stmt_execute($stmt)) {
                $accept_book = "Thank you for confirming. Enjoy your new book!";
            } else
                echo "Failed to execute procedure.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Retrieve the updated user's wish list
$sql = "SELECT * FROM wish_item INNER JOIN wish_list ON wish_item.id=wish_list.wish_item_id
                WHERE wish_list.user_id=? ORDER BY wish_item.title ASC";    // query to get the user's wish list

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind the variables
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    // Attempt to execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Check how many books there are in the user's wish list
        if ($result->num_rows > 0) {
            // The user has a wish list, retrieve the book data
            while ($row = $result->fetch_assoc()) {
                // Store a book with pending transaction
                if ($row['status'] === 1) {
                    $pending_books[] = $row;
                } else if ($row['status'] === 0) {
                    // Store the book
                    $books[] = $row;
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
    <h2>Wish List</h2>
    <p>These are books you would like to own and read.</p>
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
    if (!empty($accept_book)) {
        echo "<div class='alert alert-success alert-dismissible'>";
        echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
        echo $accept_book;
        echo "</div>";
    }
    if (count($pending_books) === 0 && count($books) === 0) {
        echo "<div class='alert alert-info'>Your wish list is empty. Add books by using the search bar above.</div>";
    }
    ?>
    <!-- display table for pending wishes -->
    <?php
    if (count($pending_books) > 0) {
        echo "<table class=\"table table-striped table-responsive text-info\">";
        // Get this user's pending trade info
        $pending_trade_info = array();
        $sql = "SELECT * FROM match_info WHERE receiver=? AND pending_wish=1";
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
        echo "<h3><strong>Matched Wishes</strong></h3>";
        echo "<p>Good news, your books are on their way!</p>";
        echo "</div>";
        $count = 1;
        // Header
        echo "<thead>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Title</th>";
        echo "<th>Date Added</th>";
        echo "<th>Status</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        // Content
        echo "<tbody>";
        foreach ($pending_trade_info as $pending_trade) {
            // Only display books that are in transit
            //if ($pending_trade['waybill_status'] === 'receipt accepted') {
            if ($pending_trade['pending_wish'] == 1) {
                echo "<tr>";
                echo "<td>{$count}</td>";
                // Display title of the book
                echo "<td>{$pending_trade['title']}</td>";
                // Display date the book was added
                echo "<td>" . date_format(date_create($pending_trade['date_wisher_added']), "m/d/y") . "</td>";
                // Display the status of the book
                if ($pending_trade['report_status'] === 'no report') {
                    echo "<td>in transit</td>";
                } else {
                    echo "<td>{$pending_trade['report_status']}</td>";
                }
                // Possible actions once user receives the book: accept, reject
                //echo "<td><abbr title='Book has arrived in good condition'>accept</abbr>";
                //echo "| <abbr title='Book has arrived in bad condition'>reject</abbr></td>";
                echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                               <span>
                                <input type='hidden' name='sender_id' value='" . $pending_trade['sender_id'] . "'>
                                <input type='hidden' name='match_id' value='" . $pending_trade['id'] . "'>
                                    <input type='submit' class='btn btn-danger btn-xs' name='accept_book' value='Accept'
                                    title='Confirm the book has arrived'>
                               </span></form>
                             </td>";
                echo "</tr>";
                $count++;
            }
        }
        echo "</tbody>";
        echo "</table><br><br>";
    }
    ?>


    <!-- display table for unmatched wish list -->
    <?php
    if (count($books) > 0) {
        echo "<table class=\"table table-striped table-responsive\">";
        echo "<div class='container text-center'>";
        echo "<h3><strong>Wishes</strong></h3>";
        echo "<p>We are waiting to find others who own these books.</p>";
        echo "</div>";
        $count = 1;
        // Headers
        echo "<thead>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Title</th>";
        echo "<th>Date Added</th>";
        //echo "<th>Condition on Arrival</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        // Content
        echo "<tbody>";
        foreach ($books as $book) {
            echo "<tr>";
            echo "<td>{$count}</td>";
            echo "<td class='col-md-5'><a href='" . urldecode($book['url']) . "' target='_blank'>{$book['title']}</a></td>";
            echo "<td>" . date_format(date_create($book['date_added']), "m/d/y") . "</td>";
           // echo "<td>{$book['condition']}</td>";
            echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                                <input type='hidden' name='book_id' value='" . $book['google_id'] . "'>
                                <input type='hidden' name='book_title' value='" . $book['title'] . "'>
                                <input type='image' src='./img/delete.png' name='edit_wish'
                                value='Delete from Wish List' alt='delete'
                                title='Delete from Wish List'>
                                </form>
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
