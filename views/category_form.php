<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM master_category WHERE user_id=$user_id ORDER BY id DESC");
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Category Management</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9fafb;
            padding: 2rem;
        }

        .container {
            max-width: 900px;
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
            margin: 10px 10px 10px;
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
        <h2>Category Management</h2>

        <form method="POST" action="../actions/category_crud.php">
            <input type="hidden" name="id">
            <input type="text" name="code" placeholder="Category Code" required>
            <input type="text" name="name" placeholder="Category Name" required>
            <button type="submit" name="create_category">Save Category</button>
        </form>

        <h3>Category List</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['code']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td class="actions">
                            <a href="category_form_update.php?id=<?= $row['id'] ?>">Update</a>
                            <a href="../actions/category_crud.php?delete=<?= $row['id'] ?>"
                                onclick="return confirm('Delete this category?')">Delete</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="top-buttons">
            <a href="dashboard.php" class="btn-gray">Back to Dashboard</a>
            <a href="../actions/export_category_csv.php" class="btn-blue">Export to CSV</a>
            <a href="../actions/logout.php" class="btn-red">Logout</a>
        </div>
    </div>
</body>

</html>