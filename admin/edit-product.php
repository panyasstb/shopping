<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

// 1. ดึงข้อมูลสินค้าเดิม
$id = $_GET['id'] ?? null;
if (!$id) { echo "ไม่พบรหัสสินค้า"; exit; }

$stmt = $pdo->prepare("SELECT * FROM products WHERE p_id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) { echo "ไม่พบข้อมูลสินค้า"; exit; }

// 2. ดึงหมวดหมู่ทั้งหมด
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();

// 3. Logic การอัปเดต (AJAX)
// --- ส่วนประมวลผลอัปเดต ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_product') {
    header('Content-Type: application/json');
    try {
        $stmtOld = $pdo->prepare("SELECT p_image FROM products WHERE p_id = ?");
        $stmtOld->execute([$id]);
        $oldImage = $stmtOld->fetchColumn();

        $image = $oldImage;
        if (!empty($_FILES['p_image']['name'])) {
            $image = uploadWebP($_FILES['p_image']);
        }

        $stmt = $pdo->prepare("UPDATE products SET p_sku=?, p_name=?, p_slug=?, p_detail=?, p_unit=?, p_price_before_vat=?, cat_id=?, p_image=? WHERE p_id=?");
        $stmt->execute([
            $_POST['p_sku'], $_POST['p_name'], createSlug($_POST['p_name']), 
            $_POST['p_detail'], $_POST['p_unit'], $_POST['p_price_before_vat'], 
            $_POST['cat_id'], $image, $id
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'แก้ไขสำเร็จ']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ดึงข้อมูลโชว์ในฟอร์ม (เหมือนเดิม)
$stmt = $pdo->prepare("SELECT * FROM products WHERE p_id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="card border-0 shadow-sm p-4 mx-auto" style="max-width: 850px;">
    <h4 class="fw-bold mb-4"><i class="fas fa-edit me-2 text-warning"></i>แก้ไขข้อมูลสินค้า</h4>
    <form id="editProductForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_product">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">รหัสสินค้า (SKU)</label>
                <input type="text" name="p_sku" class="form-control" value="<?= $p['p_sku'] ?>" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">ชื่อสินค้า</label>
                <input type="text" name="p_name" class="form-control" value="<?= $p['p_name'] ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">หน่วยสินค้า</label>
                <input type="text" name="p_unit" class="form-control" value="<?= $p['p_unit'] ?>" required>
            </div>
            
            <div class="col-md-12">
                <label class="form-label small fw-bold">รายละเอียดสินค้า</label>
                <textarea name="p_detail" class="form-control" rows="3"><?= $p['p_detail'] ?></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold">หมวดหมู่</label>
                <select name="cat_id" class="form-select" required>
                    <?php foreach($cats as $c): ?>
                        <option value="<?= $c['cat_id'] ?>" <?= ($c['cat_id'] == $p['cat_id']) ? 'selected' : '' ?>>
                            <?= $c['cat_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-8">
                <label class="form-label small fw-bold">รูปภาพ (ปล่อยว่างหากไม่เปลี่ยน)</label>
                <input type="file" name="p_image" class="form-control" accept="image/*">
                <div class="mt-2 text-muted small">รูปปัจจุบัน: <?= $p['p_image'] ?></div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-primary">ราคาก่อน VAT</label>
                <input type="number" id="price_before" name="p_price_before_vat" class="form-control" step="0.01" value="<?= $p['p_price_before_vat'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">ภาษี (7%)</label>
                <input type="text" id="vat_amount" class="form-control bg-light" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-success">ราคารวม VAT</label>
                <input type="text" id="price_after" class="form-control bg-light fw-bold text-success" readonly>
            </div>

            <div class="col-12 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-warning px-5 py-2 fw-bold text-white">บันทึกการแก้ไข</button>
                <a href="index.php?page=products" class="btn btn-light px-4 py-2 border">ยกเลิก</a>
            </div>
        </div>
    </form>
</div>



<script>
document.getElementById('editProductForm').onsubmit = function(e) {
    e.preventDefault();
    Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    // แก้ไข Path ตรงนี้ให้ส่งไปที่ edit-product.php โดยตรงแทน index.php
    fetch('edit-product.php?id=<?= $id ?>', { 
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

<script>
// ฟังก์ชันคำนวณ VAT (เหมือนหน้าเพิ่มสินค้า)
function calculateVAT() {
    let price = parseFloat(document.getElementById('price_before').value) || 0;
    let vat = price * 0.07;
    let total = price + vat;
    document.getElementById('vat_amount').value = vat.toFixed(2);
    document.getElementById('price_after').value = total.toFixed(2);
}

document.getElementById('price_before').addEventListener('input', calculateVAT);
window.onload = calculateVAT; // คำนวณทันทีเมื่อโหลดหน้า

// ส่งข้อมูลแก้ไขด้วย AJAX
document.getElementById('editProductForm').onsubmit = function(e) {
    e.preventDefault();
    Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('index.php?page=edit-product&id=<?= $id ?>', {
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