<?php
require_once '../config/db.php';

// --- Logic ส่วนการประมวลผล (API) ---
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $stmt = $pdo->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->execute([$_POST['cat_name']]);
        echo json_encode(['status' => 'success', 'message' => 'เพิ่มหมวดหมู่สำเร็จ']);
    } elseif ($_POST['action'] == 'update') {
        $stmt = $pdo->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->execute([$_POST['cat_name'], $_POST['cat_id']]);
        echo json_encode(['status' => 'success', 'message' => 'อัปเดตหมวดหมู่สำเร็จ']);
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['cat_id'];
        $check = $pdo->prepare("SELECT COUNT(*) FROM products WHERE cat_id = ?");
        $check->execute([$id]);
        if ($check->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'หมวดหมู่นี้มีสินค้าอยู่ ไม่สามารถลบได้']);
        } else {
            $stmt = $pdo->prepare("DELETE FROM categories WHERE cat_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'ลบหมวดหมู่สำเร็จ']);
        }
    }
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY cat_id DESC")->fetchAll();
?>

<div class="main-header mb-4">
    <h4 class="fw-bold text-dark"><i class="fas fa-tags me-2 text-primary"></i>จัดการหมวดหมู่สินค้า</h4>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-3" id="formTitle">เพิ่มหมวดหมู่ใหม่</h6>
            <form id="catForm">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="cat_id" id="formCatId">
                <div class="mb-3">
                    <input type="text" name="cat_name" id="formCatName" class="form-control" placeholder="ชื่อหมวดหมู่..." required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2" id="submitBtn">บันทึกข้อมูล</button>
                <button type="button" class="btn btn-light w-100 mt-2 d-none" id="cancelBtn" onclick="resetCatForm()">ยกเลิก</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ชื่อหมวดหมู่</th>
                        <th class="text-end pe-4">เครื่องมือ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $c): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= htmlspecialchars($c['cat_name']) ?></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="editCat(<?= $c['cat_id'] ?>, '<?= htmlspecialchars($c['cat_name']) ?>')">แก้ไข</button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="deleteCat(<?= $c['cat_id'] ?>)">ลบ</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('catForm').onsubmit = function(e) {
    e.preventDefault();
    fetch('categories.php', { method: 'POST', body: new FormData(this) })
    .then(res => res.json()).then(data => {
        if(data.status === 'success') Swal.fire('สำเร็จ', data.message, 'success').then(() => location.reload());
        else Swal.fire('ผิดพลาด', data.message, 'error');
    });
};

function editCat(id, name) {
    document.getElementById('formCatId').value = id;
    document.getElementById('formCatName').value = name;
    document.getElementById('formAction').value = 'update';
    document.getElementById('formTitle').innerText = 'แก้ไขหมวดหมู่';
    document.getElementById('submitBtn').className = 'btn btn-warning w-100 py-2';
    document.getElementById('cancelBtn').classList.remove('d-none');
}

function resetCatForm() {
    document.getElementById('catForm').reset();
    document.getElementById('formAction').value = 'add';
    document.getElementById('formTitle').innerText = 'เพิ่มหมวดหมู่ใหม่';
    document.getElementById('submitBtn').className = 'btn btn-primary w-100 py-2';
    document.getElementById('cancelBtn').classList.add('d-none');
}

function deleteCat(id) {
    Swal.fire({ title: 'ยืนยันการลบ?', text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!", icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก' })
    .then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData(); fd.append('action', 'delete'); fd.append('cat_id', id);
            fetch('categories.php', { method: 'POST', body: fd }).then(res => res.json()).then(data => {
                if(data.status === 'success') Swal.fire('ลบแล้ว!', data.message, 'success').then(() => location.reload());
                else Swal.fire('ผิดพลาด', data.message, 'error');
            });
        }
    });
}
</script>