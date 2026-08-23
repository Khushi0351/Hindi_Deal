<?php
session_start();

if (!isset($_SESSION['ic_no']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hindi Karyashala Portal</title>
    <link rel="stylesheet" href="../css/admins.css">
</head>

<body>

<header class="topbar">
    <div>
        <h2>Hindi Karyashala Portal</h2>
        <h3>Admin Dashboard</h3>
    </div>
    <a href="../logout.php" class="logout">Logout</a>
</header>

<div class="dashboard">
    <div class="menu">

        <a href="employees.php" class="menu-card">
            <h2>Employment Management</h2>
        </a>
     
        <a href="roles.php" class="menu-card">
            <h2>Role Management</h2>
        </a>
    </div>

</div>

</body>
</html>