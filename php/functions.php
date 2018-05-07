<?php
/**
 * Created by PhpStorm.
 * User: dinge
 * Date: 5/1/2018
 * Time: 8:12 PM
 */

// Error handler
/*function custom_error($level, $message, $file="Unknown", $line=0, $context=array()) {
    if (($level == E_NOTICE) || ($level == E_ERROR)) {
        return false;
    }

    if (!error_reporting()) {
        return false;
    }

    throw new Exception($message, $level);

    return true;
}

set_error_handler("custom_error", E_NOTICE);*/

function get_volume_from_google_books($volume_id, $token) {
    $base = "https://www.googleapis.com/books/v1/volumes/";
    $url = $base . $volume_id . "?key=" . $token;

    //Initialize cURL.
    $ch = curl_init();
    //Set the URL that you want to GET by using the CURLOPT_URL option.
    curl_setopt($ch, CURLOPT_URL, $url);
    //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    //Execute the request.
    $data = curl_exec($ch);
    //Close the cURL handle.
    curl_close($ch);
    //Return the data
    return $data;
}

function query_google_books($query, $token) {
    $base = "https://www.googleapis.com/books/v1/volumes?q=";
    $terms = urlencode($query);
    $url = $base . $terms . "&key=" . API_KEY;

    //Initialize cURL.
    $ch = curl_init();
    //Set the URL that you want to GET by using the CURLOPT_URL option.
    curl_setopt($ch, CURLOPT_URL, $url);
    //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    //Execute the request.
    $data = curl_exec($ch);
    //Close the cURL handle.
    curl_close($ch);
    //Return the json data sent by Google.
    return $data;
}

// Removes whitespaces and backslashes, also sanitizes data
function clean_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

/* VALIDATION FUNCTIONS - returns the error message */
function validate_password($input) {
    if (empty($input)) {
        return "Password is required";
    } else {
        // Check if password is at least 8 characters long
        if (strlen($input) < 8) {
            return "Must be at least 8 characters";
        } else {
            return "";
        }
    }
}

function confirm_password($pass, $confirm_pass) {
    if (empty($confirm_pass)) {
        return "Confirm password is required";
    } else {
        // Check if password entered is the same
        if ($pass != $confirm_pass) {
            return "Password does not match";
        } else {
            return "";
        }
    }
}

function validate_email($input) {
    if (empty($input)) {
        return "Email is required";
    } else {
        // Check if email address has the correct format
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        } else {
            return "";
        }
    }
}

function validate_phone($input) {
    if (empty($input)) {
        return "Cellphone/Landline is required";
    } else {
        // Check if phone number is all digits (e.g. 09171234567 or 029876543)
        if (!preg_match("/^[0-9]*$/", $input)) {
            return "Only numbers allowed";
        } else {
            // Check if phone number has max 11 digits and minimum 7
            if (strlen($input) < 7 || strlen($input) > 11) {
                return "Must be 7-11 digit phone number";
            } else {
                return "";
            }
        }
    }
}

function validate_fname($input) {
    if (empty($input)) {
        return "First name is required";
    } else {
        // Check if name has only letters and spaces
        if (!preg_match("/^[a-zA-Z ]*$/", $input)) {
            return "Only letters and white space allowed";
        } else {
            return "";
        }
    }
}

function validate_mname($input) {
    if (!preg_match("/^[a-zA-Z ]*$/", $input)) {
        return "Only letters and white space allowed";
    } else {
        return "";
    }
}

function validate_lname($input) {
    if (empty($input)) {
        return "Last name is required";
    } else {
        if (!preg_match("/^[a-zA-Z ]*$/", $input)) {
            return "Only letters and white space allowed";
        }
    }
}

function validate_address($input) {
    if (empty($input)) {
        return "Address is required";
    } else {
        return "";
    }
}

function validate_address_no($input) {
    if (empty($input)) {
        return "House/Bldg/Unit No. is required";
    } else {
        return "";
    }
}

function validate_address_street($input) {
    if (empty($input)) {
        return "Street/Subdivision is required";
    } else {
        return "";
    }
}

function validate_address_city($input) {
    if (empty($input)) {
        return "City is required";
    } else {
        return "";
    }
}

function validate_address_province($input) {
    if (empty($input)) {
        return "Province is required";
    } else {
        return "";
    }
}

function validate_address_zip($input) {
    if (empty($input)) {
        return "Zip/Postal code is required";
    } else {

        // Check if zip code is a 4-digit number
        if (!preg_match("/^[0-9]*$/", $input)) {
            return "4-digit zip code required";
        } else {
            if (strlen($input) != 4) {
                return "Zip code must be 4 digits";
            } else {
                return "";
            }
        }
    }
}