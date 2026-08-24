<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION['ic_no']) || $_SESSION['role'] !== 'Karyashala' 
 && $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}
$selected_block = "";
$result = null;

if (isset($_GET['block'])) {

    $selected_block = $_GET['block'];

    $allowed_blocks = [
        "2023-2025",
        "2025-2027",
        "2027-2029"
    ];

    if (in_array($selected_block, $allowed_blocks)) {

    $sql = "SELECT
                kr.ic_no,
                kr.employee_name,
                kr.starting_date,
                kr.remarks
            FROM karyashala_records kr
            INNER JOIN roles r
                ON kr.ic_no = r.ic_no
            WHERE r.role_name = 'Karyashala'
            AND (
                (? = '2023-2025'
                    AND YEAR(kr.starting_date) IN (2023, 2024))

                OR

                (? = '2025-2027'
                    AND YEAR(kr.starting_date) IN (2025, 2026))

                OR

                (? = '2027-2029'
                    AND YEAR(kr.starting_date) IN (2027, 2028))
            )
            ORDER BY kr.ic_no  ASC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $selected_block,
        $selected_block,
        $selected_block
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    }
    else{

        $selected_block = "";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block - Hindi Karyashala Portal</title>
    <link rel="stylesheet" href="../css/karyashala.css">
</head>
<body>

<header class="topbar">
    <div>
        <h2>Block</h2>
        <p>Hindi Karyashala Portal</p>
    </div>
    <a href="dashboard.php" class="back">Dashboard </a>
</header>


<div class="page">

    <div class="block-select-box">
        <h1>Select Block</h1>

        <form method="GET">
            <select name="block" onchange="this.form.submit()" required>
                <option value="">Select Block </option>
                
                <option value="2023-2025"
                 <?php if ($selected_block == "2023-2025") echo "selected"; ?>>
                    2023 - 2025</option>
                
                    <option value="2025-2027"
                    <?php if ($selected_block == "2025-2027") echo "selected"; ?>>
                    2025 - 2027 </option>

                <option value="2027-2029"
                    <?php if ($selected_block == "2027-2029") echo "selected";?>>
                    2027 - 2029 </option>
            </select>

        </form>
    </div>


    <?php if ($selected_block != "") { ?>

        <div class="page-header">
            <h1> Block: <?php echo htmlspecialchars($selected_block); ?></h1>
        </div>

        <div class="table-box">

            <table>
                <thead>
                    <tr>
                        <th>IC No.</th>
                        <th>Employee Name</th>
                        <th>Starting Date</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>

                <?php

                if ($result &&
                    mysqli_num_rows($result) > 0) {

                    while ($row =
                           mysqli_fetch_assoc($result)) {

                ?>

                    <tr>
                        <td><?php echo htmlspecialchars( $row['ic_no'] );?></td>
                        <td><?php echo htmlspecialchars( $row['employee_name'] );?></td>
                        <td> <?php echo date ('d-m-Y', strtotime($row['starting_date'])); ?></td>
                        <td><?php echo htmlspecialchars( $row['remarks'] );?></td>
                    </tr>

                <?php

                    }

                } 
                else {

                ?>
                    <tr>
                        <td colspan="4" style="text-align:center;"> No Karyashala records 
                            found for this block.</td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>

        </div>

    <?php } ?>


</div>

</body>
</html>