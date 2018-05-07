<?php
include "header.php";
include "php/functions.php";
?>

<div class="container">
    <h2>Latest Wishes</h2>
    <p>Do you own any of these books? Other users have just asked for them!</p>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>

                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $sql = "SELECT * FROM recently_requested";
            if ($stmt=mysqli_prepare($conn, $sql)) {
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    while($row = $result->fetch_assoc()) {
                        // Get the volume info from Google
                        $data = json_decode(get_volume_from_google_books($row['google_id'], API_KEY), true);
                        // Store the info
                        $link = $data['volumeInfo']['previewLink'];
                        $title = $data['volumeInfo']['title'];
                        $avg_rating = $data['volumeInfo']['averageRating'];
                        $ratings = $data['volumeInfo']['ratingsCount'];
                        $small_thumb = $data['volumeInfo']['imageLinks']['smallThumbnail'];
                        $description = $data['volumeInfo']['description'];
                        //Display the info
                        echo "<tr>";
                        echo "<td>{$count}</td>";
                        echo "<td><img src='{$small_thumb}'></td>";
                        echo "<td class='col-md-2'><a href='{$link}' target='_blank'>{$title}</a><br>";
                        echo "Rating: {$avg_rating} <br> Votes: {$ratings}</td>";
                        echo "<td>{$description}";
                        echo "</tr>";
                        $count++;
                    }
                }
            }
            mysqli_stmt_close($stmt);
            ?>
            </tbody>
        </table>
    </div>
</div>
