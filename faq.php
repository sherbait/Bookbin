<?php
include "header.php";
include "php/functions.php";
?>

<div class="container mx-5">
<h1 class="text-center">Frequently Asked Questions</h1>

<h2>About Bookbin</h2>
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse1">What is Bookbin?</a>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body">
                Bookbin is an online book-swapping platform to exchange second-hand books within the Philippines.
            </div>
        </div>
    </div>
</div>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse2">Trade List</a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse in">
                <div class="panel-body">
                   A Trade List is a collection of books available for trade saved to a user's account.
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse3">Wish List</a>
                </h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse in">
                <div class="panel-body">
                    A Trade List is a collection of desired books saved to a user's account.
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse4">How does the book swapping work?</a>
                </h4>
            </div>
            <div id="collapse4" class="panel-collapse collapse in">
                <div class="panel-body">
                    Users of the website must have an account to create their Trade List and/or Wish List.
                    Users will be notified via email if any in their list is matched with someone else’s.
                    If the book is from their trade list, they must send the book via postal service
                    and a copy of the postal receipt must be submitted online. Once the book is sent
                    and their receipt verified, they earn Book Points.

                    If the book is from their wish list, a “pending”, “out for delivery”,
                    or “in transit” notice will appear and Book Points will be deducted from their account.
                    If not enough Book Points are available, their requested books will not be matched and processed.

                    Books must be in good condition with no missing pages to be traded.
                    Complaints reported by the recipient will be reviewed by a moderator. Depending on the condition
                    of the book, additional book points will either be deducted from/added to the requestor or sender.
                    Please see the section on book points for more information.

                </div>
            </div>
        </div>
    </div>

<h2>Book Points System</h2>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse5">Book Points</a>
                </h4>
            </div>
            <div id="collapse5" class="panel-collapse collapse in">
                <div class="panel-body">
                    Books are traded on a points-earning system. Users must first earn book points by sending books
                    before they will be able to wish for books.
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse6">Book Points Conversion Table</a>
                </h4>
            </div>
            <div id="collapse6" class="panel-collapse collapse in">
                <div class="panel-body">
                    Paperback = 5 pts. <br>
                    Hardbound = 10 pts.
                </div>
            </div>
        </div>
    </div>

</div>

<?php
include "footer.php";
?>

