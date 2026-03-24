<?php
header('Content-Type: application/json');

require "../db/connection.php";
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access."
    ]);
    exit();
}

$userId = $_SESSION["user_id"];

$fname     = $_POST["fname"]     ?? "";
$lname     = $_POST["lname"]     ?? "";
$email     = $_POST["email"]     ?? "";
$bio       = $_POST["bio"]       ?? "";
$genderId  = $_POST["genderID"]  ?? null;
$mobile    = $_POST["mobile"]    ?? "";
$line1     = $_POST["line01"]    ?? "";
$line2     = $_POST["line02"]    ?? "";
$cityId    = $_POST["cityID"]    ?? 0;
$avatarUrl = $_POST["avatarUrl"] ?? "";

// Validation
if (empty($fname)) {
    echo json_encode(["success" => false, "message" => "First name is required!"]); exit();
} else if (empty($lname)) {
    echo json_encode(["success" => false, "message" => "Last name is required!"]); exit();
} else if (empty($email)) {
    echo json_encode(["success" => false, "message" => "Email is required!"]); exit();
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email format!"]); exit();
} else if (strlen($email) >= 150) {
    echo json_encode(["success" => false, "message" => "Email must be less than 150 characters!"]); exit();
} else if (!empty($bio) && strlen($bio) > 500) {
    echo json_encode(["success" => false, "message" => "Bio must be maximum 500 characters!"]); exit();
} else if (!empty($mobile) && !preg_match("/^\d{10}$/", $mobile)) {
    echo json_encode(["success" => false, "message" => "Mobile must be 10 digits!"]); exit();
} else if (empty($line1)) {
    echo json_encode(["success" => false, "message" => "Address line 1 is required!"]); exit();
} else if ($cityId == 0) {
    echo json_encode(["success" => false, "message" => "City is required!"]); exit();
}

// Handle optional fields
$genderId = ($genderId == 0) ? null : $genderId;

try {
    // 1. Update User Table
    Database::iud(
        "UPDATE `user` SET `fname`=?, `lname`=? WHERE `id`=?",
        "ssi",
        [$fname, $lname, $userId]
    );
    $_SESSION["user_name"] = $fname . " " . $lname;

    // 2. Fetch or Create Address
    $profileQuery = Database::search(
        "SELECT `address_id`, `avatar_url` FROM `user_profile` WHERE `user_id`=?",
        "i",
        [$userId]
    );
    $existingProfile = $profileQuery->fetch_assoc();
    $addressId = $existingProfile["address_id"] ?? null;

    if ($addressId) {
        Database::iud(
            "UPDATE `address` SET `line_1`=?, `line_2`=?, `city_id`=? WHERE `id`=?",
            "ssii",
            [$line1, $line2, $cityId, $addressId]
        );
    } else {
        if (!empty($line1) || !empty($line2) || $cityId > 0) {
            Database::iud(
                "INSERT INTO `address` (`line_1`, `line_2`, `city_id`) VALUES (?, ?, ?)",
                "ssi",
                [$line1, $line2, $cityId]
            );
            $conn = Database::getConnection();
            $addressId = $conn->insert_id;
        }
    }

    // 3. Handle Avatar Upload
    $avatarUrl = $existingProfile["avatar_url"] ?? "";
    if (isset($_FILES["avatarFile"]) && $_FILES["avatarFile"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpeg", "jpeg" => "image/jpeg", "png" => "image/png"];
        $filename = $_FILES["avatarFile"]["name"];
        $filesize = $_FILES["avatarFile"]["size"];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!array_key_exists($ext, $allowed)) {
            echo json_encode(["success" => false, "message" => "Invalid file format. Only JPG, JPEG, and PNG allowed."]); exit();
        }
        if ($filesize > 5 * 1024 * 1024) {
            echo json_encode(["success" => false, "message" => "File size too large (max 5MB)."]); exit();
        }

        $newFilename = "profile_" . $userId . "_" . time() . "." . $ext;
        $uploadPath = "../assets/users_images/" . $newFilename;

        if (move_uploaded_file($_FILES["avatarFile"]["tmp_name"], $uploadPath)) {
            $avatarUrl = "assets/users_images/" . $newFilename;
        }
    }

    // 4. Update or Insert User Profile
    if ($existingProfile) {
        $types = "ssissi"; // corrected bind types
        $params = [$avatarUrl, $bio, $genderId, $mobile, $addressId, $userId];

        Database::iud(
            "UPDATE `user_profile` 
             SET `avatar_url`=?, `bio`=?, `gender_id`=?, `mobile`=?, `address_id`=? 
             WHERE `user_id`=?",
            $types,
            $params
        );
    } else {
        $types = "issisi"; 
        $params = [$userId, $avatarUrl, $bio, $genderId, $mobile, $addressId];

        Database::iud(
            "INSERT INTO `user_profile` (`user_id`, `avatar_url`, `bio`, `gender_id`, `mobile`, `address_id`) 
             VALUES (?, ?, ?, ?, ?, ?)",
            $types,
            $params
        );
    }

    echo json_encode([
        "success" => true,
        "message" => "Profile updated successfully!"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage() // show actual error for debugging
    ]);
}
?>
