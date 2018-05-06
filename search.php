<?php
include "header.php";
include "./php/functions.php";
?>

<?php
/**
 * Bookbin does a public search using the Google Books API without accessing the user's Google Books account.
 * A book's information is stored in a volume. Only displays the first 10 results.
 * TODO: pagination to display more search results
 * TODO: advanced search
 */

// Search a volume by sending an HTTP GET request to this URL
$base = "https://www.googleapis.com/books/v1/volumes?q=";

/* ADVANCED SEARCH RESULT VARIABLES */
// Find a volume by title, that contains the text appended to this variable
$intitle = "intitle:";
// Find a volume by author, that contains the text appended to this variable
$inauthor = "inauthor:";
// Find a volume by ISBN
$isbn = "isbn:";

// Search result variables
$total_items = 0;   #number of search results
$items = array();   #will hold the books found in the search


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Get search terms entered by user and append it to the base url
    $terms = clean_input($_GET["term"]);

    // Check if the user entered a value in the search bar
    if (empty($terms)) {
        echo "<div><h3>Must enter a search value</h3></div>";   // TODO: need proper css
    } else {
        $terms = urlencode($terms);
        $url = $base . $terms . "&key=" . API_KEY;

        //Initialize cURL.
        $ch = curl_init();
        //Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($ch, CURLOPT_URL, $url);
        //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        //Execute the request.
        $data = curl_exec($ch);
        //Close the cURL handle.
        curl_close($ch);
        //Print the data out onto the page.
        //echo $data;

        // Parse the json file sent by Google Books API
        $parse = json_decode($data, true);
        $total_items = $parse['totalItems'];
        $items = $parse['items'];

        // TODO: Display the table with proper formatting and css (e.g. https://www.youtube.com/watch?v=bJ5K7IERMRE)
        echo "<table id='search_result'>";
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
                echo "<input type='submit' value='I own this' formaction='trade_list.php' name='edit_trade'>";
                echo "<input type='submit' value='I want to read this' formaction='wish_list.php' name='edit_wish'>";
                echo "</form>";
                echo "</div>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>