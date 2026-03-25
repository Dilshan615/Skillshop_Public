<?php
session_start();
require "../db/connection.php";
require "PHPMailer/email.php";

if (isset($_POST["email"])) {
    $email = $_POST["email"];

    if (empty($email)) {
        echo "Please enter your email.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // Check if admin exists
        $result = Database::search("SELECT * FROM `admin` WHERE `email`=?", "s", [$email]);

        if ($result->num_rows > 0) {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            Database::iud("UPDATE `admin` SET `vcode`=? WHERE `email`=?", "ss", [$code, $email]);

            if (EmailHelper::sendAdminVerificationCode($email, "Admin", $code)) {
                $_SESSION["admin_email"] = $email;
                echo "success";
            } else {
                echo "Email sending failed.";
            }
        } else {
            echo "Access Denied. You are not an admin.";
        }
    }
} else {
    echo "No email provided.";
}
?>
