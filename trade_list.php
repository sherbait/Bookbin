<?php
include "header.php";
include "./php/session.php";
?>

<?php
// Variables for displaying messages to the user
$add_book_err = "";
$empty_list = true;
$add_book = "";

$books = array();   #holds the books from the database that the user has added to their trade list
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];   #This is a unique identifier for this user in the database


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Variables for adding or deleting a book
    $book_google_id = $book_title = $condition = "";

    // Check if the user clicked the [Add to Trade List] button in search.php
    if ($_POST['add_trade'] === "Add to Trade List") {

        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];
        $condition = "very good";   #default value TODO ask user to set this

        // Check if the book exists in the user's trade list
        $sql = "SELECT * FROM trade_item INNER JOIN trade_list ON trade_item.id=trade_list.trade_item_id
                WHERE trade_list.user_id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variables
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            // Attempt to execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Get the result
                $result = mysqli_stmt_get_result($stmt);

                // If the book exists in the user's trade list, don't add into the database, then inform the user
                if ($result->num_rows > 0) {
                    // The user has a trade list, retrieve the book data
                    while ($row = $result->fetch_assoc()) {
                        //TODO store data to books()
                        if ($row['google_id'] === $book_google_id) {
                            $add_book_err = "Book already exists in your trade list";
                        }
                    }
                } else {
                    $empty_list = false;
                }
            } else {
                echo "Something went wrong checking {$book_title} in the database. Please try again later.";
            }
        }
        // Close the statement
        mysqli_stmt_close($stmt);

        // Book doesn't exist in the user's trade list so add it
        if (empty($add_book_err)) {
            $sql = "INSERT into trade_item(google_id, title) VALUES(?,?)";
            // Store the trade_item.id generated from inserting into this table
            $trade_item_id = 0;

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $book_google_id, $book_title);
                if (mysqli_stmt_execute($stmt)) {
                    $trade_item_id = mysqli_insert_id($conn);
                } else {
                    echo "Something went wrong adding {$book_title} in the database. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);

            $sql = "INSERT into trade_list(user_id, trade_item_id, `condition`) VALUES(?,?,?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "iis", $user_id, $trade_item_id, $condition);
                if (mysqli_stmt_execute($stmt)) {
                    $add_book = "Successfully added";
                } else {
                    echo "Something went wrong adding {$book_title} in the database. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);

        }
    }
}
?>

<h2>Trade List</h2>

<div>
    <!-- display any system messages here -->
    <span class="error"><?php echo $add_book_err ?></span>
    <span class="message"><?php echo $add_book ?></span>
    <?php if ($empty_list) echo "<span>Your list is empty</span>"?><br>

    <!-- display trade list table here -->

</div>



<?php   include "footer.php"    ?>
