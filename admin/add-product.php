<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

// --- ส่วนประมวลผล (ต้องอยู่บนสุดและ exit ทันทีเมื่อจบ) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'save_product') {
    header('Content-Type: application/json'); // บังคับคืนค่าเป็น JSON
    try {
        // 1. เช็ค SKU ซ้ำก่อน
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE p_sku = ?");
        $stmtCheck->execute([$_POST['p_sku']]);
        if ($stmtCheck->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'รหัส SKU นี้มีในระบบแล้ว']);
            exit;
        }

        // 2. จัดการรูปภาพ
        $image = uploadWebP($_FILES['p_image']);
        if (!$image) {
            echo json_encode(['status' => 'error', 'message' => 'อัปโหลดรูปภาพไม่สำเร็จ']);
            exit;
        }

        // 3. บันทึกข้อมูล
        $stmt = $pdo->prepare("INSERT INTO products (p_sku, p_name, p_slug, p_detail, p_unit, p_price_before_vat, cat_id, p_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['p_sku'], $_POST['p_name'], createSlug($_POST['p_name']), 
            $_POST['p_detail'], $_POST['p_unit'], $_POST['p_price_before_vat'], 
            $_POST['cat_id'], $image
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'บันทึกเรียบร้อย']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit; // สำคัญมาก: ต้องหยุดการทำงานเพื่อไม่ให้ HTML ด้านล่างติดไปกับ JSON
}

// --- ส่วนแสดงผล HTML (ดึงหมวดหมู่) ---
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<script>
// แก้ไข fetch ให้ระบุไฟล์ตรงๆ เพื่อเลี่ยงปัญหา Layout ใน index.php
document.getElementById('productForm').onsubmit = function(e) {
    e.preventDefault();
    Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('add-product.php', { // ส่งไปที่ไฟล์ตัวเอง
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('สำเร็จ!', data.message, 'success').then(() => {
                window.location.href = 'index.php?page=products';
            });
        } else {
            Swal.fire('ผิดพลาด', data.message, 'error');
        }
    }).catch(err => {
        Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
    });
};
</script>

<div class="card border-0 shadow-sm p-4 mx-auto" style="max-width: 850px;">
    <h4 class="fw-bold mb-4"><i class="fas fa-plus-circle me-2 text-primary"></i>เพิ่มสินค้าใหม่</h4>
    <form id="productForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save_product">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">รหัสสินค้า (SKU)</label>
                <input type="text" name="p_sku" class="form-control" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">ชื่อสินค้า</label>
                <input type="text" name="p_name" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">หน่วยสินค้า</label>
                <input type="text" name="p_unit" class="form-control" placeholder="เช่น ชิ้น, กล่อง" required>
            </div>
            
            <div class="col-md-12">
                <label class="form-label small fw-bold">รายละเอียดสินค้า</label>
                <textarea name="p_detail" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold">หมวดหมู่</label>
                <select name="cat_id" class="form-select" required>
                    <option value="">เลือกหมวดหมู่...</option>
                    <?php foreach($cats as $c): ?>
                        <option value="<?= $c['cat_id'] ?>"><?= $c['cat_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-8">
                <label class="form-label small fw-bold">รูปภาพสินค้า (.jpg, .png จะถูกแปลงเป็น .webp)</label>
                <input type="file" name="p_image" class="form-control" accept="image/*" required>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-primary">ราคาก่อน VAT</label>
                <input type="number" id="price_before" name="p_price_before_vat" class="form-control" step="0.01" value="0.00" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">ภาษีมูลค่าเพิ่ม (7%)</label>
                <input type="text" id="vat_amount" class="form-control bg-light" readonly value="0.00">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-success">ราคารวม VAT</label>
                <input type="text" id="price_after" class="form-control bg-light fw-bold text-success" readonly value="0.00">
            </div>

            <div class="col-12 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">บันทึกสินค้า</button>
                <a href="index.php?page=products" class="btn btn-light px-4 py-2 border">ยกเลิก</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ระบบคำนวณ VAT อัตโนมัติ
document.getElementById('price_before').addEventListener('input', function() {
    let price = parseFloat(this.value) || 0;
    let vat = price * 0.07;
    let total = price + vat;

    document.getElementById('vat_amount').value = vat.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('price_after').value = total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
});

// บันทึกข้อมูลด้วย AJAX + SweetAlert2
document.getElementById('productForm').onsubmit = function(e) {
    e.preventDefault();
    Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('add-product.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('สำเร็จ!', data.message, 'success').then(() => {
                window.location.href = 'index.php?page=products';
            });
        } else {
            Swal.fire('ผิดพลาด', data.message, 'error');
        }
    });
};
</script>