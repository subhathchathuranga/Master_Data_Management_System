<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die('Category ID is required.');
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM master_category WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    die('Category not found or access denied.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9fafb;
            padding: 2rem;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        input[type="text"] {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            align-self: center;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        a {
            display: block;
            margin-top: 1rem;
            text-align: center;
            color: #6b7280;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Category</h2>
    <form action="../actions/category_crud.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">
        <input type="text" name="code" value="<?= htmlspecialchars($category['code']) ?>" required>
        <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        <button type="submit" name="update_category">Update Category</button>
    </form>
    <a href="category_management.php">← Back to Category List</a>
</div>
</body>
</html>
