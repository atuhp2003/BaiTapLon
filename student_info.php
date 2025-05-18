<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT s.*, c.class_name 
                      FROM students s 
                      JOIN classes c ON s.class_id = c.id 
                      WHERE s.id = ?");
$stmt->execute([$_GET['id']]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Học Sinh - Quản Lý Học Sinh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-top: 2rem;
            max-width: 800px;
        }
        h2 {
            color: #1e88e5;
            margin-bottom: 1.5rem;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead {
            background: #1e88e5;
            color: white;
        }
        .navbar {
            background: #1e88e5;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .btn-primary {
            background: #1e88e5;
            border: none;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #1565c0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill"></i> Quản Lý Học Sinh</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Đăng Xuất</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Thông Tin Học Sinh</h2>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Mã</th>
                    <td><?php echo $student['id']; ?></td>
                </tr>
                <tr>
                    <th>Họ Tên</th>
                    <td><?php echo $student['name']; ?></td>
                </tr>
                <tr>
                    <th>Ngày Sinh</th>
                    <td><?php echo $student['date_of_birth']; ?></td>
                </tr>
                <tr>
                    <th>Giới Tính</th>
                    <td><?php echo $student['gender'] == 'Male' ? 'Nam' : 'Nữ'; ?></td>
                </tr>
                <tr>
                    <th>Lớp</th>
                    <td><?php echo $student['class_name']; ?></td>
                </tr>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Quay Lại Danh Sách</a>
        <a href="student_scores.php?id=<?php echo $student['id']; ?>" class="btn btn-info">Xem Điểm</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>