<?php
session_start();
require "../db/connection.php";

if (isset($_POST["vcode"])) {
    $v_code = $_POST["vcode"];

    if (empty($v_code)) {
        echo "Please enter the verification code.";
    } else if (strlen($v_code) != 6) {
        echo "Verification code must be 6 digits.";
    } else {
        if (isset($_SESSION["admin_email"])) {
            $email = $_SESSION["admin_email"];

            $result = Database::search("SELECT * FROM `admin` WHERE `email`=? AND `vcode`=?", "ss", [$email, $v_code]);

            if ($result->num_rows > 0) {
                // Login Success
                $admin_data = $result->fetch_assoc();
                $_SESSION["admin_logged_in"] = true;
                $_SESSION["admin_fname"] = $admin_data["fname"];
                $_SESSION["admin_lname"] = $admin_data["lname"];
                
                // Important: Clear the code after successful login
                Database::iud("UPDATE `admin` SET `vcode`='' WHERE `email`=?", "s", [$email]);

                echo "success";
            } else {
                echo "Invalid verification code.";
            }
        } else {
            echo "Session expired. Please try again.";
        }
    }
} else {
    echo "Verification code not found.";
}
?>
