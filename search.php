<?php
include "header.php";
include "php/functions.php";
?>

<div class="container">
    <?php
    /**
     * Bookbin does a public search using the Google Books API without accessing the user's Google Books account.
     * A book's information is stored in a volume. Only displays the first 10 results.
     * TODO: pagination to display more search results
     * TODO: advanced search
     */

    // Search result variables
    $total_items = 0;   #number of search results
    $items = array();   #will hold the books found in the search


    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        // Get search terms entered by user and append it to the base url
        $terms = clean_input($_GET["term"]);

        // Check if the user entered a value in the search bar
        if (empty($terms)) {
            echo "<div class='alert alert-warning text-center'>You must enter a value in the search box.</div>";
        } else {
            // Parse the json file sent by Google Books API
            $parse = json_decode(query_google_books($terms, API_KEY), true);
            $total_items = $parse['totalItems'];
            $items = $parse['items'];

            // Display the search results
            echo "<h4>Found {$total_items} results for '{$terms}'</h4><br>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped'>";
            echo "<tbody>";
            $count = 1;
            foreach ($items as $item) {
                // Volume info: https://developers.google.com/books/docs/v1/reference/volumes
                // Suppress any runtime error that may occur when info does not exist in Google's database
                $selfLink = @$item['selfLink'];   #Google URL to this resource
                $id = @$item['id']; #unique identifier for this
                $title = @$item['volumeInfo']['title'];  #volume title
                $authors = @$item['volumeInfo']['authors']; #the names of authors and/or editors for this volume -> list
                $publisher = @$item['volumeInfo']['publisher'];  #publisher of this volume
                $publishedDate = @$item['volumeInfo']['publishedDate'];  #date of publication
                $description = @$item['volumeInfo']['description'];  #synopsis of this volume
                $averageRating = @$item['volumeInfo']['averageRating']; #mean review rating for this volume (min=1.0,max=5.0)
                $ratingsCount = @$item['volumeInfo']['ratingsCount']; #number of review ratings for this volume
                $thumbnail = @$item['volumeInfo']['imageLinks']['thumbnail']; #image link for thumbnail size ~300pixels
                $previewLink = @$item['volumeInfo']['previewLink'];  #URL to preview this volume in Google Books

                echo "<tr>";
                echo "<td><b>$count</b></td>";
                // Book image
                echo "<td><img src='{$thumbnail}'><p></p>";

                // Add trade and wish list buttons if user is logged in
                if (isset($_SESSION["username"])) {

                    echo "<form method='post'>";
                    echo "<div class='form-group'>";
                    echo "<input type='hidden' name='book_id' value='{$id}'>";
                    echo "<input type='hidden' name='book_title' value='{$title}'>";
                    echo "<input type='hidden' name='book_url' value='{$previewLink}'>";
                    // Value [I own this | I want to read this] referenced in trade_list.php and wish_list.php respectively
                    echo "<div class='btn-group-vertical'>";
                    echo "<button type='submit' class='btn btn-primary' value='I own this' 
                                formaction='trade_list.php' name='edit_trade'>I own this</button>";
                    echo "<button type='submit' class='btn btn-primary' value='I want to read this' 
                                formaction='wish_list.php' name='edit_wish'>I want to read this</button>";
                    echo "</div>";
                    //echo "<input type='submit' value='I own this' formaction='trade_list.php' name='edit_trade'>";
                    //echo "<input type='submit' value='I want to read this' formaction='wish_list.php' name='edit_wish'>";
                    echo "</div>";
                    echo "</form>";

                }
                echo "</td>";

                // Book title with link to Google
                echo "<td><a href='{$previewLink}' target='_blank'>{$title}</a><br>";
                // Display author(s)
                $author_list = "";
                if (@count($authors) > 0) {
                    foreach (@$authors as $author) {
                        $author_list .= "{$author}, ";
                    }
                }
                $author_list = trim($author_list, ", ");
                echo "<small>by: {$author_list}</small>";
                echo "<br>";
                // Display description
                echo $description;
                echo "</td>";
                echo "</tr>";







                $count++;
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";


            /*echo "<table id='search_result'>";
            echo "Found {$total_items} items <br />";

            foreach ($items as $item) {
                echo "<tr>";

                // Volume info: https://developers.google.com/books/docs/v1/reference/volumes
                // Suppress any runtime error that may occur when info does not exist in Google's database
                $selfLink = @$item['selfLink'];   #Google URL to this resource
                $id = @$item['id']; #unique identifier for this
                $title = @$item['volumeInfo']['title'];  #volume title
                $authors = @$item['volumeInfo']['authors']; #the names of authors and/or editors for this volume -> list
                $publisher = @$item['volumeInfo']['publisher'];  #publisher of this volume
                $publishedDate = @$item['volumeInfo']['publishedDate'];  #date of publication
                $description = @$item['volumeInfo']['description'];  #synopsis of this volume
                $averageRating = @$item['volumeInfo']['averageRating']; #mean review rating for this volume (min=1.0,max=5.0)
                $ratingsCount = @$item['volumeInfo']['ratingsCount']; #number of review ratings for this volume
                $thumbnail = @$item['volumeInfo']['imageLinks']['thumbnail']; #image link for thumbnail size ~300pixels
                $previewLink = @$item['volumeInfo']['previewLink'];  #URL to preview this volume in Google Books

                //echo "<hr>";
                // Display thumbnail with preview link
                echo "<td>";
                echo "<a href='{$previewLink}' target='_blank'><img src='{$thumbnail}'></a><br />";
                echo "</td>";
                // Display title
                echo "<td>";
                echo "<b>{$title}</b><br />";
                // Display author(s)
                $author_list = "";
                if (@count($authors) > 0) {
                    foreach (@$authors as $author) {
                        $author_list .= "{$author}, ";
                    }
                }
                $author_list = trim($author_list, ", ");
                echo "by: {$author_list}";
                echo "<br />";
                // Display description
                echo $description;
                echo "<br />";

                if (isset($_SESSION["username"])) {
                    // Add trade and wish list buttons if user is logged in
                    echo "<div>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='book_id' value='{$id}'>";
                    echo "<input type='hidden' name='book_title' value='{$title}'>";
                    echo "<input type='hidden' name='book_url' value='{$previewLink}'>";
                    echo "<input type='submit' value='I own this' formaction='trade_list.php' name='edit_trade'>";
                    echo "<input type='submit' value='I want to read this' formaction='wish_list.php' name='edit_wish'>";
                    echo "</form>";
                    echo "</div>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";*/
        }
    }
    ?>

</div>
