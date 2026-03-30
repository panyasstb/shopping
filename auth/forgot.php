<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <title>กู้คืนรหัสผ่าน | TAX-PRO</title>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6" style="font-family: 'Kanit', sans-serif;">
    <div class="bg-white p-10 rounded-[2rem] shadow-xl w-full max-w-sm text-center border border-slate-100">
        <div class="bg-amber-100 w-20 h-20 rounded-full mx-auto flex items-center justify-center mb-6">
            <span class="text-4xl">🔑</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">ลืมรหัสผ่าน?</h2>
        <p class="text-slate-500 mb-8">ระบุอีเมลของคุณ เพื่อรับลิงก์ตั้งรหัสผ่านใหม่</p>

        <form action="forgot_process.php" method="POST" class="space-y-4">
            <input type="email" name="email" required 
                class="w-full p-4 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-50 focus:border-amber-400 outline-none transition-all" 
                placeholder="ระบุอีเมลของคุณ">
            
            <button class="w-full bg-slate-900 hover:bg-black text-white py-4 rounded-2xl font-bold text-lg shadow-lg transition-all active:scale-95">
                ส่งลิงก์ตั้งรหัสใหม่
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <a href="login.php" class="text-slate-400 hover:text-indigo-600 transition-colors flex items-center justify-center gap-2">
                <span>←</span> กลับหน้าเข้าสู่ระบบ
            </a>
        </div>
    </div>
</body>
</html>