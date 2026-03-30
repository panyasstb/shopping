<?php
$current_page = $_GET['page'] ?? 'dashboard';
?>
<style>
    .sidebar-admin { min-width: 280px; max-width: 280px; background: #1e293b; color: #fff; min-height: 100vh; }
    .nav-admin-link { color: #94a3b8; padding: 12px 20px; display: block; text-decoration: none; border-radius: 8px; margin: 4px 15px; transition: 0.3s; }
    .nav-admin-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
    .nav-admin-link.active { background: #3b82f6; color: #fff; }
</style>

<div class="sidebar-admin">
    <div class="p-4 mb-4 text-center">
        <h4 class="fw-bold text-white mb-0">GEMINI SHOP</h4>
        <small class="text-primary font-bold uppercase">Administrator Panel</small>
    </div>
    
    <div class="mt-2">
        <a href="index.php?page=dashboard" class="nav-admin-link <?= $current_page == 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-chart-line me-2"></i> แดชบอร์ด
        </a>
        <a href="index.php?page=products" class="nav-admin-link <?= $current_page == 'products' ? 'active' : '' ?>">
            <i class="fas fa-box me-2"></i> จัดการสินค้า
        </a>
        <a href="index.php?page=categories" class="nav-admin-link <?= $current_page == 'categories' ? 'active' : '' ?>">
            <i class="fas fa-tags me-2"></i> จัดการหมวดหมู่
        </a>
        
        <hr class="mx-4 my-4 border-secondary opacity-25">
        
        <a href="../auth/logout.php" class="nav-admin-link text-danger">
            <i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ
        </a>
    </div>
</div>