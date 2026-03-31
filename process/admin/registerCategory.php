<?php
session_start();
require_once '../../db/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION["admin_logged_in"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

 $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";

if (empty($name)) {
    echo json_encode(["success" => false, "message" => "Category name is required"]);
    exit();
}

// Check if the category already exists
 $check = Database::search("SELECT id FROM `category` WHERE `name` = ?", "s", [$name]);
if ($check && $check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Category already exists"]);
    exit();
}

// Insert new category
 $result = Database::iud("INSERT INTO `category` (`name`) VALUES (?)", "s", [$name]);

if ($result) {
    echo json_encode(["success" => true, "message" => "Category registered successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to register category"]);
}

?>
