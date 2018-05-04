<?php
    include "header.php";
?>

<!-- code here -->
<div class="container">
    <div class="row">
        <div class="col"><h1>What is Bookbin?</h1></div>
        <div class="col"><h2> Bookin is Philippine-based book swapping platform for book lovers.</h2></div>
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
            <h2>Recently Requested</h2>
        </span>
        <span class="border border-secondary col-sm-6">
            <h2>Highly Requested</h2>
        </span>
    </div>
</div>

<?php
    include "footer.php";
?>


