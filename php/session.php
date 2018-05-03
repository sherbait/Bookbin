<?php
/**
 * Created by PhpStorm.
 * User: dinge
 * Date: 5/1/2018
 * Time: 5:51 PM
 */

// Redirect to an access denied page if the user is not logged in
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("location: access_denied.php");
    exit;
}