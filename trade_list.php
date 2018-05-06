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

$books = array();   #holds the books from the database that the user has added to their trade list
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];   #This is a unique identifier for this user in the database

// Handle the case when user adds or deletes a book via form
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Variables for adding or deleting a book
    $book_google_id = $book_title = $condition = "";

    // Check if the user clicked the [Add to Trade List] button in search.php
    if ($_POST['edit_trade'] === "Add to Trade List") {

        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];
        $condition = "very good";   #default value TODO ask user to set this

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
                    echo $book_google_id;
                    echo $user_id;
                    echo $result->num_rows;
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

            $sql = "INSERT into trade_item(google_id, title) VALUES(?,?)";
            // Store the trade_item.id generated from inserting into this table
            $trade_item_id = 0;

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $book_google_id, $book_title);
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
                    $add_book = "Successfully added";
                } else {
                    echo "@trade_list: Something went wrong adding {$book_title} in your trade list. Please try again later.";
                }
            }
            mysqli_commit($conn);
            mysqli_stmt_close($stmt);
        }
    } elseif ($_POST['edit_trade'] === "Delete from Trade List") {
        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];

        // Prepare the statement
        $sql = "DELETE FROM trade_item WHERE google_id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variable
            mysqli_stmt_bind_param($stmt, "s", $book_google_id);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $delete_book = "Sucessfully deleted";
            } else {
                echo "Something went wrong deleting the book. Please try again later.";
            }
        }
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

<h2>Trade List</h2>

<div>
    <!-- display any system messages here -->
    <span class="error"><?php echo $add_book_err ?></span>
    <span class="message"><?php echo $add_book ?></span>
    <span class="message"><?php echo $delete_book ?></span>

    <!-- display trade list table here -->
    <div>
        <?php   // Populate the trade list table
            if (count($books) > 0) {
                echo "Books in trade list: " . count($books);
                echo "<table class='table table-striped'>";
                echo "<thead>";
                echo "<tr><th scope='col'>Title</th><th scope='col'>Date Added</th><th scope='col'>Condition</th><th scope='col'>Action</th></tr>";
                echo "</thead>";
                foreach ($books as $book) {
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<th scope='row'>{$book['title']}</th>";
                    echo "<td>" . date_format(date_create($book['date_added']), "m/d/y") . "</td>";
                    echo "<td>{$book['condition']}</td>";
                    echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                                <input type='hidden' name='book_id' value='" . $book['google_id'] . "'>
                                <input type='hidden' name='book_title' value='" . $book['title'] . "'>
                                <input type='image' src='./img/delete.png' name='edit_trade'
                                value='Delete from Trade List' alt='delete'
                                title='Delete from Trade List'>
                                </form>
                             </td>";
                    echo "</tr>";
                    echo "</tbody>";
                }

                echo "</table>";
            } else {
                echo "Your trade list is empty";
            }

        ?>
    </div>
</div>



<?php   include "footer.php"    ?>
