<?php
require "../db/connection.php";

$response = [];

// Total Users
$resUser = Database::search("SELECT COUNT(*) as count FROM `user` ");
$response["userCount"] = $resUser->fetch_assoc()["count"];

// Active Products
$resProd = Database::search("SELECT COUNT(*) as count FROM `product` WHERE `status`='active'");
$response["prodCount"] = $resProd->fetch_assoc()["count"];

// Total Revenue
$resRev = Database::search("SELECT SUM(`total`) as revenue FROM `invoice` ");
$revenue = $resRev->fetch_assoc()["revenue"] ?? 0;
$response["revenue"] = number_format($revenue, 2);

// Total Orders
$resOrder = Database::search("SELECT COUNT(*) as count FROM `order` ");
$response["orderCount"] = $resOrder->fetch_assoc()["count"];

echo json_encode($response);
?>
