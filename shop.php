<?php 
require_once 'config/db.php';
require_once 'includes/functions.php';
if(!isset($_SESSION['user_id'])) header("Location: index.php");

$q = $_GET['q'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM products WHERE p_name LIKE ? OR p_sku LIKE ?");
$stmt->execute(["%$q%", "%$q%"]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta name="robots" content="noindex, nofollow"> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <form action="" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="ค้นหาชื่อหรือรหัสสินค้า..." value="<?= htmlspecialchars($q) ?>">
                <button class="btn btn-dark">ค้นหา</button>
            </div>
        </form>

        <div class="row">
            <?php foreach($products as $p): 
                $vatInfo = formatVat($p['p_price_before_vat']);
            ?>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="uploads/<?= $p['p_image'] ?>" class="card-img-top" alt="product">
                    <div class="card-body">
                        <small class="text-muted"><?= $p['p_sku'] ?></small>
                        <h5 class="card-title"><?= $p['p_name'] ?></h5>
                        <p class="mb-0 text-muted small">ก่อน VAT: <?= $vatInfo['before'] ?> ฿</p>
                        <p class="text-danger fw-bold">รวม VAT: <?= $vatInfo['total'] ?> ฿</p>
                        <a href="product/<?= $p['p_slug'] ?>" class="btn btn-outline-primary btn-sm w-100">ดูรายละเอียด</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>