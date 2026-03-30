<?php
session_start();
require_once '../config/db.php';

// ตั้งค่าให้แสดง Error เพื่อการตรวจสอบ (Debug Mode)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ตรวจสอบ CAPTCHA
    if (!isset($_POST['user_captcha']) || $_POST['user_captcha'] != $_SESSION['captcha']) {
        header("Location: register.php?error=captcha");
        exit();
    }

    // 2. รับค่าจากฟอร์ม
    $company_name    = trim($_POST['company_name']);
    $company_tax_id  = trim($_POST['company_tax_id']);
    $full_name       = trim($_POST['full_name']);
    $email           = trim($_POST['email']);
    $password        = $_POST['password'];

    try {
        // เริ่มต้น Transaction (ป้องกันข้อมูลค้างถ้าบันทึกไม่ครบทุกตาราง)
        $pdo->beginTransaction();

        // 3. บันทึกข้อมูลลงตาราง companies
        $stmt1 = $pdo->prepare("INSERT INTO companies (company_name, tax_id) VALUES (?, ?)");
        $stmt1->execute([$company_name, $company_tax_id]);
        
        // ดึง ID ของบริษัทที่เพิ่งสร้าง (ระบบสร้าง Company ID อัตโนมัติ)
        $company_id = $pdo->lastInsertId();

        // 4. เข้ารหัสรหัสผ่าน (Hashing)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 5. บันทึกข้อมูลลงตาราง users พร้อมผูกกับ company_id
        $stmt2 = $pdo->prepare("INSERT INTO users (company_id, email, password, full_name) VALUES (?, ?, ?, ?)");
        $stmt2->execute([$company_id, $email, $hashed_password, $full_name]);

        // 6. ยืนยันการบันทึกข้อมูลทั้งหมด (Commit)
        if ($pdo->commit()) {
            // ล้างค่า Captcha หลังจากใช้งานสำเร็จ
            unset($_SESSION['captcha']);
            
            // ส่งค่ากลับไปยังหน้า Login พร้อมแจ้งเตือนสำเร็จ
            header("Location: login.php?status=reg_ok");
            exit();
        }

    // ในไฟล์ register_process.php ส่วน catch
} catch (PDOException $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    
    $error_text = ($e->getCode() == 23000) 
        ? "อีเมล หรือ เลขผู้เสียภาษีนี้ถูกใช้งานไปแล้วในระบบ" 
        : "เกิดข้อผิดพลาด: " . $e->getMessage();
    
    // ส่งกลับไปที่ register.php พร้อม error_msg
    header("Location: register.php?error_msg=" . urlencode($error_text));
    exit();
}


} else {
    // หากไม่ได้มาจากการ Post ให้เด้งกลับหน้า Register
    header("Location: register.php");
    exit();
}
?>
