<?php session_start(); $code = rand(1000, 9999); $_SESSION['captcha'] = $code;
header('Content-type: image/png'); $img = imagecreatetruecolor(70, 30);
$bg = imagecolorallocate($img, 255, 255, 255); $txt = imagecolorallocate($img, 0, 0, 0);
imagefill($img, 0, 0, $bg); imagestring($img, 5, 15, 7, $code, $txt); imagepng($img); imagedestroy($img); ?>
