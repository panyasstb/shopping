<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
checkLogin();

$cart_items = $_SESSION['cart'] ?? [];
$grand_total = 0;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">ตะกร้าสินค้าของคุณ</h3>
    <div class="card shadow-sm p-4">
        <table class="table align-middle">
            <thead><tr><th>สินค้า</th><th>จำนวน</th><th>ราคา/หน่วย (รวม VAT)</th><th>รวม</th><th></th></tr></thead>
            <tbody>
                <?php 
                if(empty($cart_items)) echo "<tr><td colspan='5' class='text-center'>ไม่มีสินค้าในตะกร้า</td></tr>";
                foreach($cart_items as $id => $qty): 
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE p_id = ?");
                    $stmt->execute([$id]);
                    $p = $stmt->fetch();
                    $vat = formatVat($p['p_price_before_vat']);
                    $item_total = $vat['raw_total'] * $qty;
                    $grand_total += $item_total;
                ?>
                <tr>
                    <td><img src="uploads/<?= $p['p_image'] ?>" width="50"> <?= $p['p_name'] ?></td>
                    <td><?= $qty ?></td>
                    <td><?= $vat['total'] ?> ฿</td>
                    <td><?= number_format($item_total, 2) ?> ฿</td>
                    <td>
                        <form action="modules/cart-action.php" method="POST">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="p_id" value="<?= $id ?>">
                            <button type="submit" class="btn btn-sm btn-danger">ลบ</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-end mt-3">
            <h4>ยอดรวมสุทธิทั้งสิ้น: <span class="text-primary"><?= number_format($grand_total, 2) ?> ฿</span></h4>
            <button class="btn btn-success btn-lg mt-2">ยืนยันการสั่งซื้อ</button>
        </div>
    </div>
</div>
</body>
</html>