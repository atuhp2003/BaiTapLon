<?php
// Cấu hình kết nối cơ sở dữ liệu
$host = 'dbbaitaplonx.mysql.database.azure.com';
$dbname = 'baitaplonx';
echo $username = 'ngan90856';
$password = 'Tu@01215255404';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Hàm hiển thị thông báo
function showAlert($message, $type = 'success') {
    return "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
}
?>
