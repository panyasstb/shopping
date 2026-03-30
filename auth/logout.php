<?php
session_start();
// ล้างค่า Session ทั้งหมด
session_unset();
session_destroy();

// Redirect กลับไปที่หน้า Login
header("Location: login.php");
exit();
?>