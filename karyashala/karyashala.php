<?php

session_start();
require_once "../db.php";

if (
    !isset($_SESSION['ic_no']) ||
    ($_SESSION['role'] !== 'Karyashala' && $_SESSION['role'] !== 'Admin')
) {
    header("Location: ../index.php");
    exit();
}

$error = "";
$success = "";


/* =====================================================
   ADD KARYASHALA RECORD
   ===================================================== */

if (isset($_POST['add_record'])) {

    $ic_no = intval($_POST['ic_no']);
    $starting_date = $_POST['starting_date'];
    $remarks = trim($_POST['remarks']);


    /* ================= BLOCK ================= */

    $year = date("Y", strtotime($starting_date));

    if ($year == 2023 || $year == 2024) {

        $block = "2023-2025";

    } elseif ($year == 2025 || $year == 2026) {

        $block = "2025-2027";

    } elseif ($year == 2027 || $year == 2028) {

        $block = "2027-2029";

    } else {

        $block = "";

    }


    if ($block == "") {

        $error = "Starting date is outside the allowed block.";

    } else {


        /* ================= CHECK EMPLOYEE ================= */

        $sql = "
            SELECT e.name
            FROM employees e
            INNER JOIN roles r
                ON e.ic_no = r.ic_no
            WHERE e.ic_no = ?
            AND r.role_name = 'Karyashala'
        ";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $ic_no
        );

        mysqli_stmt_execute($stmt);

        $employee_result = mysqli_stmt_get_result($stmt);


        if (mysqli_num_rows($employee_result) == 0) {

            $error = "This IC Number is not assigned to Karyashala.";

        } else {

            $employee = mysqli_fetch_assoc($employee_result);

            $employee_name = $employee['name'];


            /* =================================================
               IMPORTANT:
               NO DUPLICATE IC CHECK
               Same IC can have multiple records.
               ================================================= */

            $insert_sql = "
                INSERT INTO karyashala_records
                (
                    ic_no,
                    employee_name,
                    starting_date,
                    block,
                    remarks
                )
                VALUES (?, ?, ?, ?, ?)
            ";

            $insert_stmt = mysqli_prepare(
                $conn,
                $insert_sql
            );


            mysqli_stmt_bind_param(
                $insert_stmt,
                "issss",
                $ic_no,
                $employee_name,
                $starting_date,
                $block,
                $remarks
            );


            if (mysqli_stmt_execute($insert_stmt)) {

                $success =
                    "Karyashala record added successfully.";

            } else {

                $error =
                    "Unable to save Karyashala record: " .
                    mysqli_error($conn);

            }


            mysqli_stmt_close($insert_stmt);
        }


        mysqli_stmt_close($stmt);
    }
}


/* =====================================================
   FETCH KARYASHALA RECORDS
   ===================================================== */

$result = mysqli_query(
    $conn,
    "
    SELECT
        kr.id,
        kr.ic_no,
        kr.employee_name,
        kr.starting_date,
        kr.block,
        kr.remarks

    FROM karyashala_records kr

    INNER JOIN roles r
        ON kr.ic_no = r.ic_no

    WHERE r.role_name = 'Karyashala'

    ORDER BY
        kr.ic_no ASC,
        kr.id ASC
    "
);


if (!$result) {

    $error =
        "Unable to fetch Karyashala records: " .
        mysqli_error($conn);

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyashala Management</title>
    <link rel="stylesheet"  href="../css/karyashala.css">
</head>
<body>

<header class="topbar">
    <div>
        <h2>Karyashala Management</h2>
        <p>Hindi Karyashala Portal</p>
    </div>
    <a href="dashboard.php" class="back"> Dashboard </a>
</header>


<div class="page">


    <div class="page-header">
        <h1>Karyashala Records</h1>
        <button class="add-btn" onclick="openAdd()">+ Add Karyashala Record</button>
    </div>


    <?php if ($success != "") { ?>

        <div class="success-message">
            <?php
            echo htmlspecialchars($success);
            ?>
        </div>

    <?php } ?>


    <?php if ($error != "") { ?>

        <div class="error-message">
            <?php
            echo htmlspecialchars($error);
            ?>
        </div>

    <?php } ?>


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
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                
                <tr>
                    <td> <?php echo htmlspecialchars($row['ic_no']);?> </td>
                    <td> <?php echo htmlspecialchars( $row['employee_name']); ?> </td>
                    <td> <?php echo date ('d-m-Y', strtotime($row['starting_date'])); ?></td>
                    <td> <?php echo htmlspecialchars( $row['remarks'] ); ?></td>
                </tr>

            <?php } ?>
            </tbody>

        </table>
    </div>

</div>

<div id="addModal" class="modal">
    <div class="modal-box">

        <span class="close" onclick="closeAdd()"> × </span>
        <h2>Add Karyashala Record</h2>

        <form method="POST">

            <label>IC No.</label>
            <input type="number" name="ic_no" id="ic_no" placeholder="Enter IC No." required oninput="getEmployeeName()">

            <label>Employee Name</label>
            <input type="text" id="employee_name" placeholder="Employee name will appear here" readonly>

            <label>Starting Date</label>
            <input type="date" name="starting_date" required>

            <label>Remarks</label>
            <textarea name="remarks" rows="3" placeholder="Enter remarks"></textarea>

            <button type="submit" name="add_record">Save Record</button>

        </form>

    </div>
</div>


<script>

function openAdd() {
    document.getElementById("addModal")
        .style.display = "flex";
}

function closeAdd() {
    document.getElementById("addModal")
        .style.display = "none";
}

function getEmployeeName() {

    let ic =
        document.getElementById("ic_no").value;

    let nameBox =
        document.getElementById("employee_name");

    if (ic === "") {

        nameBox.value = "";

        return;
    }


    fetch(
        "get_karyashala_employee.php?ic_no=" + ic
    )

    .then(response => response.text())

    .then(data => {

        if (data === "NOT_FOUND") {

            nameBox.value =
                "Not a Karyashala employee";

        } 
        else {

            nameBox.value = data;

        }

    })

    .catch(error => {

        nameBox.value = "";
   
    });

}

</script>

</body>
</html>