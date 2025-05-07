<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (isset($_POST['create_item'])) {
        $id = $_POST['id'] ?? '';
        $code = trim($_POST['code']);
        $name = trim($_POST['name']);
        $brand_id = (int)$_POST['brand_id'];
        $category_id = (int)$_POST['category_id'];
        $status = 'Active';
        $attachment = null;

        if (!empty($_FILES['attachment']['name'])) {
            $filename = basename($_FILES['attachment']['name']);
            $target_dir = "../uploads/";
            $target_file = $target_dir . time() . "_" . $filename;

            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
                $attachment = basename($target_file);
            }
        }

        if ($id) {
            if ($attachment) {
                $stmt = $conn->prepare("UPDATE master_item SET code=?, name=?, brand_id=?, category_id=?, attachment=?, updated_at=NOW() WHERE id=? AND user_id=?");
                $stmt->bind_param("ssissii", $code, $name, $brand_id, $category_id, $attachment, $id, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE master_item SET code=?, name=?, brand_id=?, category_id=?, updated_at=NOW() WHERE id=? AND user_id=?");
                $stmt->bind_param("ssiiii", $code, $name, $brand_id, $category_id, $id, $user_id);
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO master_item (brand_id, category_id, code, name, attachment, status, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("iissssi", $brand_id, $category_id, $code, $name, $attachment, $status, $user_id);
        }

        $stmt->execute();
        $stmt->close();
        header("Location: ../views/item_form.php");
        exit();
    }

    if (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM master_item WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: ../views/item_form.php");
        exit();
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
