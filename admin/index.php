<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
// checkAdmin(); // สมมติว่ามีฟังก์ชันเช็คสิทธิ์ Admin

// 1. ยอดขายวันนี้
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT SUM(total_price) as daily_total FROM orders WHERE DATE(created_at) = ?");
$stmt->execute([$today]);
$daily_sales = $stmt->fetch()['daily_total'] ?? 0;

// 2. จำนวนคำสั่งซื้อทั้งหมด
$stmt = $pdo->query("SELECT COUNT(order_id) as total_order FROM orders");
$total_orders = $stmt->fetch()['total_order'];

// 3. ดึงข้อมูล 7 วันย้อนหลังเพื่อทำกราฟ
$chart_data = $pdo->query("SELECT DATE(created_at) as date, SUM(total_price) as total 
                           FROM orders GROUP BY DATE(created_at) 
                           ORDER BY date DESC LIMIT 7")->fetchAll();
?>
<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
// checkAdmin(); // เปิดใช้งานเมื่อมีระบบเช็คสิทธิ์

$page = $_GET['page'] ?? 'dashboard'; // ค่าเริ่มต้นคือหน้า dashboard
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; }
        .wrapper { display: flex; align-items: stretch; }
        #content { width: 100%; padding: 20px; min-height: 100vh; transition: all 0.3s; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .main-header { margin-bottom: 30px; }
    </style>
</head>
<body>

<div class="wrapper">
    <?php include 'sidebar-admin.php'; ?>

    <div id="content">
        <div class="container-fluid">
            <?php 
                // ตรวจสอบและ Include ไฟล์ตามหน้า
                // ในไฟล์ admin/index.php หาบรรทัด switch($page)
				switch($page) {
					case 'products':
						include 'products.php';
						break;
					case 'edit-product': // เพิ่มบรรทัดนี้
						include 'edit-product.php';
						break;
					case 'categories':
						include 'categories.php';
						break;
					case 'add-product':
						include 'add-product.php';
						break;
					default:
						include 'dashboard-view.php';
						break;

                }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// ตรวจสอบว่าไฟล์ที่เรียกใช้อยู่ลึกแค่ไหน (เพื่อปรับ path กลับไปที่ root)
$current_dir = dirname($_SERVER['PHP_SELF']);
$base_path = (strpos($current_dir, 'modules') !== false || strpos($current_dir, 'auth') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light"> <?php include '../admin/sidebar-admin.php'; ?>
    <div class="d-flex">


        <div class="p-4 w-100">
            <h3>สรุปภาพรวมระบบ</h3>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white shadow-sm p-3">
                        <h5>ยอดขายวันนี้</h5>
                        <h2><?= number_format($daily_sales, 2) ?> ฿</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white shadow-sm p-3">
                        <h5>คำสั่งซื้อทั้งหมด</h5>
                        <h2><?= $total_orders ?> รายการ</h2>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-8">
                    <div class="card shadow-sm p-4">
                        <h5>แนวโน้มยอดขาย (7 วันล่าสุด)</h5>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
	</div>
	

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php foreach(array_reverse($chart_data) as $d) echo "'".$d['date']."',"; ?>],
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: [<?php foreach(array_reverse($chart_data) as $d) echo $d['total'].","; ?>],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    </script>
</body>
</html>