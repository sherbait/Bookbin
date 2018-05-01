<?php
/**
 * Created by PhpStorm.
 * User: dinge
 * Date: 4/28/2018
 * Time: 10:02 AM
 */

// Database settings
define("DB_SERVER", "localhost");
define("DB_NAME", "bookbin");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");


date_default_timezone_set("Asia/Manila");

// Creates a connection to the database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("Unable to connect to database: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");