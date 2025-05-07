<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['create_brand'])) {
    $id = $_POST['id'] ?? '';
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);

    if ($id) {
        $stmt = $conn->prepare("UPDATE master_brand SET code=?, name=?, updated_at=NOW() WHERE id=? AND user_id=?");
        $stmt->bind_param("ssii", $code, $name, $id, $user_id);
    } else {
        $status = 'Active';
        $stmt = $conn->prepare("INSERT INTO master_brand (code, name, status, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("sssi", $code, $name, $status, $user_id);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: ../views/brand_form.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM master_brand WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../views/brand_form.php");
    exit();
}
?>
