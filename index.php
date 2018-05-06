<?php
    include "header.php";
?>

<!-- code here -->
<div class="container">
    <div class="row">
        <div class="col"><h1>What is Bookbin?</h1></div>
        <br>
        <div class="col"><h2> Bookbin is Philippine-based book swapping platform for book lovers.</h2></div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form class="form-inline" action='search.php' id='form_search'>
            <input id="search_bar_big" type='search' placeholder="Search books..." aria-label="Search" name='term' id='form_search_term'>
            </form>
        </div>
    </div>
    <div class="row text-center">
        <span class="border border-secondary col-sm-6">
            <table>
                <h2>Recently Requested</h2>
                <?php
                    $books = array();

                    $sql = "SELECT title FROM recently_requested";
                    if ($stmt=mysqli_prepare($conn, $sql)) {
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            while($row = $result->fetch_assoc()) {
                                echo "<tr><td>{$row['title']}</tr></td>";
                            }
                        }
                    }
                    mysqli_stmt_close($stmt);
                ?>
            </table>
        </span>
        <span class="border border-secondary col-sm-6">
            <table>
                <h2>Highly Requested</h2>
                <?php
                $books = array();

                $sql = "SELECT title FROM mostly_requested";
                if ($stmt=mysqli_prepare($conn, $sql)) {
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>{$row['title']}</tr></td>";
                        }
                    }
                }
                mysqli_stmt_close($stmt);
                ?>
            </table>

        </span>
    </div>
</div>

<?php
    include "footer.php";
?>


