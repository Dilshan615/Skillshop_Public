<?php

if (!isset($_SESSION)) session_start();

require_once "../db/connection.php";


header("Content-Type: application/json");

// Check if Database class exists
if (!class_exists('Database')) {
    echo json_encode(["success" => false, "message" => "Database class not found!"]);
    exit;
}

// Check if user is logged in and is a buyer
if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    echo json_encode(["success" => false, "message" => "Please log in first!"]);
    exit;
}

if (($_SESSION["active_account_type"] ?? "") != "buyer") {
    echo json_encode(["success" => false, "message" => "Only buyers can checkout!"]);
    exit;
}

// Get user ID
$userId = intval($_SESSION["user_id"] ?? 0);
if ($userId == 0) {
    echo json_encode(["success" => false, "message" => "Invalid user ID!"]);
    exit;
}

// Get cart items
$cartItemsQ = Database::search(
    "SELECT c.id AS cart_item_id, 
            p.id AS product_id, 
            p.price,
            p.title,
            u.id AS seller_id,
            u.fname AS seller_fname, 
            u.lname AS seller_lname,
            sa.city_id AS seller_city_id
     FROM cart c
     JOIN product p ON c.product_id = p.id
     JOIN user u ON p.seller_id = u.id
     LEFT JOIN user_profile up ON u.id = up.user_id
     LEFT JOIN address sa ON up.address_id = sa.id
     WHERE c.user_id = ?
     ORDER BY c.created_at DESC",
    "i",
    [$userId]
);

// Check if cart has items
if (!$cartItemsQ) {
    echo json_encode(["success" => false, "message" => "Database error: Failed to fetch cart items"]);
    exit;
}

if ($cartItemsQ->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "Your cart is empty!"]);
    exit;
}

// Get buyer's city
$buyerCityQ = Database::search(
    "SELECT a.`city_id` FROM `user_profile` up
     JOIN `address` a ON up.`address_id` = a.`id`
     WHERE up.`user_id` = ?",
    "i",
    [$userId]
);

$buyerCityId = 0;
if ($buyerCityQ && $buyerCityQ->num_rows > 0) {
    $buyerCityData = $buyerCityQ->fetch_assoc();
    $buyerCityId = intval($buyerCityData["city_id"] ?? 0);
}

// Calculate totals
$subtotal = 0;
$totalDeliveryfee = 0;
$sellersInCart = [];
$cartItems = [];

// Reset pointer and fetch all items
$cartItemsQ = Database::search(
    "SELECT c.id AS cart_item_id, 
            p.id AS product_id, 
            p.price,
            p.title,
            u.id AS seller_id,
            u.fname AS seller_fname, 
            u.lname AS seller_lname,
            sa.city_id AS seller_city_id
     FROM cart c
     JOIN product p ON c.product_id = p.id
     JOIN user u ON p.seller_id = u.id
     LEFT JOIN user_profile up ON u.id = up.user_id
     LEFT JOIN address sa ON up.address_id = sa.id
     WHERE c.user_id = ?
     ORDER BY c.created_at DESC",
    "i",
    [$userId]
);

while ($item = $cartItemsQ->fetch_assoc()) {
    $subtotal += floatval($item["price"] ?? 0);
    $cartItems[] = $item;

    $sellerId = $item["seller_id"];
    if (!isset($sellersInCart[$sellerId])) {
        $deliveryFee = 500; // Default delivery fee
        if ($buyerCityId != 0 && isset($item["seller_city_id"])) {
            $deliveryFee = ($item["seller_city_id"] == $buyerCityId) ? 500 : 500;
        }
        $totalDeliveryfee += $deliveryFee;
        $sellersInCart[$sellerId] = $deliveryFee;
    }
}

$total = $subtotal + $totalDeliveryfee;

// Payhere configuration
$merchantId = "1232356";
$merchantSecret = "MzkzNDMxODU2NTI4ODAxNDkwNTMzNDE2MTAyNTE3MTA4OTY2ODM2";
$currency = "LKR";
$formattedTotal = number_format($total, 2, ".", "");
$orderId = "ORD" . uniqid() . time();

// Generate hash
$hash = strtoupper(
    md5(
        $merchantId .
            $orderId .
            $formattedTotal .
            $currency .
            strtoupper(md5($merchantSecret))
    )
);

// Get user details for billing
$userQ = Database::search(
    "SELECT u.`fname`, u.`lname`, u.`email`,
            up.`mobile`,
            a.`line_1`, a.`line_2`,
            c.`name` AS `city_name`,
            co.`name` AS `country_name`
    FROM `user` u
    JOIN `user_profile` up ON u.`id` = up.`user_id`
    JOIN `address` a ON up.`address_id` = a.`id`
    JOIN `city` c ON a.`city_id` = c.`id`
    JOIN `country` co ON c.`country_id` = co.`id`
    WHERE u.`id` = ?",
    "i",
    [$userId]
);

if (!$userQ || $userQ->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "User address information not found! Please update your profile."]);
    exit;
}

$user = $userQ->fetch_assoc();

// Prepare items string (limit to first 3 items for Payhere)
$itemNames = array_slice(array_column($cartItems, 'title'), 0, 3);
$itemsString = implode(', ', $itemNames);
if (count($cartItems) > 3) {
    $itemsString .= ' and ' . (count($cartItems) - 3) . ' more items';
}

// Create payment object
$paymentObject = [
    "sandbox" => true,
    "merchant_id" => $merchantId,
    "return_url" => "http://localhost/skillshop_online/buyer-dashboard.php",
    "cancel_url" => "http://localhost/skillshop_online/buyer-dashboard.php",
    "notify_url" => "http://localhost/skillshop_online/process/payhereNotify.php",
    "order_id" => $orderId,
    "items" => $itemsString,
    "amount" => $formattedTotal,
    "currency" => $currency,
    "hash" => $hash,
    "first_name" => $user["fname"],
    "last_name" => $user["lname"],
    "email" => $user["email"],
    "phone" => $user["mobile"],
    "address" => trim(($user["line_1"] ?? "") . " " . ($user["line_2"] ?? "")),
    "city" => $user["city_name"],
    "country" => $user["country_name"]
];

// Store order in session for later verification
$_SESSION['pending_order'] = [
    'order_id' => $orderId,
    'amount' => $formattedTotal,
    'currency' => $currency,
    'user_id' => $userId,
    'cart_items' => $cartItems,
    'delivery_fee' => $totalDeliveryfee,
    'subtotal' => $subtotal
];


echo json_encode(["success" => true, "payment" => $paymentObject]);
exit;
