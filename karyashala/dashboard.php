<?php

session_start();

if (!isset($_SESSION['ic_no']) || $_SESSION['role'] !== 'Karyashala') {
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
    <title>Karyashala Dashboard - Hindi Karyashala Portal</title>
    <link rel="stylesheet" href="../css/karyashala.css">
</head>
<body>

<header class="topbar">
    <div>
        <h2>Hindi Karyashala Portal</h2>
        <h3>Karyashala Dashboard</h3>
    </div>
    <a href="../logout.php"  class="logout"> Logout </a>
</header>



<div class="dashboard">
    <div class="menu">
        <a href="karyashala.php" class="menu-card">
            <h2>Karyashala Management</h2>
        </a>

        <a href="block.php" class="menu-card">
            <h2>Block</h2>
        </a>

        <a href="module.php" class="menu-card">
            <h2>Module </h2>
        </a>

        <a href="report.php" class="menu-card">
            <h2>Report</h2>
        </a>

    </div>
</div>

</body>
</html>