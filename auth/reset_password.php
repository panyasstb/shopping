<?php 
require_once '../config/db.php';
$token = $_GET['token'] ?? '';

// ตรวจสอบ Token
$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token=? AND token_expire > NOW()");
$stmt->execute([$token]); 
$u = $stmt->fetch();

if(!$u) {
    die("<div style='text-align:center; padding-top:50px; font-family:sans-serif;'>
            <h2 style='color:red;'>ลิงก์หมดอายุ หรือ ไม่ถูกต้อง</h2>
            <a href='forgot.php'>ขอลิงก์ใหม่อีกครั้งที่นี่</a>
         </div>");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $h = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET password=?, reset_token=NULL WHERE id=?")->execute([$h, $u['id']]);
    header("Location: login.php?status=reset_ok");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <title>ตั้งรหัสผ่านใหม่ | TAX-PRO</title>
</head>
<body class="bg-indigo-50 min-h-screen flex items-center justify-center p-6" style="font-family: 'Kanit', sans-serif;">
    <div class="bg-white p-10 rounded-[2rem] shadow-2xl w-full max-w-sm border border-white">
        <div class="text-center mb-8">
            <div class="text-4xl mb-4">🛡️</div>
            <h2 class="text-2xl font-bold text-slate-800">ตั้งรหัสผ่านใหม่</h2>
            <p class="text-slate-500 mt-2">กรุณากำหนดรหัสผ่านใหม่ที่ปลอดภัย</p>
        </div>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-slate-600 mb-1 ml-1">รหัสผ่านใหม่</label>
                <input type="password" name="password" required minlength="6"
                    class="w-full p-4 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all" 
                    placeholder="••••••••">
            </div>
            
            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-bold text-lg shadow-lg shadow-indigo-100 transition-all transform hover:-translate-y-1">
                เปลี่ยนรหัสผ่านทันที
            </button>
        </form>
    </div>
</body>
</html>