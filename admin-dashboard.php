<?php
session_start();
require "./db/connection.php";

// Admin access validation
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] == false) {
    header("Location:admin-login.php");
    exit();
}

$admin_email = $_SESSION["admin_email"];
// Fetch admin name if possible (assuming admin table has name, or fetching from email)
// For now let's use a placeholder or check if admin table has a name column.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Overview | SkillShop Admin</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/competence.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-item-active {
            background: linear-gradient(90deg, #2563eb 0%, #4f46e5 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-[#f8fafc] min-h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-72 bg-[#1e293b] text-slate-400 flex flex-col flex-shrink-0 h-screen overflow-y-auto">
        <!-- Logo Section -->
        <div class="p-8 border-b border-white/5">
            <h1 class="text-2xl font-bold text-white tracking-tight">SkillShop</h1>
            <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-slate-500 mt-1">Admin Control</p>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-8 space-y-2">
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl sidebar-item-active group">
                <i class="fas fa-th-large w-5 text-center"></i>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                <i class="fas fa-users-cog w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-sm">User Management</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                <i class="fas fa-box-open w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-sm">Product Management</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 hover:text-white transition-all group">
                <i class="fas fa-receipt w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-sm">Transactions</span>
            </a>
        </nav>

        <!-- Profile & Logout -->
        <div class="p-6 bg-[#172130] border-t border-white/5">
            <div class="flex items-center space-x-4 mb-6 px-2">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    <?php echo substr($_SESSION["admin_fname"], 0, 1); ?>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm"><?php echo $_SESSION["admin_fname"]; ?></h4>
                    <p class="text-[10px] uppercase font-bold tracking-widest text-slate-500">Administrator</p>
                </div>
            </div>
            <a href="process/adminLogoutProcess.php" onclick="return confirm('Are you sure you want to sign out?')" class="w-full flex items-center justify-center py-3 bg-white/5 hover:bg-red-500/10 hover:text-red-500 text-white font-bold text-xs uppercase tracking-widest rounded-xl transition-all border border-white/10 text-center">
                Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto overflow-x-hidden flex flex-col h-screen">
        <!-- Top Bar -->
        <header class="bg-white border-b border-slate-100 px-10 py-6 flex items-center justify-between sticky top-0 z-30">
            <h2 class="text-xl font-bold text-slate-800">Dashboard Overview</h2>
            <div class="flex items-center space-x-8">
                <button class="relative text-orange-400 hover:text-orange-500 transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="text-[11px] font-bold uppercase tracking-widest text-slate-400 border-l border-slate-100 pl-8">
                    <?php echo date('D, M d'); ?>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="p-10 space-y-10">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Total Users -->
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm stat-card relative overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-users-rectangle text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-green-500 bg-green-50 px-2.5 py-1 rounded-full">+12%</span>
                    </div>
                    <h3 class="text-slate-400 text-[10px] font-extrabold uppercase tracking-[0.2em] mb-2">Total Users</h3>
                    <?php
                    $resUser = Database::search("SELECT COUNT(*) as count FROM `user` ");
                    $userCount = $resUser->fetch_assoc()["count"];
                    ?>
                    <p id="totalUsers" class="text-4xl font-bold text-slate-900"><?php echo $userCount; ?></p>
                </div>

                <!-- Active Products -->
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm stat-card">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-laptop-code text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Skills</span>
                    </div>
                    <h3 class="text-slate-400 text-[10px] font-extrabold uppercase tracking-[0.2em] mb-2">Active Products</h3>
                    <?php
                    $resProd = Database::search("SELECT COUNT(*) as count FROM `product` WHERE `status`='active'");
                    $prodCount = $resProd->fetch_assoc()["count"];
                    ?>
                    <p id="activeProducts" class="text-4xl font-bold text-slate-900"><?php echo $prodCount; ?></p>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm stat-card">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest flex items-center">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span> LIVE
                        </span>
                    </div>
                    <h3 class="text-slate-400 text-[10px] font-extrabold uppercase tracking-[0.2em] mb-2">Total Revenue</h3>
                    <?php
                    $resRev = Database::search("SELECT SUM(`total`) as revenue FROM `invoice` ");
                    $revenue = $resRev->fetch_assoc()["revenue"] ?? 0;
                    ?>
                    <p id="totalRevenue" class="text-3xl font-bold text-slate-900">Rs. <?php echo number_format($revenue, 2); ?></p>
                </div>

                <!-- Total Orders -->
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm stat-card">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-orange-50 text-orange-400 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-scroll text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Scales</span>
                    </div>
                    <h3 class="text-slate-400 text-[10px] font-extrabold uppercase tracking-[0.2em] mb-2">Total Orders</h3>
                    <?php
                    $resOrder = Database::search("SELECT COUNT(*) as count FROM `order` ");
                    $orderCount = $resOrder->fetch_assoc()["count"];
                    ?>
                    <p id="totalOrders" class="text-4xl font-bold text-slate-900"><?php echo $orderCount; ?></p>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div class="bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-10 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">Recent Transactions</h3>
                    <a href="#" class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] hover:text-blue-700 transition-colors">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-slate-400 border-y border-slate-50">
                                <th class="px-10 py-6">Order ID</th>
                                <th class="px-6 py-6">Buyer</th>
                                <th class="px-6 py-6 text-center">Product</th>
                                <th class="px-6 py-6 text-center">Amount</th>
                                <th class="px-6 py-6 text-center">Status</th>
                                <th class="px-10 py-6 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody id="txnTable" class="divide-y divide-slate-50">
                            <?php
                            $resT = Database::search("SELECT i.*, u.fname, u.lname, (SELECT p.title FROM product p JOIN invoice_item ii ON p.id = ii.product_id WHERE ii.invoice_id = i.id LIMIT 1) as prod_title FROM `invoice` i JOIN `user` u ON i.user_id = u.id ORDER BY i.date DESC LIMIT 5");
                            if ($resT->num_rows > 0) {
                                while ($row = $resT->fetch_assoc()) {
                            ?>
                                    <tr class="hover:bg-slate-50/50 transition-all cursor-pointer group">
                                        <td class="px-10 py-8 text-sm font-bold text-slate-900">#<?php echo $row["order_order_id"]; ?></td>
                                        <td class="px-6 py-8">
                                            <div class="font-semibold text-slate-600 text-sm"><?php echo $row["fname"] . " " . $row["lname"]; ?></div>
                                        </td>
                                        <td class="px-6 py-8 text-center">
                                            <div class="text-slate-600 text-sm font-medium"><?php echo $row["prod_title"] ?? 'Bundle Purchase'; ?></div>
                                        </td>
                                        <td class="px-6 py-8 text-center">
                                            <div class="text-slate-900 font-bold text-sm"><?php echo number_format($row["total"], 2); ?></div>
                                        </td>
                                        <td class="px-6 py-8 text-center">
                                            <span class="px-4 py-1.5 bg-green-50 text-green-600 text-[10px] font-extrabold rounded-lg uppercase tracking-wider">Completed</span>
                                        </td>
                                        <td class="px-10 py-8 text-right">
                                            <div class="text-slate-400 text-[11px] font-bold"><?php echo date('M d, Y', strtotime($row["date"])); ?></div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" class="px-10 py-20 text-center text-slate-400 italic">No transactions found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateStats() {
            var r = new XMLHttpRequest();
            r.open("GET", "process/getDashboardStats.php", true);
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    var data = JSON.parse(r.responseText);
                    document.getElementById("totalUsers").innerText = data.userCount;
                    document.getElementById("activeProducts").innerText = data.prodCount;
                    document.getElementById("totalRevenue").innerText = "Rs. " + data.revenue;
                    document.getElementById("totalOrders").innerText = data.orderCount;
                }
            };
            r.send();
        }

        function updateTransactions() {
            var r = new XMLHttpRequest();
            r.open("GET", "process/getRecentTransactions.php", true);
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    document.getElementById("txnTable").innerHTML = r.responseText;
                }
            };
            r.send();
        }

        // Live updates every 10 seconds
        setInterval(() => {
            updateStats();
            updateTransactions();
        }, 10000);
    </script>
</body>

</html>