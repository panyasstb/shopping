<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
checkLogin(); // ฟังก์ชันตรวจสอบการ Login จากไฟล์ functions.php

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM products WHERE p_slug = ?");
$stmt->execute([$slug]);
$p = $stmt->fetch();

if (!$p) { echo "ไม่พบสินค้า"; exit; }
$vatInfo = formatVat($p['p_price_before_vat']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?= $p['p_name'] ?></title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="row g-0">
                <div class="col-md-6 text-center p-4">
                    <img src="../uploads/<?= $p['p_image'] ?>" class="img-fluid rounded" alt="product">
                </div>
                <div class="col-md-6 p-4">
                    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../shop.php">หน้าหลัก</a></li><li class="breadcrumb-item active"><?= $p['p_sku'] ?></li></ol></nav>
                    <h2 class="fw-bold"><?= $p['p_name'] ?></h2>
                    <p class="text-muted"><?= nl2br($p['p_detail']) ?></p>
                    <hr>
                    <h5 class="text-muted">ราคาก่อน VAT: <?= $vatInfo['before'] ?> ฿</h5>
                    <h3 class="text-success fw-bold">ราคาสุทธิ (รวม VAT 7%): <?= $vatInfo['total'] ?> ฿</h3>
                    <div class="mt-4">
                        <input type="number" id="qty" value="1" min="1" class="form-control d-inline-block w-25 mb-2">
                        <button onclick="addToCart(<?= $p['p_id'] ?>)" class="btn btn-primary btn-lg w-100">เพิ่มลงตะกร้าสินค้า</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function addToCart(id) {
        const qty = document.getElementById('qty').value;
        fetch('../modules/cart-action.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=add&p_id=${id}&qty=${qty}`
        }).then(res => res.json()).then(data => {
            if(data.status === 'success') {
                Swal.fire('สำเร็จ', 'เพิ่มสินค้าลงตะกร้าแล้ว', 'success');
            }
        });
    }
    </script>
</body>
</html>