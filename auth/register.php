<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <title>สมัครสมาชิก |Shopping-APP Inter Supply</title>
    <style>
        body { font-family: 'Kanit', sans-serif; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="glass-card p-8 md:p-12 rounded-[2.5rem] shadow-2xl w-full max-w-2xl border border-white">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">ลงทะเบียนธุรกิจใหม่</h2>
            <p class="text-slate-500 mt-2">เริ่มต้นจัดการภาษีอย่างเป็นระบบกับเรา</p>
        </div>

        <form action="register_process.php" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="flex items-center gap-2 font-bold text-indigo-600 border-b pb-2">
                        <span>🏢 ข้อมูลบริษัท</span>
                    </h3>
                    <input type="text" name="company_name" placeholder="ชื่อบริษัท/ร้านค้า" required 
                        class="w-full p-4 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-50 outline-none transition-all">
                    <input type="text" name="company_tax_id" placeholder="เลขผู้เสียภาษี 13 หลัก" required maxlength="13" 
                        class="w-full p-4 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-50 outline-none transition-all">
                </div>

                <div class="space-y-4">
                    <h3 class="flex items-center gap-2 font-bold text-emerald-600 border-b pb-2">
                        <span>👤 ข้อมูลผู้ดูแล</span>
                    </h3>
                    <input type="text" name="full_name" placeholder="ชื่อ-นามสกุล" required 
                        class="w-full p-4 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-50 outline-none transition-all">
                    <input type="email" name="email" placeholder="อีเมล (ใช้เข้าระบบ)" required 
                        class="w-full p-4 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-50 outline-none transition-all">
                    <input type="password" name="password" placeholder="ตั้งรหัสผ่าน" required 
                        class="w-full p-4 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-50 outline-none transition-all">
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-4 bg-slate-50 p-4 rounded-3xl border border-dashed border-slate-300 mt-4">
                <img src="../includes/captcha.php" class="rounded-xl shadow-sm">
                <input type="text" name="user_captcha" placeholder="กรอกเลขยืนยัน" required 
                    class="w-full md:w-40 p-3 bg-white border border-slate-200 rounded-xl text-center font-bold text-indigo-600 text-xl">
                <button class="flex-1 w-full bg-slate-900 hover:bg-black text-white py-4 rounded-2xl font-bold text-lg transition-all shadow-lg hover:-translate-y-1">
                    ยืนยันการสมัครสมาชิก
                </button>
            </div>

            <div class="text-center pt-2">
                <p class="text-slate-500">เป็นสมาชิกอยู่แล้ว? <a href="login.php" class="text-indigo-600 font-bold hover:underline">เข้าสู่ระบบ</a></p>
            </div>
        </form>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'captcha') {
            Swal.fire({ icon: 'warning', title: 'รหัสยืนยันไม่ถูกต้อง', text: 'กรุณากรอกเลขยืนยันใหม่อีกครั้ง', confirmButtonColor: '#4f46e5' });
        }
        if (urlParams.get('error_msg')) {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: decodeURIComponent(urlParams.get('error_msg')) });
        }
    </script>
</body>
</html>