<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$grade = isset($_GET['grade']) ? (int)$_GET['grade'] : 0;
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;

$where = '';
$params = [];
if ($grade > 0) {
    $where .= "WHERE c.grade = ?";
    $params[] = $grade;
}
if ($class_id > 0) {
    $where .= ($where ? ' AND' : 'WHERE') . " s.class_id = ?";
    $params[] = $class_id;
}

$stmt = $pdo->prepare("SELECT s.*, c.class_name, (s.math_score + s.physics_score + s.chemistry_score) / 3 AS avg_score 
                      FROM students s 
                      JOIN classes c ON s.class_id = c.id $where ORDER BY s.name");
$stmt->execute($params);
$students = $stmt->fetchAll();

$classes = $pdo->query("SELECT * FROM classes ORDER BY class_name")->fetchAll();
$grades = [10, 11, 12];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Học Sinh</title>
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
            margin-bottom: 2rem;
        }
        h2 {
            color: #1e88e5;
            margin-bottom: 1.5rem;
        }
        .form-control, .btn {
            border-radius: 8px;
        }
        .btn-primary {
            background: #1e88e5;
            border: none;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #1565c0;
        }
        .btn-info {
            background: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background: #138496;
        }
        .btn-warning {
            background: #ffc107;
            border: none;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-danger {
            background: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead {
            background: #1e88e5;
            color: white;
        }
        .table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .table tbody tr:hover {
            background: #e3f2fd;
        }
        .navbar {
            background: #1e88e5;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-mortarboard-fill"></i> Quản Lý Học Sinh</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Đăng Xuất</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Lọc Học Sinh</h2>
        <form method="GET" class="row mb-4">
            <div class="col-md-4 mb-3">
                <label class="form-label">Khối</label>
                <select name="grade" class="form-control" onchange="this.form.submit()">
                    <option value="0">Tất Cả Khối</option>
                    <?php foreach ($grades as $g): ?>
                        <option value="<?php echo $g; ?>" <?php echo $grade == $g ? 'selected' : ''; ?>>Khối <?php echo $g; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Lớp</label>
                <select name="class_id" class="form-control" onchange="this.form.submit()">
                    <option value="0">Tất Cả Lớp</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" <?php echo $class_id == $class['id'] ? 'selected' : ''; ?>><?php echo $class['class_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <h2>Thêm Học Sinh Mới</h2>
        <?php if (isset($_SESSION['message'])) {
            echo showAlert($_SESSION['message'], $_SESSION['message_type']);
            unset($_SESSION['message'], $_SESSION['message_type']);
        } ?>
        <form action="add.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Họ Tên</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày Sinh</label>
                    <input type="date" name="date_of_birth" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Giới Tính</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male">Nam</option>
                        <option value="Female">Nữ</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Lớp</label>
                    <select name="class_id" class="form-control" required>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Điểm Toán</label>
                    <input type="number" step="0.1" name="math_score" class="form-control" value="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Điểm Lý</label>
                    <input type="number" step="0.1" name="physics_score" class="form-control" value="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Điểm Hóa</label>
                    <input type="number" step="0.1" name="chemistry_score" class="form-control" value="0">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Học Sinh</button>
        </form>

        <h2 class="mt-5">Danh Sách Học Sinh</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Họ Tên</th>
                    <th>Lớp</th>
                    <th>Điểm Trung Bình</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['class_name']; ?></td>
                    <td><?php echo number_format($student['avg_score'], 2); ?></td>
                    <td>
                        <a href="student_info.php?id=<?php echo $student['id']; ?>" class="btn btn-info btn-sm">Xem Thông Tin</a>
                        <a href="student_scores.php?id=<?php echo $student['id']; ?>" class="btn btn-info btn-sm">Xem Điểm</a>
                        <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="delete.php?id=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>