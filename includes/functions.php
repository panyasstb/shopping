<?php
// คำนวณราคารวม VAT
function formatVat($price) {
    $vat = $price * 0.07;
    return [
        'before' => number_format($price, 2),
        'vat'    => number_format($vat, 2),
        'total'  => number_format($price + $vat, 2),
        'raw_total' => $price + $vat
    ];
}

// แปลงรูปภาพเป็น WebP และลดขนาด (Optimize)
function uploadWebP($file) {
    $target_dir = "../uploads/";
    $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_name = uniqid() . ".webp";
    $target_file = $target_dir . $new_name;

    if ($extension == "jpg" || $extension == "jpeg") $img = imagecreatefromjpeg($file["tmp_name"]);
    elseif ($extension == "png") $img = imagecreatefrompng($file["tmp_name"]);
    else return false;

    imagewebp($img, $target_file, 80); // คุณภาพ 80%
    imagedestroy($img);
    return $new_name;
}

// ตรวจสอบความปลอดภัย URL (SEO Friendly Slug)
function createSlug($string) {
    return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $string));
}
?>