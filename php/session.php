<?php
/**
 * Created by PhpStorm.
 * User: dinge
 * Date: 5/1/2018
 * Time: 5:51 PM
 */

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("location: login.php");
    exit;
}