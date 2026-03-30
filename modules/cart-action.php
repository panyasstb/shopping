<?php
require_once '../config/db.php';

$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $p_id = $_POST['p_id'];
    $qty = (int)$_POST['qty'];

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    // ถ้ามีสินค้าเดิมอยู่แล้วให้บวกเพิ่ม
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id] += $qty;
    } else {
        $_SESSION['cart'][$p_id] = $qty;
    }
    echo json_encode(['status' => 'success']);
}

if ($action == 'remove') {
    $p_id = $_POST['p_id'];
    unset($_SESSION['cart'][$p_id]);
    header("Location: ../cart.php");
}
?>