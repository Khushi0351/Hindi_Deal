
<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION['ic_no']) || $_SESSION['role'] !== 'Karyashala') 
{
    header("Location: ../index.php");
    exit();
}

$selected_block = "";
$result = null;
$error = "";

$allowed_blocks = [
    "2023-2025",
    "2025-2027",
    "2027-2029"
];


if (isset($_GET['block'])) {

    $selected_block = $_GET['block'];

    if (!in_array($selected_block, $allowed_blocks)) {

        $selected_block = "";

        $error = "Invalid block selected.";
    }
}


if ($selected_block != "") {

    $sql = "
        SELECT
            kr.ic_no,
            kr.employee_name,
            e.designation,
            kr.starting_date,
            kr.remarks AS karyashala_remarks,

            ma.attendance

        FROM karyashala_records kr

        INNER JOIN employees e
            ON kr.ic_no = e.ic_no

        LEFT JOIN module_attendance ma
            ON kr.ic_no = ma.ic_no
            AND ma.block = kr.block

        WHERE kr.block = ?

        ORDER BY kr.ic_no ASC
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $selected_block
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta  name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Hindi Karyashala Portal</title>
    <link  rel="stylesheet"  href="../css/karyashala.css">
       
</head>
<body>


<header class="topbar">

    <div>
        <h2>Reports</h2>
        <p>Hindi Karyashala Portal</p>
    </div>
    <a href="dashboard.php" class="back">Dashboard</a>
</header>


<div class="page">


    <!-- ==========================
         REPORT SELECTION
         ========================== -->

    <div class="report-select-box">

        <h2>Generate Attendance Report</h2>

        <form method="GET">

            <label>
                <b>Select Year / Block</b>
            </label>

            <select
                name="block"
                required>

                <option value="">
                    Select Year / Block
                </option>

                <?php foreach ($allowed_blocks as $block) { ?>

                    <option
                        value="<?php echo htmlspecialchars($block); ?>"
                        <?php
                        if ($selected_block == $block) {
                            echo "selected";
                        }
                        ?>>

                        <?php
                        echo str_replace(
                            "-",
                            " - ",
                            $block
                        );
                        ?>

                    </option>

                <?php } ?>

            </select>


            <button
                type="submit"
                class="generate-btn">

                Generate Report

            </button>

        </form>

    </div>


    <?php if ($error != "") { ?>

        <div class="error-message">

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php } ?>


    <?php if ($selected_block != "") { ?>


        <!-- ==========================
             REPORT HEADER
             ========================== -->

        <div class="report-header">

            <h1>
                Karyashala Attendance Report
            </h1>

            <p>
                Year / Block:
                <strong>
                    <?php
                    echo htmlspecialchars(
                        $selected_block
                    );
                    ?>
                </strong>
            </p>

        </div>


        <button
            class="print-btn"
            onclick="window.print()">

            Generate / Print Report

        </button>


        <!-- ==========================
             REPORT TABLE
             ========================== -->

        <div class="table-box">

            <table>

                <thead>

                    <tr>

                        <th>Sr. No.</th>

                        <th>IC No.</th>

                        <th>Name</th>

                        <th>Designation</th>

                        <th>Starting Date</th>

                        <th>Karyashala Remarks</th>

                        <th>Attendance</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                $sr_no = 1;

                if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ) {

                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {

                ?>

                    <tr>

                        <td>
                            <?php
                            echo $sr_no++;
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['ic_no']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['employee_name']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['designation']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['starting_date']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['karyashala_remarks']
                                ?? ""
                            );
                            ?>
                        </td>


                        <td>

                            <?php

                            if (
                                $row['attendance']
                                === 'Present'
                            ) {

                                echo
                                    '<span class="present">
                                    Present
                                    </span>';

                            }
                            elseif (
                                $row['attendance']
                                === 'Not Present'
                            ) {

                                echo
                                    '<span class="not-present">
                                    Not Present
                                    </span>';

                            }
                            else {

                                echo
                                    '<span class="not-marked">
                                    Not Marked
                                    </span>';

                            }

                            ?>

                        </td>

                    </tr>

                <?php

                    }

                }
                else {

                ?>

                    <tr>

                        <td
                            colspan="7"
                            style="text-align:center;">

                            No records found for
                            this Year / Block.

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>


    <?php } ?>


</div>

</body>

</html>

