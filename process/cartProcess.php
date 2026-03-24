<?php 
if(!isset($_SESSION)) session_start();
require_once "../db/connection.php";

header("Content-Type: application/json");

// auth check
if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || ($_SESSION["active_account_type"] ?? "") != "buyer"){
    echo json_encode(["success" => false, "message" => "Unauthorized!"]);
    exit;
}

$userId = intval($_SESSION["user_id"] ?? 0);
$product_id = intval($_POST["product_id"] ?? 0);

if($userId <= 0 || $product_id <= 0){
    echo json_encode(["success"=> false,"message"=> "Invalid Input!"]);
    exit;
}

// check if already in cart
$exists = Database::search("SELECT id FROM cart WHERE user_id=? AND product_id=? LIMIT 1","ii",[$userId,$product_id]);

if($exists && $exists->num_rows > 0){
    // remove
    $done = Database::iud("DELETE FROM cart WHERE user_id=? AND product_id=?","ii",[$userId,$product_id]);
    echo json_encode(["success" => ($done > 0), "action" => "removed"]);
} else {
    // add
    $done = Database::iud("INSERT INTO cart(user_id,product_id) VALUES(?,?)","ii",[$userId,$product_id]);
    echo json_encode(["success" => ($done > 0), "action"=> "added"]);
}
?>
