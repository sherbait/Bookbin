<?php
include "header.php";
include "php/functions.php";
?>

<div class="container">
<h1 class="text-center">Frequently Asked Questions</h1>

<h2>About Bookbin</h2>
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">What is Bookbin?</a>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body">
                Bookbin is an online book-swapping platform to exchange second-hand books within the Philippines.
            </div>
        </div>
    </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Trade List</a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse in">
                <div class="panel-body">
                   A Trade List is a collection of books available for trade saved to a user's account.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Wish List</a>
            </h4>
        </div>
        <div id="collapse3" class="panel-collapse collapse in">
            <div class="panel-body">
                A Trade List is a collection of desired books saved to a user's account.
            </div>
        </div>
    </div>
</div>


<h2>The Book Swapping Process</h2>
<div class="alert alert-success">
    Users must register an account on the website to create their <strong>Trade List</strong> and/or <strong>Wish List</strong>. Click <a href="register.php" class="alert-link">here</a> to sign-up.
</div>
<div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse4">As a Trader...</a>
            </h4>
        </div>
        <div id="collapse4" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action">If a book from their <strong>Trade List</strong> matches a book on another user's <strong>Wish List</strong>, they have <strong>24 hours</strong> to accept the <strong>Trade Request</strong>.
                    <a href="#" class="list-group-item list-group-item-action">A partial credit of <strong>5 Book Points</strong> will be added to the user's account.</a>
                    <a href="#" class="list-group-item list-group-item-action">Trader must send out the book via postal service.</a>
                    <a href="#" class="list-group-item list-group-item-action">The book will remain in their <strong>Pending List</strong> until the wisher confirms receiving the book.</a>
                    <a href="#" class="list-group-item list-group-item-action">The remaining <strong>5 Book Points</strong> will then be credited.</a>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse5">As a Wisher...</a>
            </h4>
        </div>
        <div id="collapse5" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action">The <strong>Wisher</strong> will see their book on <strong>Pending</strong> status if a <strong>Trader</strong> accepts their <strong>Trade Request</strong>.</a>
                    <a href="#" class="list-group-item list-group-item-action">When the <strong>Wisher</strong> receives the book, they need to confirm they have received the book.</a>
                  </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse6">What if the Trader sends the wrong book or sends an <strong>unacceptable</strong> book?</a>
            </h4>
        </div>
        <div id="collapse6" class="panel-collapse collapse in">
            <div class="panel-body">
                The Wisher may file a complaint by sending an email through our contact form <a href="contact.php">here</a>. The moderator will review the case within a span of 30 days to determine the validity of the complaint and deduct/credit points accordingly.
            </div>
        </div>
    </div>
</div>


<h2>Book Conditions</h2>
<div class="panel-group" id="accordion3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion3" href="#collapse7">Guidelines on Rating Book Conditions</a>
            </h4>
        </div>
        <div id="collapse7" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action"><strong>New</strong> : Just like it sounds. A brand-new, unused, unread copy in perfect condition. The dust cover and original protective wrapping, if any, are intact. All supplementary materials are included and all access codes for electronic material, if applicable, are valid and/or in working condition. Books with markings of any kind on the cover or pages, books marked as "Bargain" or "Remainder," or with any other labels attached, may not be listed as New condition.</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Like New</strong> : Dust cover is intact, with no nicks or tears. Spine has no signs of creasing. Pages are clean and not marred by notes or folds of any kind. May contain remainder marks on outside edges, which should be noted in listing comments.</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Very Good</strong> : Pages and dust cover are intact and not marred by notes or highlighting. The spine is undamaged.</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Good</strong> : All pages and cover are intact (including the dust cover, if applicable). Spine may show signs of wear. Pages may include limited notes and highlighting. May include "From the library of" labels.</a>
                    <a href="#" class="list-group-item list-group-item-action"><strong>Acceptable</strong> : All pages and the cover are intact, but the dust cover may be missing. Pages may include limited notes and highlighting, but the text cannot be obscured or unreadable.
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<h2>Book Points System</h2>
<div class="panel-group" id="accordion4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion4" href="#collapse8">Book Points</a>
            </h4>
        </div>
        <div id="collapse8" class="panel-collapse collapse in">
            <div class="panel-body">
                Upon creating their accounts, users earn <strong>10 Book Points</strong> by default.
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion4" href="#collapse9">Book Points Conversion</a>
            </h4>
        </div>
        <div id="collapse9" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">Each book, regardless if paperback or hardbound, is equivalent to <strong>10 Book Points</strong>.</a>
                    <a href="#" class="list-group-item list-group-item-action">When a trade is confirmed,<strong>5 Book Points</strong> are <strong>deducted from the Wisher</strong>'s account and <strong>added to the Trader</strong>'s account</a>
                    <a href="#" class="list-group-item list-group-item-action">When the delivery of the book has been confirmed, another <strong>5 Book Points</strong> will be <strong>deducted from the Wisher</strong>'s account and <strong>added to the Trader</strong>'s account.</a>
                </div>
            </div>
        </div>
     </div>
</div>

</div>

<?php
include "footer.php";
?>

