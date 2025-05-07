<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT code, name, status, created_at FROM master_brand WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="brand_list.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Code', 'Name', 'Status', 'Created At']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['code'], $row['name'], $row['status'], $row['created_at']]);
}

fclose($output);
exit;
