<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=items.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Code', 'Name', 'Brand ID', 'Category ID', 'Attachment', 'Status', 'Created At', 'Updated At']);

$sql = "SELECT id, code, name, brand_id, category_id, attachment, status, created_at, updated_at FROM master_item WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
