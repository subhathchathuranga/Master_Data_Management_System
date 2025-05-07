<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
if (!isset($_GET['edit'])) {
    header("Location: brand_form.php");
    exit();
}

$brand_id = (int) $_GET['edit'];
$stmt = $conn->prepare("SELECT * FROM master_brand WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $brand_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$brand = $result->fetch_assoc();
$stmt->close();

if (!$brand) {
    header("Location: brand_form.php?error=Brand+not+found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE master_brand SET code=?, name=?, status=?, updated_at=NOW() WHERE id=? AND user_id=?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssii", $code, $name, $status, $brand_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: brand_form.php?success=Brand+updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Brand</title>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem;
        }

        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #111827;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin: 1rem 0 0.5rem;
            color: #374151;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            margin-top: 1rem;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #6b7280;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Brand</h2>
    <form method="POST">
        <label for="code">Brand Code</label>
        <input type="text" name="code" id="code" value="<?= htmlspecialchars($brand['code']) ?>" required>

        <label for="name">Brand Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($brand['name']) ?>" required>

        <label for="status">Status</label>
        <select name="status" id="status" required>
            <option value="Active" <?= $brand['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
            <option value="Inactive" <?= $brand['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>

        <div class="actions">
            <button type="submit" class="btn">Apply Changes</button>
            <a href="brand_form.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
