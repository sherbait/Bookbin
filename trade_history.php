<?php
    include "header.php";
    include "./php/session.php";
    include "./php/user_info.php";
    include "profile_sidebar.php";
?>

<!-- Code here to display profile information. Select whichever is relevant for this page. -->

        <div class="col-sm-10">
            <h1> Trade History</h1>
             <table class="table table-striped">
                 <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Transaction Date</th>
                        <th scope="col">Earned</th>
                        <th scope="col">Spent</th>
                        <th scope="col">Balance</th>
                    </tr>
                 </thead>
                 <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                 </tbody>
             </table>
        </div>
      </div>
</div>


<?php   include "footer.php"    ?>