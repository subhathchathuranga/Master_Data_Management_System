<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "<div style='text-align:center;margin-top:50px;color:red;'>No item ID specified.</div>";
    exit();
}

$item_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM master_item WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div style='text-align:center;margin-top:50px;color:red;'>Item not found.</div>";
    exit();
}

$item = $result->fetch_assoc();
$stmt->close();

$brands = $conn->query("SELECT id, name FROM master_brand WHERE user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query("SELECT id, name FROM master_category WHERE user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Item</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9fafb;
            padding: 2rem;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }

        label {
            margin-top: 1rem;
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        input[type="text"], select, input[type="file"] {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #f9fafb;
        }

        button {
            margin-top: 1.5rem;
            width: 100%;
            background-color: #2563eb;
            color: white;
            padding: 0.7rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        .file-info {
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        .back-link {
            display: block;
            margin-top: 1.5rem;
            text-align: center;
            color: #2563eb;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Item</h2>

    <form method="POST" action="../actions/item_crud.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">

        <label for="code">Item Code</label>
        <input type="text" name="code" id="code" value="<?= htmlspecialchars($item['code']) ?>" required>

        <label for="name">Item Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($item['name']) ?>" required>

        <label for="brand">Brand</label>
        <select name="brand_id" id="brand" required>
            <option value="">Select Brand</option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?= $brand['id'] ?>" <?= $brand['id'] == $item['brand_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($brand['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="category">Category</label>
        <select name="category_id" id="category" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= $category['id'] == $item['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="attachment">Attachment (Optional)</label>
        <input type="file" name="attachment" id="attachment" accept=".jpg,.png,.pdf,.docx">
        <?php if (!empty($item['attachment'])): ?>
            <div class="file-info">
                Current File: <a href="../uploads/<?= htmlspecialchars($item['attachment']) ?>" target="_blank">View Attachment</a>
            </div>
        <?php endif; ?>

        <button type="submit" name="create_item">Update Item</button>
    </form>

    <a href="item_form.php" class="back-link">← Back to Item List</a>
</div>

</body>
</html>
