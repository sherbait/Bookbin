<?php
include "header.php";
include "./php/session.php";
?>

<?php
// Variables for displaying messages to the user
$add_book_err = "";
$add_book = $delete_book = "";

$books = array();   #holds the books from the database that the user has added to their wish list
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];   #This is a unique identifier for this user in the database

// Handle the case when user adds or deletes a book via form
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Variables for adding or deleting a book
    $book_google_id = $book_title = $condition = "";

    // Check if the user clicked the [Add to Wish List] button in search.php
    if ($_POST['edit_wish'] === "Add to Wish List") {

        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];
        $condition = "";   #default value

        // Check if the book exists in the user's wish list
        $sql = "SELECT * FROM (SELECT * FROM wish_item INNER JOIN wish_list ON wish_item.id=wish_list.wish_item_id
              WHERE wish_list.user_id=?) AS user_wish_list WHERE google_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the variables
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $book_google_id);
            // Attempt to execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Get the result
                $result = mysqli_stmt_get_result($stmt);

                if ($result->num_rows > 0) {    // Should be 1 row if book exists since book_google_id is unique
                    $add_book_err = "Book already exists in your wish list";
                }
            } else {
                echo "Something went wrong retrieving your book list. Please try again later.";
            }
        }
        // Close the statement
        mysqli_stmt_close($stmt);

        // Book doesn't exist in the user's wish list so add it
        if (empty($add_book_err)) {
            $sql = "INSERT into wish_item(google_id, title) VALUES(?,?)";
            // Store the wish_item.id generated from inserting into this table
            $wish_item_id = 0;

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $book_google_id, $book_title);
                if (mysqli_stmt_execute($stmt)) {
                    $wish_item_id = mysqli_insert_id($conn);
                } else {
                    echo "Something went wrong adding {$book_title} in the database. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);

            $sql = "INSERT into wish_list(user_id, wish_item_id, `condition`) VALUES(?,?,?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "iis", $user_id, $wish_item_id, $condition);
                if (mysqli_stmt_execute($stmt)) {
                    $add_book = "Successfully added";
                } else {
                    echo "Something went wrong adding {$book_title} in the database. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    } elseif ($_POST['edit_wish'] === "Delete from Wish List") {
        $book_google_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];

        // Prepare the statement
        $sql = "DELETE FROM wish_item WHERE google_id=?";

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

// Retrieve the updated user's wish list
$sql = "SELECT * FROM wish_item INNER JOIN wish_list ON wish_item.id=wish_list.wish_item_id
                WHERE wish_list.user_id=?";    // query to get the user's wish list

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

<h2>Wish List</h2>

<div>
    <!-- display any system messages here -->
    <span class="error"><?php echo $add_book_err ?></span>
    <span class="message"><?php echo $add_book ?></span>
    <span class="message"><?php echo $delete_book ?></span>

    <!-- display wish list table here -->
    <div>
        <?php   // Populate the wish list table
        if (count($books) > 0) {
            echo "<table>";

            echo "Books in wish list: " . count($books);
            echo "<tr>";
            echo "<th>Title</th><th>Date Added</th><th>Condition</th><th>Action</th>";
            echo "</tr>";
            foreach ($books as $book) {
                echo "<tr>";
                echo "<td>{$book['title']}</td>";
                echo "<td>" . date_format(date_create($book['date_added']), "m/d/y") . "</td>";
                echo "<td>{$book['condition']}</td>";
                echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>
                                <input type='hidden' name='book_id' value='" . $book['google_id'] . "'>
                                <input type='hidden' name='book_title' value='" . $book['title'] . "'>
                                <input type='image' src='./img/delete.png' name='edit_wish'
                                value='Delete from Wish List' alt='delete'
                                title='Delete from Wish List'>
                                </form>
                             </td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "Your wish list is empty";
        }

        ?>
    </div>
</div>

<?php   include "footer.php"    ?>
