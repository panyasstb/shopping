<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!empty($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'];
    $total_price = 0;

    // คำนวณยอดรวมสุทธิอีกครั้งเพื่อความถูกต้อง
    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $pdo->prepare("SELECT p_price_before_vat FROM products WHERE p_id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch();
        $vat = formatVat($p['p_price_before_vat']);
        $total_price += ($vat['raw_total'] * $qty);
    }

    // บันทึกคำสั่งซื้อ
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    if ($stmt->execute([$user_id, $total_price])) {
        unset($_SESSION['cart']); // ล้างตะกร้า
        echo "<script>alert('สั่งซื้อสำเร็จ!'); window.location='../history.php';</script>";
    }
}
?>