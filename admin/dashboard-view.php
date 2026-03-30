<?php
// สถิติต่างๆ
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT SUM(total_price) as daily_total FROM orders WHERE DATE(created_at) = ?");
$stmt->execute([$today]);
$daily_sales = $stmt->fetch()['daily_total'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(order_id) as total_order FROM orders");
$total_orders = $stmt->fetch()['total_order'];

$chart_data = $pdo->query("SELECT DATE(created_at) as date, SUM(total_price) as total 
                           FROM orders GROUP BY DATE(created_at) 
                           ORDER BY date DESC LIMIT 7")->fetchAll();
?>
<div class="main-header">
    <h3 class="fw-bold">ระบบบริหารจัดการหลังบ้าน</h3>
    <p class="text-muted">ยินดีต้อนรับผู้ดูแลระบบ, นี่คือสรุปยอดขายปัจจุบัน</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card bg-white p-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="fas fa-wallet text-primary fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">ยอดขายวันนี้</h6>
                    <h3 class="mb-0 fw-bold"><?= number_format($daily_sales, 2) ?> ฿</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white p-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="fas fa-shopping-cart text-success fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">จำนวนสั่งซื้อ</h6>
                    <h3 class="mb-0 fw-bold"><?= $total_orders ?> รายการ</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="card p-4">
            <h5 class="fw-bold mb-4">แนวโน้มยอดขาย (7 วันล่าสุด)</h5>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php foreach(array_reverse($chart_data) as $d) echo "'".date('d M', strtotime($d['date']))."',"; ?>],
            datasets: [{
                label: 'ยอดขาย',
                data: [<?php foreach(array_reverse($chart_data) as $d) echo $d['total'].","; ?>],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                fill: true,
                tension: 0.4
            }]
        }
    });
</script>