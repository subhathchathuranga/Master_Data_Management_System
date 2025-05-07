<?php
session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - MDM System</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 1.5rem;
            text-align: center;
            color: #333;
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        form input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.2rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #16a34a;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #15803d;
        }

        .error-message {
            color: #dc2626;
            margin-bottom: 1rem;
            text-align: center;
        }

        .login-link {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #2563eb;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>

    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="../actions/register.php" method="POST">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" name="register">Register</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>.
    </div>
</div>

</body>
</html>
