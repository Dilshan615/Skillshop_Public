<?php
session_start();
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] != true) {
    header("Location: admin-login.php");
    exit();
}

require_once "db/connection.php";

$todayStr = date("Y-m-d");

// Overall stats
$totalSoldItemsQ = Database::search("SELECT COUNT(*) as `c` FROM `invoice_item`");
$totalSoldItems = $totalSoldItemsQ ? $totalSoldItemsQ->fetch_assoc()["c"] : 0;

$totalRevenueQ = Database::search("SELECT SUM(`total`) as `s` FROM `invoice`");
$totalRevenue = ($totalRevenueQ && $row = $totalRevenueQ->fetch_assoc()) ? ($row["s"] ?? 0) : 0;

$pendingDeliveriesQ = Database::search("SELECT COUNT(*) as `c` FROM `invoice_item` WHERE `delivery_status` = 'pending'");
$pendingDeliveries = $pendingDeliveriesQ ? $pendingDeliveriesQ->fetch_assoc()["c"] : 0;

// Daily Selling Stats
$todaySalesCountQ = Database::search(
    "SELECT COUNT(ii.`id`) as `c` 
     FROM `invoice_item` ii 
     JOIN `invoice` i ON ii.`invoice_id` = i.`id` 
     WHERE DATE(i.`date`) = ?",
    "s",
    [$todayStr]
);
$todaySalesCount = $todaySalesCountQ ? $todaySalesCountQ->fetch_assoc()["c"] : 0;

$todayEarningsQ = Database::search(
    "SELECT SUM(ii.`price`) as `s` 
     FROM `invoice_item` ii 
     JOIN `invoice` i ON ii.`invoice_id` = i.`id` 
     WHERE DATE(i.`date`) = ?",
    "s",
    [$todayStr]
);
$todayEarnings = ($todayEarningsQ && $row = $todayEarningsQ->fetch_assoc()) ? ($row["s"] ?? 0) : 0;

// Query all transactions
$query = "SELECT 
            ii.id AS item_id,
            ii.price AS item_price,
            ii.delivery_status,
            i.order_order_id AS order_id,
            i.date AS invoice_date,
            i.delivery_fee AS invoice_delivery_fee,
            p.title AS product_title,
            p.image_url AS product_image,
            c.name AS category_name,
            buyer.fname AS buyer_fname,
            buyer.lname AS buyer_lname,
            buyer.email AS buyer_email,
            bp.mobile AS buyer_mobile,
            ba.line_1 AS buyer_line1,
            ba.line_2 AS buyer_line2,
            bcity.name AS buyer_city_name,
            seller.fname AS seller_fname,
            seller.lname AS seller_lname
          FROM `invoice_item` ii
          JOIN `invoice` i ON ii.`invoice_id` = i.`id`
          JOIN `product` p ON ii.`product_id` = p.`id`
          JOIN `category` c ON p.`category_id` = c.`id`
          JOIN `user` buyer ON i.`user_id` = buyer.`id`
          LEFT JOIN `user_profile` bp ON buyer.`id` = bp.`user_id`
          LEFT JOIN `address` ba ON bp.`address_id` = ba.`id`
          LEFT JOIN `city` bcity ON ba.`city_id` = bcity.`id`
          JOIN `user` seller ON ii.`seller_id` = seller.`id`
          ORDER BY i.`date` DESC";
$transactions = Database::search($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History & Deliveries | SkillShop Admin</title>
    <link rel="icon" type="images/png" href="assets/images/favicon.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Printable stylesheet formatting */
        @media print {
            body {
                background-color: white !important;
                color: black !important;
                font-size: 12px;
            }

            /* Hide everything but print contents */
            aside,
            header,
            .no-print,
            .action-buttons,
            button,
            input,
            .tab-container {
                display: none !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .print-header {
                display: block !important;
            }

            .shadow-sm,
            .shadow-md,
            .shadow-lg,
            .shadow-2xl {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th,
            td {
                padding: 8px 12px !important;
                border: 1px solid #e2e8f0 !important;
            }
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">
    <div class="flex flex-col md:flex-row min-h-screen">

        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-slate-900 text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-blue-500">SkillShop</h1>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest">Admin Control</p>
            </div>

            <nav class="mt-6 px-4 space-y-2">
                <a href="admin-dashboard.php"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>📊</span> Dashboard
                </a>
                <a href="admin-users.php"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>👥</span> User Management
                </a>
                <a href="admin-products.php"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>🛍️</span> Product Management
                </a>
                <a href="admin-trasactions.php"
                    class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20">
                    <span>📜</span> Transactions
                </a>
                <a href="admin-dashboard.php?openMessages=true"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>💬</span> Support Messages
                </a>
            </nav>

            <div class="mt-auto p-6 border-t border-slate-800">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center font-bold">A</div>
                    <div>
                        <p class="text-sm font-bold"><?= $_SESSION["admin_fname"] ?></p>
                        <p class="text-[10px] text-slate-400 uppercase">Administrator</p>
                    </div>
                </div>
                <a href="process/adminLogoutProcess.php"
                    class="block w-full text-center py-2 bg-slate-800 hover:bg-red-900/40 hover:text-red-400 rounded-lg text-xs font-bold transition-all border border-slate-700">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Nav -->
            <header
                class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
                <h2 class="text-xl font-extrabold text-slate-900">Product Selling History</h2>
                <div class="flex items-center gap-4">
                    <button
                        class="px-4 py-2 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-black transition-all">
                        📄 Print Report
                    </button>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <?= date("D,M j, Y") ?>
                    </span>
                </div>
            </header>

            <!-- Dashboard content -->
            <div class="p-8">

                <!-- Status Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 no-print">

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-green-50 text-green-600 rounded-xl text-xl">💰</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">Revenue</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</p>
                        <h3 class="text-2xl font-black text-slate-900">Rs. 1000.00</h3>
                    </div>
                    <!-- Total items sold  -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-green-50 text-green-600 rounded-xl text-xl">🛍️</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">Pending</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</p>
                        <h3 class="text-2xl font-black text-slate-900">5</h3>
                    </div>
                    <!-- pending deliveries  -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-green-50 text-green-600 rounded-xl text-xl">🚚</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">Pending</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</p>
                        <h3 class="text-2xl font-black text-slate-900">3</h3>
                    </div>
                    <!-- Daily Saies  -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-green-50 text-green-600 rounded-xl text-xl">⚡</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">Today</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Today's Sales</p>
                        <h3 class="text-2xl font-black text-slate-900">1</h3>
                    </div>
                </div>

                <!-- Real-time Results (During Filterning) -->
                <div class="mb-6 p-4 bg-blue-50 rounded-2xl border border-blue-100 flex flex-wrap gap-6 items-center justify-between text-sm text-blue-900">
                    <div class="flex gap-6 flex-wrap">
                        <p class="font-medium">Filtered Results: <span id="filteredCount" class="font-bold text-blue-700">0</span> items</p>
                        <p class="font-medium">Total Value: <span id="filteredEarnings" class="font-bold text-blue-700">Rs. 0.00</span></p>
                        <p class="font-medium">Pending Delivery: <span id="filteredPending" class="font-bold text-blue-700">0</span> items</p>
                    </div>
                    <span class="text-xs font-bold text-blue-500 uppercase bg-blue-100/50 px-2.5 py-1 rounded-lg">Real-time stats</span>
                </div>
                <!-- Filters & Control Bar (no-print)  -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8 no-print">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                        <!-- Search Box -->
                        <div class="lg:col-span-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Search Transactions</label>
                            <div class="relative">
                                <input type="text" id="searchBox" placeholder="Invoice ID, Buyer, Seller, Product..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-blue-500/10 focus:ring-4 outline-none transition-all" />
                                <span class="absolute left-3.5 top-3 text-slate-400">🔍</span>
                            </div>
                        </div>
                        <!-- Date Range  -->
                        <div class="lg:col-span-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                                    Start Date
                                </label>
                                <input type="date" id="startDate" onchange="" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 outline-none ">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                                    End Date
                                </label>
                                <input type="date" id="endDate" onchange="" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 outline-none ">
                            </div>
                        </div>

                        <!-- Clear filter button -->
                        <div class="lg:col-span-4 flex items-end h-full ">
                            <button class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all active:scale-95">
                                Clear Filters
                            </button>
                        </div>

                    </div>

                    <!-- Tab Buttons Container  -->
                    <div class="flex gap-2 border-t border-slate-100 mt-6 pt-6 flex-wrap">
                        <button class="tab-btn active px-5 py-2.5 text-xs font-bold rounded-xl border transition-all bg-blue-600 text-white border-blue-600 shadow-sm shadow-blue-500/10" data-tab="all">All Transactions</button>
                        <button class="tab-btn px-5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-all" data-tab="pending">Pending Deliveries</button>
                        <button class="tab-btn px-5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-all" data-tab="today">Today's Sales (Daily)</button>
                    </div>

                </div>
                <!-- Transaction Table Container -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="transactionTable">
                            <thead>
                                <tr class="bg-slate-50/50 text-slate-500 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4">Transaction / Order ID</th>
                                    <th class="px-6 py-4">Product Info</th>
                                    <th class="px-6 py-4">Seller</th>
                                    <th class="px-6 py-4">Buyer & Shipping Info</th>
                                    <th class="px-6 py-4">Cost</th>
                                    <th class="px-6 py-4">Delivery Status</th>
                                    <th class="px-6 py-4 text-right no-print">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="hover:bg-slate-50/30 transition-colors transition-row">

                                    <!-- Order ID -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900 font-mono">
                                                000000001
                                            </span>
                                            <span class="text-[10px] text-slate-400 mt-0.5">
                                                Aug 11 2026 12:44 PM
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Product Details -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="uploads/products/product_4_1772188593_cbbcb925.jpg"
                                                class="w-10 h-10 rounded-lg object-cover bg-slate-50 border border-slate-100 flex-shrink-0" />
                                            <div>
                                                <p class="text-sm font-bold text-slate-900 leading-snug line-clamp-1">Electronic Music Production 101</p>
                                                <span class="text-[9px] font-black uppercase text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded mt-1 inline-block">Education</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Seller -->
                                    <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                        Sanjaya Suraweera
                                    </td>

                                    <!-- Buyer & Address Details -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col text-xs text-slate-600 leading-relaxed">
                                            <span class="font-bold text-slate-900 text-sm">Sahan Perera</span>
                                            <span class="text-slate-500 font-mono">sahan@gmail.com</span>
                                            <span class="text-slate-500">0771234567</span>

                                            <!-- Address Block -->
                                            <div class="mt-1.5 p-2 bg-slate-50 border border-slate-100 rounded-lg text-[10px] text-slate-500 relative">
                                                <p class="font-medium text-slate-600">Alawwa</p>
                                                <p class="font-bold text-slate-700 mt-0.5">City: Kuruegala</p>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Price & Fee -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">Rs.1000.00</span>
                                            <span class="text-[9px] text-slate-400 mt-0.5 uppercase tracking-wider">Deliv: Rs.500.00</span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        <div class="status-container">
                                            <span class="status-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase transition-all duration-300 bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">
                                                <span class="status-dot w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                <span class="status-text">Pending</span>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Action Button -->
                                    <td class="px-6 py-4 text-right no-print">
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="toggle-action-btn px-3 py-1.5 rounded-xl text-[10px] font-bold transition-all border shadow-sm active:scale-95 bg-green-600 border-green-700 hover:bg-green-700 text-white shadow-green-500/70">
                                                Mark Delivered
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>

</html>