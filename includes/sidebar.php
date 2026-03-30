<?php
// ตรวจสอบว่าไฟล์ที่เรียกใช้อยู่ลึกแค่ไหน (เพื่อปรับ path กลับไปที่ root)
$current_dir = dirname($_SERVER['PHP_SELF']);
$base_path = (strpos($current_dir, 'modules') !== false || strpos($current_dir, 'auth') !== false) ? '../' : '';
?>
<div id="sidebar" class="bg-white h-screen w-64 fixed md:relative p-6 flex flex-col border-r border-slate-100 z-50
                      transition-transform duration-300 ease-in-out
                      -translate-x-full md:translate-x-0 shadow-xl md:shadow-none">

    <div class="flex justify-between items-center mb-10 shrink-0">
        <h1 class="text-2xl font-bold text-indigo-600">SHOPPING</h1>
        
        <button id="close-sidebar-btn" class="md:hidden text-slate-400 hover:text-rose-500 text-xl">
            ✕
        </button>
    </div>

    <nav class="flex-1 space-y-2 overflow-y-auto">
		<p class="text-slate-500 text-[15px] font-bold uppercase">📁 ประวัติการสั่งซื้อ</p>
        <a href="<?= $base_path ?>index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
            📊 ประวัติการสั่งซื้อ		</a>
		<a href="<?= $base_path ?>index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
            📊 สรุปรายการสั่งซื้อ
		</a>
	  <p class="text-slate-500 text-[15px] font-bold uppercase">📁 สั่งซื้อสินค้า </p>
        <a href="<?= $base_path ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
            📝 สินค้าทั้งหมด
        </a>
        <a href="<?= $base_path ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
            📝 ตระกร้าสินค้า
        </a>
      <p class="text-slate-500 text-[15px] font-bold uppercase mt-4">📝 การชำระสินค้า</p>
        <a href="<?= $base_path ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
            📥 ขั้นตอนการชำระสินค้า
        </a>

     <p class="text-slate-500 text-[15px] font-bold uppercase mt-4">📊 การจัดส่ง</p>
        <a href="<?= $base_path ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
                       ติดตามสินค้า
        </a>
	<p class="text-slate-500 text-[15px] font-bold uppercase mt-4">📄 ข้อมูลบริษัท</p>
        <a href="<?= $base_path ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
            ✍ ติดต่อบริษัท
         </a>
		

        </nav>

    <div class="mt-auto shrink-0 border-t pt-4">
        <a href="<?= $base_path ?>auth/logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-50">
            🚪 ออกจากระบบ
        </a>


	<p class="text-slate-500 text-[15px] font-bold uppercase mt-4">&copy; Ver.1</p>
	</div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const sidebar = document.getElementById('sidebar');
        
        if (closeSidebarBtn && sidebar) {
            closeSidebarBtn.addEventListener('click', function() {
                // ซ่อน Sidebar กลับไปทางซ้าย
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
            });
        }
    });
</script>