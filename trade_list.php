<?php
include "header.php";
include "./php/session.php";

/* Displays the trade list of the user.
 * TODO fix back-button form resend
 */
?>

<?php
// Variables for displaying messages to the user
$add_book_err = "";
$add_book = $delete_book = "";
$change_condition = "";

$books = array();   #holds the books from the database that the user has added to their trade list
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
                // Store the book
                $books[] = $row;
            }
        }
    } else {
        echo "Something went wrong retrieving your book list. Please try again later.";
    }
}
// Close the statement
mysqli_stmt_close($stmt);
mysqli_close($conn);
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
    ?>
    <!-- TODO add table for pending trades -->

    <!-- table for unmatched trade list -->
    <table class="table table-striped table-responsive">
        <?php
        if (count($books) > 0) {
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
                echo "<td class='col-md-5'><a href='" . urldecode($book['url']) . "' target='_blank'>{$book['title']}</a></td>";
                // Display date the book was added
                echo "<td>" . date_format(date_create($book['date_added']), "m/d/y") . "</td>";
                // Display condition of the book
                echo "<td class='col-md-2'><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                            <div class='form-group'>
                                <input type='hidden' name='book_id' value='" . $book['google_id'] . "'>
                                <select class='form-control' name='condition' onchange='this.form.submit()'>";
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
        } else {
            echo "<div class='alert alert-info'>Your trade list is empty. Add books by using the search bar above.</div>";
        }
        ?>
    </table>
</div>

<?php   include "footer.php"    ?>
