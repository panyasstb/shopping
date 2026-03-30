<?php
require 'config/db.php';
checkLogin();

// สรุปยอดตามปีและเดือน
$sql = "SELECT YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total 
        FROM orders WHERE user_id = ? 
        GROUP BY year, month ORDER BY year DESC, month DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$history = $stmt->fetchAll();
?>
<div class="container mt-5">
    <h3>ประวัติการสั่งซื้อย้อนหลัง</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ปี</th>
                <th>เดือน</th>
                <th>ยอดรวมสุทธิ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($history as $h): ?>
            <tr>
                <td><?= $h['year'] ?></td>
                <td><?= date("F", mktime(0, 0, 0, $h['month'], 10)) ?></td>
                <td><?= number_format($h['total'], 2) ?> ฿</td>
                <td><a href="view_order.php?m=<?= $h['month'] ?>&y=<?= $h['year'] ?>" class="btn btn-sm btn-info">ดูรายละเอียด</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>