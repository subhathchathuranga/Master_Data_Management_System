<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$brands = $conn->query("SELECT id, name FROM master_brand WHERE user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query("SELECT id, name FROM master_category WHERE user_id = $user_id")->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT * FROM master_item WHERE user_id = $user_id ORDER BY id DESC";
$items = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Item Management</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9fafb;
            padding: 2rem;
        }

        .container {
            max-width: 1000px;
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

        .top-buttons {
            text-align: center;
            margin-bottom: 2rem;
        }

        .top-buttons a {
            display: inline-block;
            margin: 10px 10px;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-blue {
            background: #2563eb;
            color: white;
        }

        .btn-blue:hover {
            background: #1d4ed8;
        }

        .btn-gray {
            background: #6b7280;
            color: white;
        }

        .btn-gray:hover {
            background: #4b5563;
        }

        .btn-red {
            background: #dc2626;
            color: white;
        }

        .btn-red:hover {
            background: #b91c1c;
        }

        form {
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        form button {
            align-self: center;
        }

        input,
        select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        input[type="file"] {
            padding: 0.2rem;
        }

        button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            grid-column: span 3;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
        }

        .actions a {
            margin-right: 10px;
            color: #2563eb;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Item Management</h2>

        <form method="POST" action="../actions/item_crud.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="">
            <input type="text" name="code" placeholder="Item Code" required>
            <input type="text" name="name" placeholder="Item Name" required>

            <select name="brand_id" required>
                <option value="">Select Brand</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <input type="file" name="attachment" accept=".jpg,.png,.pdf,.docx">
            <button type="submit" name="create_item">Save Item</button>
        </form>

        <h3>Item List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Brand ID</th>
                    <th>Category ID</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= htmlspecialchars($item['code']) ?></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['brand_id'] ?></td>
                        <td><?= $item['category_id'] ?></td>
                        <td><?= $item['attachment'] ? '<a href="../uploads/' . $item['attachment'] . '" target="_blank">View</a>' : '-' ?>
                        </td>
                        <td><?= $item['status'] ?></td>
                        <td class="actions">
                            <a href="item_form_update.php?id=<?= $item['id'] ?>">Edit</a>
                            <a href="../actions/item_crud.php?delete=<?= $item['id'] ?>"
                                onclick="return confirm('Delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="top-buttons">
            <a href="dashboard.php" class="btn-gray">Back to Dashboard</a>
            <a href="../actions/export_item_csv.php" class="btn-blue">Export to CSV</a>
            <a href="../actions/logout.php" class="btn-red">Logout</a>
        </div>
    </div>
</body>

</html>