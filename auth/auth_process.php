<?php session_start(); require_once '../config/db.php';
if($_POST['user_captcha'] != $_SESSION['captcha']){ header("Location: login.php?error=captcha"); exit(); }
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?"); $stmt->execute([$_POST['email']]); $u = $stmt->fetch();
if($u && password_verify($_POST['password'], $u['password'])){
    $_SESSION['user_id']=$u['id']; $_SESSION['company_id']=$u['company_id']; $_SESSION['user_name']=$u['full_name'];
    header("Location: ../index.php");
} else { header("Location: login.php?error=invalid"); }
