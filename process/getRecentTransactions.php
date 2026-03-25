<?php
require "../db/connection.php";

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
<?php 
} 
?>
