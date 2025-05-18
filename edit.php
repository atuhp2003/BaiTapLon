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

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_GET['id']]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: index.php");
    exit;
}

$classes = $pdo->query("SELECT * FROM classes ORDER BY class_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $class_id = $_POST['class_id'];
    $math_score = $_POST['math_score'];
    $physics_score = $_POST['physics_score'];
    $chemistry_score = $_POST['chemistry_score'];

    $stmt = $pdo->prepare("UPDATE students SET name = ?, date_of_birth = ?, gender = ?, class_id = ?, 
                          math_score = ?, physics_score = ?, chemistry_score = ? WHERE id = ?");
    $stmt->execute([$name, $date_of_birth, $gender, $class_id, $math_score, $physics_score, $chemistry_score, $_GET['id']]);

    $_SESSION['message'] = "Cập nhật học sinh thành công!";
    $_SESSION['message_type'] = 'success';
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Học Sinh - Quản Lý Học Sinh</title>
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
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
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
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill"></i> Quản Lý Học Sinh</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Đăng Xuất</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Sửa Thông Tin Học Sinh</h2>
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Họ Tên</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $student['name']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày Sinh</label>
                    <input type="date" name="date_of_birth" class="form-control" value="<?php echo $student['date_of_birth']; ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Giới Tính</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male" <?php echo $student['gender'] == 'Male' ? 'selected' : ''; ?>>Nam</option>
                        <option value="Female" <?php echo $student['gender'] == 'Female' ? 'selected' : ''; ?>>Nữ</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Lớp</label>
                    <select name="class_id" class="form-control" required>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>" <?php echo $student['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                                <?php echo $class['class_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Điểm Toán</label>
                    <input type="number" step="0.1" name="math_score" class="form-control" value="<?php echo $student['math_score']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Điểm Lý</label>
                    <input type="number" step="0.1" name="physics_score" class="form-control" value="<?php echo $student['physics_score']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Điểm Hóa</label>
                    <input type="number" step="0.1" name="chemistry_score" class="form-control" value="<?php echo $student['chemistry_score']; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>