<?php
session_start();
require_once "db/connection.php";

if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true){
    header("Location: index.php");
    exit;
}

$userId = intval($_SESSION["user_id"] ?? 0);
$orderId = isset($_GET["id"]) ? $_GET["id"] : "";

// fetch invoice
$invoiceQ = Database::search(
  "SELECT * FROM `invoice` WHERE `order_order_id`=? AND `user_id`=?",
  "si", [$orderId, $userId]
);
if(!$invoiceQ || $invoiceQ->num_rows == 0){
  die("Invoice not found or unauthorized access!");
}
$invoice = $invoiceQ->fetch_assoc();

// fetch buyer info
$buyerQ = Database::search(
  "SELECT u.fname, u.lname, u.email, up.mobile, a.line_1, a.line_2, c.name AS city_name
   FROM user u
   LEFT JOIN user_profile up ON u.id = up.user_id
   LEFT JOIN address a ON up.address_id = a.id
   LEFT JOIN city c ON a.city_id = c.id
   WHERE u.id=?",
  "i", [$userId]
);
$buyer = $buyerQ->fetch_assoc();

// fetch invoice items
$itemsQ = Database::search(
  "SELECT ii.*, p.title, p.level, u.fname AS seller_first, u.lname AS seller_last
   FROM invoice_item ii
   JOIN product p ON ii.product_id = p.id
   JOIN user u ON ii.seller_id = u.id
   WHERE ii.invoice_id=?",
  "i", [$invoice["id"]]
);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - 123456789 | SkillShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="images/phg" href="./assets/images/competence.png">
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif;}
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-10">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- action bar -->
    <div class="flex justify-between items-center mb-6 no-print">
      
      <!-- Back to Dashboard -->
      <a href="buyer-dashboard.php" 
         class="text-blue-600 font-bold hover:text-blue-800 flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Dashboard
      </a>

      <!-- Print Invoice -->
      <button onclick="window.print();" 
              class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-lg shadow hover:bg-black transition-all flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="w-5 h-5" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke="currentColor">
          <path stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M6 9V4h12v5m-12 6h12v5H6v-5zm0 0H4a2 2 0 01-2-2V9a2 2 0 012-2h16a2 2 0 012 2v4a2 2 0 01-2 2h-2"/>
        </svg>
        Print Invoice
      </button>

    </div>

    <!-- invoice paper -->
    <div class="bg-white p-4 md:p-12 rounded-2xl shadow-xl print:shadow-none border border-transparent print:border">
      
      <!-- header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-8 mb-8">
        <div>
          <h1 class="text-4xl font-extrabold text-blue-600 tracking-tight flex items-center gap-2">
            <span>🚀</span>SkillShop
          </h1>
          <p class="text-sm text-gray-500 mt-2">Elevate your skills, elevate your life</p>
        </div>
        <div class="mt-6 md:mt-0 text-left md:text-right">
          <h2 class="text-3xl font-bold text-gray-900 mb-1">INVOICE</h2>
          <p class="font-mono text-gray-600 font-semibold">#<?= $invoice["order_order_id"]; ?></p>
          <p class="text-sm text-gray-500 mt-1">Date: <?= date("M d, Y", strtotime($invoice["date"])); ?></p>
        </div>
      </div>

      <!-- billing + payment -->
      <div class="grid md:grid-cols-2 gap-8 mb-10">
        <div>
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Billed To:</p>
          <h3 class="text-lg font-bold text-gray-900"><?= $buyer["fname"] . " " . $buyer["lname"]; ?></h3>
          <p class="text-sm text-gray-600 mt-1"><?= $buyer["email"]; ?></p>
          <p class="text-sm text-gray-600"><?= $buyer["mobile"]; ?></p>
          <?php if($buyer["line_1"]): ?>
          <p class="mt-2 text-gray-500">
            <?= $buyer["line_1"]; ?> <br/>
            <?= $buyer["line_2"]; ?><br/>
            <?= $buyer["city_name"]; ?>
          </p>
          <?php endif; ?>
        </div>

        <div class="md:text-right flex flex-col items-start md:items-end">
          <p class="text-sm font-bold uppercase text-gray-500 mb-2">Payment Details</p>
          <div class="flex items-center gap-2 bg-green-100 border border-green-200 text-green-700 px-4 py-2 rounded-lg mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 fill="none" viewBox="0 0 24 24" 
                 stroke-width="1.5" stroke="currentColor" 
                 class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            PAID (PayHere)
          </div>
          <p class="text-sm text-gray-700">Amount Paid: Rs. <?= number_format($invoice["total"],2); ?></p>
        </div>
      </div>

      <!-- items table -->
      <div class="overflow-x-auto mb-4">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b-2 border-slate-200">
              <th class="py-4 px-2 text-sm font-bold text-gray-900 uppercase tracking-wider">Course Detail</th>
              <th class="py-4 px-2 text-sm font-bold text-gray-900 uppercase tracking-wider">P. Level</th>
              <th class="py-4 px-2 text-sm font-bold text-gray-900 uppercase tracking-wider">Price</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php while($item = $itemsQ->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50/50 transition-colors">
              <td class="py-5 px-2">
                <p class="font-bold text-gray-900 text-base"><?= $item["title"]; ?></p>
                <p class="text-xs text-gray-500 mt-1">Instructor: <?= $item["seller_first"] . " " . $item["seller_last"] ?></p>
              </td>
              <td class="py-5 px-2">
                <span class="text-xs font-bold px-2 py-1 rounded bg-slate-100 text-slate-600 uppercase"><?= $item["level"] ?></span>
              </td>
              <td class="py-5 px-2 text-right font-semibold text-gray-900">
                Rs. <?= number_format($item["price"],2); ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- total -->
       <div class="flex justify-end pt-6 border-t border-slate-200">
            <div class="w-full max-w-sm space-y-3">
               <div class="flex justify-between text-slate-600">
                  <span>Subtotal</span>
                  <span class="font-medium">Rs. <?= number_format($invoice["subtotal"],2); ?></span>
               </div>
               <div class="flex justify-between text-slate-600">
                  <span>Course Documents delivery fee</span>
                  <span class="font-medium">Rs. <?= number_format($invoice["delivery_fee"],2); ?></span>
               </div>
               <div class="flex justify-between text-gray-900 pt-4 border-t border-slate-200">
                 <span class="text-lg font-bold">Total Paid</span>
                 <span class="text-2xl font-black text-blue-600">Rs. <?= number_format($invoice["total"],2); ?></span>
               </div>
            </div>
            </div>
<!-- footer -->
             <div class="mt-10 ot-8 border-t border-gray-100 text-center text-sm text-gray-500">
                <p class="font-bold text-gray-900 mb-1">Thank you for investing in your skills</p>
                <p>If you have any quetions regarding this invoice,please contact support@skillshop.com</p>
                <p class="mt-4 text-xs font-medium text-gray-400">&copy; <?= date('Y') ?>Skillshop. Al rights reserved.</p>
             </div>

           
       </div>

    </div>
  </div>
</body>
 
</html>