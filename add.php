<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $class_id = $_POST['class_id'];
    $math_score = $_POST['math_score'];
    $physics_score = $_POST['physics_score'];
    $chemistry_score = $_POST['chemistry_score'];

    $stmt = $pdo->prepare("INSERT INTO students (name, date_of_birth, gender, class_id, math_score, physics_score, chemistry_score) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $date_of_birth, $gender, $class_id, $math_score, $physics_score, $chemistry_score]);

    $_SESSION['message'] = "Thêm học sinh thành công!";
    $_SESSION['message_type'] = 'success';
    header("Location: index.php");
    exit;
}
?>