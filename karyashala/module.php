
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
$error = "";
$success = "";

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

if (isset($_POST['save_attendance'])) {

    $ic_no = intval($_POST['ic_no']);
    $block = $_POST['block'];

    $attendance = $_POST['attendance'];

    $starting_date = $_POST['starting_date'];

    $karyashala_remarks = trim(
        $_POST['karyashala_remarks']
    );


    if (!in_array($block, $allowed_blocks)) {

        $error = "Invalid block selected.";

    }
    elseif (!in_array(
        $attendance,
        ['Present', 'Not Present']
    )) {

        $error = "Please select attendance.";

    }
    else {
        $check_sql = "
            SELECT
                kr.ic_no,
                kr.employee_name,
                e.designation

            FROM karyashala_records kr

            INNER JOIN employees e
                ON kr.ic_no = e.ic_no

            INNER JOIN roles r
                ON kr.ic_no = r.ic_no

            WHERE kr.ic_no = ?

            AND r.role_name = 'Karyashala'
        ";

        $check_stmt = mysqli_prepare(
            $conn,
            $check_sql
        );

        mysqli_stmt_bind_param(
            $check_stmt,
            "i",
            $ic_no
        );

        mysqli_stmt_execute(
            $check_stmt
        );

        $check_result =
            mysqli_stmt_get_result(
                $check_stmt
            );

        if (
            mysqli_num_rows(
                $check_result
            ) == 0
        ) {

            $error =
                "Employee not found in Karyashala.";

        }

        else {

            $employee =
                mysqli_fetch_assoc(
                    $check_result
                );

            $employee_name =
                $employee['employee_name'];

            $designation =
                $employee['designation'];

            $update_k_sql = "
                UPDATE karyashala_records

                SET starting_date = ?,
                    remarks = ?

                WHERE ic_no = ?
            ";

            $update_k_stmt =
                mysqli_prepare(
                    $conn,
                    $update_k_sql
                );

            mysqli_stmt_bind_param(
                $update_k_stmt,
                "ssi",
                $starting_date,
                $karyashala_remarks,
                $ic_no
            );

            mysqli_stmt_execute(
                $update_k_stmt
            );

            mysqli_stmt_close(
                $update_k_stmt
            );

            $existing_sql = "
                SELECT ic_no
                FROM module_attendance
                WHERE ic_no = ?
                AND block = ?
                ";
            
                $existing_stmt = mysqli_prepare(
                    $conn,
                    $existing_sql
                );

                if ($existing_stmt === false) {
                    die(
                        "SQL ERROR: " . mysqli_error($conn)
                    );
                }

                mysqli_stmt_bind_param(
                    $existing_stmt,
                    "is",
                    $ic_no,
                    $block
                );

            mysqli_stmt_execute(
                $existing_stmt
            );

            $existing_result =
                mysqli_stmt_get_result(
                    $existing_stmt
                );

            if (
                mysqli_num_rows(
                    $existing_result
                ) > 0
            ) {

                $update_sql = "
                    UPDATE module_attendance

                    SET attendance = ?,
                        employee_name = ?,
                        designation = ?

                    WHERE ic_no = ?
                    AND block = ?
                ";

                $update_stmt =
                    mysqli_prepare(
                        $conn,
                        $update_sql
                    );


                mysqli_stmt_bind_param(
                    $update_stmt,
                    "sssis",
                    $attendance,
                    $employee_name,
                    $designation,
                    $ic_no,
                    $block
                );

                mysqli_stmt_close(
                    $update_stmt
                );

                $update_stmt =
                    mysqli_prepare(
                        $conn,
                        $update_sql
                    );

                mysqli_stmt_bind_param(
                    $update_stmt,
                    "sssis",
                    $attendance,
                    $employee_name,
                    $designation,
                    $ic_no,
                    $block
                );

                mysqli_stmt_execute(
                    $update_stmt
                );

                mysqli_stmt_close(
                    $update_stmt
                );

            }

            else {

                $insert_sql = "
                    INSERT INTO module_attendance
                    (
                        ic_no,
                        employee_name,
                        designation,
                        block,
                        attendance
                    )

                    VALUES (?, ?, ?, ?, ?)
                ";

                $insert_stmt =
                    mysqli_prepare(
                        $conn,
                        $insert_sql
                    );

                mysqli_stmt_bind_param(
                    $insert_stmt,
                    "issss",
                    $ic_no,
                    $employee_name,
                    $designation,
                    $block,
                    $attendance
                );

                mysqli_stmt_execute(
                    $insert_stmt
                );

                mysqli_stmt_close(
                    $insert_stmt
                );

            }

            mysqli_stmt_close(
                $existing_stmt
            );

            $success =
                "Record updated successfully.";

        }

        mysqli_stmt_close(
            $check_stmt
        );

    }
}

if (isset($_GET['delete'])) {

    $ic_no = intval(
        $_GET['delete']
    );

    $block =
        $_GET['block'] ?? "";

    if (
        in_array(
            $block,
            $allowed_blocks
        )
    ) {

        $delete_sql = "
            DELETE FROM module_attendance

            WHERE ic_no = ?
            AND block = ?
        ";

        $delete_stmt =
            mysqli_prepare(
                $conn,
                $delete_sql
            );

        mysqli_stmt_bind_param(
            $delete_stmt,
            "is",
            $ic_no,
            $block
        );

        mysqli_stmt_execute(
            $delete_stmt
        );

        mysqli_stmt_close(
            $delete_stmt
        );

    }


    header(
        "Location: module.php?block=" .
        urlencode($block)
    );

    exit();
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
        INNER JOIN roles r
            ON kr.ic_no = r.ic_no
        LEFT JOIN module_attendance ma
            ON kr.ic_no = ma.ic_no
            AND ma.block = ?
        WHERE kr.block = ?
        AND r.role_name = 'Karyashala'
        ORDER BY kr.ic_no ASC
    ";

    $stmt =
        mysqli_prepare(
            $conn,
            $sql
        );

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $selected_block,
        $selected_block
    );

    mysqli_stmt_execute(
        $stmt
    );

    $result =
        mysqli_stmt_get_result(
            $stmt
        );

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta  name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Module - Hindi Karyashala Portal </title>
    <link rel="stylesheet" href="../css/karyashala.css">

</head>
<body>


<header class="topbar">
    <div>
        <h2>Module</h2>
        <p>Hindi Karyashala Portal</p>
    </div>
    <a href="dashboard.php" class="back">Dashboard</a>
</header>


<div class="page">

    <?php if ($success != "") { ?>
        <div class="success-message">
            <?php
            echo htmlspecialchars( $success );
            ?>
        </div>
    <?php } ?>


    <?php if ($error != "") { ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error );
            ?>
        </div>
    <?php } ?>


    <div class="block-select-box">
        <h1>Select Block </h1>

        <form method="GET">

            <select name="block" onchange="this.form.submit()" required>
                <option value=""> Select Block</option>
                <?php
                foreach ($allowed_blocks as $block) {
                ?>

                <option value= "<?php echo htmlspecialchars( $block); ?>"      

                    <?php
                    if ( $selected_block == $block) 
                    {
                        echo "selected";
                    }
                    ?> >

                    <?php
                        echo str_replace( "-", " - ", $block);
                    ?>

                </option>

                <?php } ?>

            </select>
        </form>
    </div>


    <?php
    if (
        $selected_block != ""
    ) {
    ?>

        <div class="page-header">
            <h1> Module:
                <?php echo htmlspecialchars( $selected_block );?>
            </h1>
        </div>

        <div class="table-box">
            <table>

                <thead>
                    <tr>
                        <th>IC No.</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Starting Date</th>
                        <th>Karyashala Remarks</th>
                        <th>Attendance</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>


                <?php
                if ( $result && mysqli_num_rows( $result ) > 0 )
                    {
                    while ($row = mysqli_fetch_assoc( $result))
                        {
                ?>

                    <tr>
                        <td><?php echo htmlspecialchars($row['ic_no'] );?></td>
                        <td> <?php echo htmlspecialchars($row[ 'employee_name']);?></td>
                        <td><?php echo htmlspecialchars( $row['designation']);?></td>
                        <td> <?php echo date ('d-m-Y', strtotime($row['starting_date'])); ?></td>
                        <td><?php echo htmlspecialchars( $row['karyashala_remarks']);?></td>
                        <td>
                            <?php
                            if (!empty( $row['attendance']))
                            {
                                echo htmlspecialchars($row['attendance']);
                            }
                            else {
                                echo "Not Marked";
                            }
                            ?>
                        </td>

                        <td>  
                            <button class="view-btn" onclick='viewRecord(
                                    <?php echo json_encode($row); ?>)'>View 
                            </button>

                            <?php if (empty($row['attendance'])) { ?>

                            <button class="mark-btn"  onclick='markRecord(
                               <?php echo json_encode($row); ?>)'>Mark
                            </button>

                             <?php } else { ?>

                            <button class="edit-btn" onclick='editRecord(
                               <?php echo json_encode($row); ?>)'> Edit
                            </button>
       
                            <a class="delete-btn" href="module.php?delete=<?php echo urlencode($row['ic_no']); ?>
                                &block=<?php echo urlencode($selected_block); ?>"
                                onclick="return confirm('Delete attendance record?');"> Delete
                            </a>

                            <?php } ?>

                        </td>
                    </tr>

                <?php
                    }
                }
                else {
                ?>

                    <tr>
                        <td colspan="7" style="text-align:center;">
                        No Karyashala records found in this block.</td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>

    <?php } ?>

</div>


<div  id="viewModal" class="modal">
    <div class="modal-box">
        <span class="close" onclick="closeView()"> ×</span>
        <h2> Employee Details</h2>

        <p>
            <b>IC No.:</b>
            <span id="v_ic"></span>
        </p>

        <p>
            <b>Name:</b>
            <span id="v_name"></span>
        </p>

        <p>
            <b>Designation:</b>
            <span id="v_designation"></span>
        </p>

        <p>
            <b>Starting Date:</b>
            <span id="v_date"></span>
        </p>

        <p>
            <b>Karyashala Remarks:</b>
            <span id="v_kremarks"></span>
        </p>

        <p>
            <b>Attendance:</b>
            <span id="v_attendance"></span>
        </p>

    </div>
</div>



<div id="markModal" class="modal">

<div class="modal-box">
        <span class="close" onclick="closeMark()">×</span>
        <h2>Mark Attendance</h2>

        <form method="POST">

            <input type="hidden" name="ic_no" id="m_ic">
            <input type="hidden" name="block"
                   value="<?php echo htmlspecialchars($selected_block); ?>">

            <div class="edit-form-row">
                <label>IC No.</label>
                <input type="text" id="m_ic_display" class="readonly-field" readonly>
            </div>

            <div class="edit-form-row">
                <label>Name</label>
                <input type="text" id="m_name" class="readonly-field" readonly>
            </div>

            <div class="edit-form-row">
                <label>Designation</label>
                <input type="text" id="m_designation" class="readonly-field" readonly>
            </div>

            <div class="edit-form-row">
                <label>Starting Date</label>
                <input type="date" name="starting_date" id="m_date" required>
            </div>

            <div class="edit-form-row">
                <label>Karyashala Remarks</label>
                <textarea name="karyashala_remarks"id="m_kremarks" rows="3"></textarea>
            </div>

            <div class="edit-form-row">
                <label>Attendance</label>
                <select name="attendance" id="m_attendance" required>
                    <option value="">Select Attendance</option>
                    <option value="Present">Present</option>
                    <option value="Not Present">Not Present</option>
                </select>
            </div>

            <button type="submit" name="save_attendance">Mark Attendance</button>

        </form>

    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-box">
        <span class="close" onclick="closeEdit()">× </span>
        <h2> Edit Module Record</h2>

        <form method="POST">
            <input type="hidden" name="ic_no" id="e_ic">
            <input type="hidden" name="block"
                value="<?php echo htmlspecialchars( $selected_block); ?>">

            <div class="edit-form-row">
                <label> IC No. </label>
                <input type="text" id="e_ic_display"class="readonly-field" readonly>
            </div>

            <div class="edit-form-row">
                <label>Name</label>
                <input type="text" id="e_name" class="readonly-field" readonly>
            </div>

            <div class="edit-form-row">
                <label>Designation</label>
                <input type="text" id="e_designation" class="readonly-field" readonly>
            </div>

            <div class="edit-form-row">
                <label>Starting Date</label>
                <input type="date" name="starting_date" id="e_date" required>
            </div>

            <div class="edit-form-row">
                <label>Karyashala Remarks</label>
                <textarea name="karyashala_remarks" id="e_kremarks" rows="3" 
                    placeholder="Enter Karyashala remarks" ></textarea>
            </div>

            <div class="edit-form-row">
                <label>Attendance</label>
                    <select name="attendance" id="e_attendance" required>
                    <option value="">Select Attendance</option>
                    <option value="Present">Present</option>
                    <option value="Not Present">Not Present</option>
                </select>
            </div>

            <button type="submit" name="save_attendance">Save Changes</button>
        </form>

    </div>
</div>


<script>

function viewRecord(row) {

    document.getElementById( "v_ic").innerText = row.ic_no;

    document.getElementById("v_name" ).innerText = row.employee_name;

    document.getElementById( "v_designation" ).innerText = row.designation;

    document.getElementById( "v_date" ).innerText = formatDate(row.starting_date);

    document.getElementById("v_kremarks" ).innerText = row.karyashala_remarks || "—";

    document.getElementById( "v_attendance" ).innerText = row.attendance || "Not Marked";

    document.getElementById("viewModal" ).style.display = "flex";
}

function formatDate(dateString){

    if (!dateString){
        return "";
    }
    const parts = dateString.split("-");

    return parts[2] + "-" + parts[1] + "-" + parts[0];
}

function closeView() {
    document.getElementById( "viewModal" ).style.display = "none";
}


/* =====================================================
   MARK RECORD
   ===================================================== */

function markRecord(row) {

    document.getElementById("m_ic").value =
        row.ic_no;

    document.getElementById("m_ic_display").value =
        row.ic_no;

    document.getElementById("m_name").value =
        row.employee_name;

    document.getElementById("m_designation").value =
        row.designation;

    document.getElementById("m_date").value =
        row.starting_date;

    document.getElementById("m_kremarks").value =
        row.karyashala_remarks || "";

    document.getElementById("m_attendance").value =
        "";


    document.getElementById(
        "markModal"
    ).style.display = "flex";
}


/* =====================================================
   CLOSE MARK
   ===================================================== */

function closeMark() {

    document.getElementById(
        "markModal"
    ).style.display = "none";
}



function editRecord(row) {

    document.getElementById( "e_ic" ).value = row.ic_no;

    document.getElementById( "e_ic_display" ).value = row.ic_no;

    document.getElementById( "e_name" ).value = row.employee_name;

    document.getElementById( "e_designation" ).value = row.designation;

    document.getElementById("e_date").value = row.starting_date;

    document.getElementById("e_kremarks" ).value =  row.karyashala_remarks  || "";

    document.getElementById("e_attendance").value = row.attendance || "";

    document.getElementById("editModal").style.display = "flex";

}

function closeEdit() {
    document.getElementById( "editModal" ).style.display =  "none";
}


window.onclick = function(event) {

    const viewModal =
        document.getElementById("viewModal");

    const markModal =
        document.getElementById("markModal");

    const editModal =
        document.getElementById("editModal");


    if (event.target === viewModal) {

        viewModal.style.display = "none";
    }


    if (event.target === markModal) {

        markModal.style.display = "none";
    }


    if (event.target === editModal) {

        editModal.style.display = "none";
    }

};


</script>


</body>
</html>
