<?php include '../includes/auth.php'; ?>
<?php include '../config/database.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - MDM System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9fafb;
            padding: 2rem;
            margin: 0;
        }

        .dashboard-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        }

        h2 {
            text-align: center;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        p {
            text-align: center;
            font-size: 1.1rem;
            color: #4b5563;
            margin-bottom: 2.5rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
        }

        .tile {
            background-color: #f3f4f6;
            border-radius: 10px;
            padding: 1.25rem;
            text-align: center;
            text-decoration: none;
            color: #1f2937;
            transition: all 0.3s ease;
            box-shadow: 0 5px 12px rgba(0,0,0,0.03);
        }

        .tile:hover {
            background-color: #2563eb;
            color: white;
            transform: translateY(-3px);
        }

        .tile i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .tile span {
            display: block;
            font-weight: 600;
            font-size: 1rem;
        }

        .logout-btn {
            display: block;
            margin: 2rem auto 0;
            width: fit-content;
            background-color: #dc2626;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #b91c1c;
        }

        @media (max-width: 500px) {
            h2 {
                font-size: 1.5rem;
            }

            .tile i {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Dashboard - MDM System</h2>
    <p>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></p>

    <div class="grid">
        <a href="brand_form.php" class="tile">
            <i class="fas fa-tags"></i>
            <span>Manage Brands</span>
        </a>
        <a href="category_form.php" class="tile">
            <i class="fas fa-list"></i>
            <span>Manage Categories</span>
        </a>
        <a href="item_form.php" class="tile">
            <i class="fas fa-box-open"></i>
            <span>Manage Items</span>
        </a>
    </div>

    <a href="../actions/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

</body>
</html>