<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: views/dashboard.php");
    echo "<a href='views/dashboard.php'>Go to Dashboard</a>";
    exit();
} else {
    header("Location: views/login.php");
    echo "<a href='views/login.php'>Go to Login</a>";
    exit();
}
?>
