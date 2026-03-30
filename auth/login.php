<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <title>เข้าสู่ระบบ | Shopping APP-Inter Supply</title>
    <style>
        body { font-family: 'Kanit', sans-serif; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="glass-card p-10 rounded-[2rem] shadow-2xl w-full max-w-md border border-white">
        <div class="text-center mb-10">
            <div class="bg-indigo-600 w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg shadow-indigo-200">
                <span class="text-white text-3xl">📦</span>
            </div>
            <h2 class="text-3xl font-bold text-slate-800">ยินดีต้อนรับ</h2>
            <p class="text-slate-500 mt-2">เข้าสู่ระบบ Shopping</p>
        </div>

        <form action="auth_process.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1 ml-1">อีเมลผู้ใช้งาน</label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all"
                    placeholder="name@company.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1 ml-1">รหัสผ่าน</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center gap-4 bg-slate-50 p-2 rounded-xl border border-dashed border-slate-300">
                <img src="../includes/captcha.php" class="rounded-lg">
                <input type="text" name="user_captcha" placeholder="เลขยืนยัน" required 
                    class="flex-1 bg-transparent outline-none text-center font-bold text-lg text-indigo-600">
            </div>

            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1">
                เข้าสู่ระบบ
            </button>
            
            <div class="flex justify-between items-center text-sm pt-4">
                <a href="forgot.php" class="text-indigo-600 hover:underline">ลืมรหัสผ่าน?</a>
                <span class="text-slate-400"></span>
            </div>
        </form>
    </div>
</body>
</html>