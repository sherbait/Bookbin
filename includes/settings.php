<?php
/**
 * Created by PhpStorm.
 * User: dinge
 * Date: 4/28/2018
 * Time: 10:02 AM
 */

// database settings
define("SERVER", "localhost");
define("DATABASE", "bookbin");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");


date_default_timezone_set("Asia/Manila");
$link = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE);
if (!$link)
    die("Unable to connect to MySQL: " . mysqli_connect_error());
else
    echo "Connected to database " . DATABASE;