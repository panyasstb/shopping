<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

// รับค่าการค้นหาและตัวกรอง
$search = $_GET['search'] ?? '';
$filter_cat = $_GET['cat_id'] ?? '';

// สำหรับลบสินค้า (AJAX)
if (isset($_POST['action']) && $_POST['action'] == 'delete_prod') {
    $stmt = $pdo->prepare("DELETE FROM products WHERE p_id = ?");
    $stmt->execute([$_POST['p_id']]);
    echo json_encode(['status' => 'success']);
    exit;
}

// 1. ดึงรายการหมวดหมู่สำหรับ Dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY cat_name ASC")->fetchAll();

// 2. สร้าง SQL สำหรับดึงสินค้า พร้อมเงื่อนไขการค้นหาและกรอง
$sql = "SELECT p.*, c.cat_name 
        FROM products p 
        LEFT JOIN categories c ON p.cat_id = c.cat_id 
        WHERE 1=1"; // เทคนิคพื้นฐานเพื่อให้ต่อ query ง่ายขึ้น

$params = [];

if ($search != '') {
    $sql .= " AND (p.p_name LIKE ? OR p.p_sku LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filter_cat != '') {
    $sql .= " AND p.cat_id = ?";
    $params[] = $filter_cat;
}

$sql .= " ORDER BY p.p_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="row align-items-center mb-4">
    <div class="col-md-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-boxes me-2 text-primary"></i>รายการสินค้าทั้งหมด</h4>
    </div>
    <div class="col-md-8">
        <form method="GET" action="index.php" class="row g-2 justify-content-md-end">
            <input type="hidden" name="page" value="products">
            
            <div class="col-auto">
                <select name="cat_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                    <option value="">ทุกหมวดหมู่</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['cat_id'] ?>" <?= $filter_cat == $cat['cat_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['cat_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-auto">
                <div class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control border-0" placeholder="ค้นหาชื่อ/SKU..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-white border-0 bg-white" type="submit">
                        <i class="fas fa-search text-muted"></i>
                    </button>
                </div>
            </div>

            <div class="col-auto">
                <a href="index.php?page=add-product" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-2"></i>เพิ่มสินค้า
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">ภาพสินค้า</th>
                    <th>ข้อมูลสินค้า</th>
                    <th>หมวดหมู่</th>
                    <th>ราคา (รวม VAT)</th>
                    <th class="text-end pe-4">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0): ?>
                    <?php foreach($products as $p): 
                        $vatTotal = $p['p_price_before_vat'] * 1.07;
                    ?>
                    <tr>
                        <td class="ps-4">
                            <img src="../uploads/<?= htmlspecialchars($p['p_image']) ?>" 
                                 onerror="this.src='https://via.placeholder.com/50x50?text=No+Img'" 
                                 width="55" height="55" class="rounded-3 shadow-sm object-fit-cover border">
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($p['p_name']) ?></div>
                            <small class="text-muted">SKU: <?= htmlspecialchars($p['p_sku']) ?> | หน่วย: <?= htmlspecialchars($p['p_unit']) ?></small>
                        </td>
                        <td><span class="badge bg-info bg-opacity-10 text-info px-3"><?= htmlspecialchars($p['cat_name'] ?? 'ไม่มีหมวดหมู่') ?></span></td>
                        <td>
                            <div class="fw-bold text-success"><?= number_format($vatTotal, 2) ?> ฿</div>
                            <small class="text-muted" style="font-size: 0.7rem;">ก่อน VAT: <?= number_format($p['p_price_before_vat'], 2) ?></small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="index.php?page=edit-product&id=<?= $p['p_id'] ?>" class="btn btn-sm btn-outline-warning rounded-start-pill px-3">
                                    <i class="fas fa-edit me-1"></i>แก้ไข
                                </a>
                                <button onclick="deleteProd(<?= $p['p_id'] ?>)" class="btn btn-sm btn-outline-danger rounded-end-pill px-3">
                                    <i class="fas fa-trash me-1"></i>ลบ
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">ไม่พบข้อมูลสินค้าที่ค้นหา</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteProd(id) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "ข้อมูลสินค้าจะถูกลบออกจากระบบ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ลบสินค้า',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData(); 
            fd.append('action', 'delete_prod'); 
            fd.append('p_id', id);
            
            fetch('products.php', { method: 'POST', body: fd })
            .then(res => res.json()).then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1000 })
                    .then(() => location.reload());
                }
            });
        }
    });
}
</script>