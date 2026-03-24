<?php 
session_start();

require_once "../db/connection.php";

header("Content-type: application/json");

// check authentication
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true || $_SESSION["active_account_type"] !== "seller") {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized access!"]);
    exit();
}

$userId = $_SESSION["user_id"]; // make sure this matches your login system
$productId = intval($_POST["product_id"] ?? 0);

if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid product ID!"]);
    exit();
}

// fetch current product status
$statusResult = Database::search(
    "SELECT `status` FROM `product` WHERE `id`=? AND `seller_id`=?",
    "ii",
    [$productId, $userId]
);

if (!$statusResult || $statusResult->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Product not found or unauthorized"]);
    exit();
}

$currentStatus = $statusResult->fetch_assoc()["status"];

// toggle status
$newStatus = ($currentStatus === "active") ? "inactive" : "active";

// update product status
$result = Database::iud(
    "UPDATE `product` SET `status`=? WHERE `id`=? AND `seller_id`=?",
    "sii",
    [$newStatus, $productId, $userId]
);

if ($result) {
    echo json_encode([
        "success" => true,
        "message" => "Product status updated successfully",
        "newStatus" => $newStatus
    ]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to update product status!"]);
}
?>
