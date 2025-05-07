<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['create_category'])) {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $status = 'Active';

    $stmt = $conn->prepare("INSERT INTO master_category (code, name, status, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssi", $code, $name, $status, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../views/category_form.php");
    exit();
}

if (isset($_POST['update_category'])) {
    $id = intval($_POST['id']);
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);

    $stmt = $conn->prepare("UPDATE master_category SET code=?, name=?, updated_at=NOW() WHERE id=? AND user_id=?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssii", $code, $name, $id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../views/category_form.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM master_category WHERE id=? AND user_id=?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../views/category_form.php");
    exit();
}
?>
