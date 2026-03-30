<?php
require_once 'config/db.php';
checkLogin();

// รับค่า Filter
$year = $_GET['year'] ?? date('Y');

$sql = "SELECT 
            MONTH(created_at) as month, 
            COUNT(order_id) as total_orders, 
            SUM(total_price) as monthly_sales 
        FROM orders 
        WHERE user_id = ? AND YEAR(created_at) = ?
        GROUP BY month ORDER BY month DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id'], $year]);
$history = $stmt->fetchAll();
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h3>ประวัติการซื้อปี <?= $year ?></h3>
        <select onchange="location.href='history.php?year='+this.value" class="form-select w-25">
            <option value="2024" <?= $year == '2024' ? 'selected' : '' ?>>2024</option>
            <option value="2023" <?= $year == '2023' ? 'selected' : '' ?>>2023</option>
        </select>
    </div>
    <div class="row">
        <?php foreach($history as $h): ?>
        <div class="col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        เดือน: <?= date("F", mktime(0, 0, 0, $h['month'], 10)) ?>
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($h['monthly_sales'], 2) ?> ฿</div>
                    <div class="text-muted small">จำนวน <?= $h['total_orders'] ?> รายการ</div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>