<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM master_brand WHERE user_id=? LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$brands = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$countResult = $conn->query("SELECT COUNT(*) as total FROM master_brand WHERE user_id=$user_id");
$total = $countResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Brand Management</title>
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

        .pagination {
            margin-top: 1rem;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            color: #2563eb;
            text-decoration: none;
        }

        .pagination a:hover {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Brand Management</h2>

        <form method="POST" action="../actions/brand_crud.php">
            <input type="hidden" name="id" value="">
            <input type="text" name="code" placeholder="Brand Code" required>
            <input type="text" name="name" placeholder="Brand Name" required>
            <button type="submit" name="create_brand">Save Brand</button>
        </form>

        <h3>Brand List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($brands as $brand): ?>
                    <tr>
                        <td><?= $brand['id'] ?></td>
                        <td><?= htmlspecialchars($brand['code']) ?></td>
                        <td><?= htmlspecialchars($brand['name']) ?></td>
                        <td><?= $brand['status'] ?></td>
                        <td class="actions">
                            <a href="brand_form_update.php?edit=<?= $brand['id'] ?>">Edit</a>
                            <a href="../actions/brand_crud.php?delete=<?= $brand['id'] ?>"
                                onclick="return confirm('Delete this brand?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="top-buttons">
            <a href="dashboard.php" class="btn-gray">Back to Dashboard</a>
            <a href="../actions/export_brand_csv.php" class="btn-blue">Export to CSV</a>
            <a href="../actions/logout.php" class="btn-red">Logout</a>
        </div>

        <div class="pagination">
            Pages:
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>