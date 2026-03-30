<?php require_once '../config/db.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $token = bin2hex(random_bytes(32)); $expire = date("Y-m-d H:i:s", strtotime('+1 hour'));
    $stmt = $pdo->prepare("UPDATE users SET reset_token=?, token_expire=? WHERE email=?");
    $stmt->execute([$token, $expire, $_POST['email']]);
    echo "คุณแน่ใจที่จะตั้งรหัสผ่านใหม่หรือไม่ กรุณาตรวจสอบภาษาที่ใช้ และ ปุ่ม NumLock: <a href='reset_password.php?token=$token'>คลิกที่นี่เพื่อตั้งรหัสใหม่</a>";
}
