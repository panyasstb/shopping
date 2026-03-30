<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$cid = $_SESSION['company_id'];
$year = isset($_GET['y']) ? intval($_GET['y']) : date('Y');


?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Shopping-APP Inter Supply</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #f1f5f9; }
        /* ซ่อน Scrollbar ของหน้าต่างหลัก */
        body::-webkit-scrollbar { display: none; }
        body { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-screen flex overflow-hidden relative"> <?php include 'includes/sidebar.php'; ?>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar'); // ต้องแน่ใจว่าใน sidebar.php มี id="sidebar"
            
            if (mobileMenuBtn && sidebar) {
                mobileMenuBtn.addEventListener('click', function() {
                    // เปลี่ยนสถานะการซ่อน/แสดง Sidebar บนมือถือ
                    sidebar.classList.toggle('-translate-x-full');
                    sidebar.classList.toggle('translate-x-0');
                });
            }
        });
    </script>

    <script>
        // ... (โค้ด Chart.js เดิม) ...
        const ctx = document.getElementById('taxChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                datasets: [
                    {
                        label: 'รายได้',
                        data: <?= json_encode(array_values($sell_monthly)) ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 6, // ลดรัศมีลงหน่อยให้เหมาะกับมือถือ
                        barThickness: 'flex',
                        maxBarThickness: 15 // ลดขนาดแท่งลงหน่อย
                    },
                    {
                        label: 'รายจ่าย',
                        data: <?= json_encode(array_values($buy_monthly)) ?>,
                        backgroundColor: '#fb7185',
                        borderRadius: 6,
                        barThickness: 'flex',
                        maxBarThickness: 15
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9' },
                        ticks: { 
                            font: { family: 'Kanit', size: 10 }, // ลดขนาดตัวอักษรแกน Y
                            callback: function(value) {
                                // แสดงผลย่อบนมือถือ เช่น 10k แทน 10,000
                                if (value >= 1000) return '฿' + (value/1000).toFixed(1) + 'k';
                                return '฿' + value;
                            }
                        }
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { family: 'Kanit', size: 10 } } // ลดขนาดตัวอักษรแกน X
                    }
                }
            }
        });
    </script>
</body>
</html>