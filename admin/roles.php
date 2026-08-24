<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION['ic_no']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$error = "";


if (isset($_POST['add_role'])) {

    $ic_no = intval($_POST['ic_no']);
    $role_name = $_POST['role_name'];

    $employee_sql = "SELECT name FROM employees WHERE ic_no = ?";

    $employee_stmt = mysqli_prepare($conn, $employee_sql);

    mysqli_stmt_bind_param(
        $employee_stmt,
        "i",
        $ic_no
    );

    mysqli_stmt_execute($employee_stmt);

    $employee_result = mysqli_stmt_get_result($employee_stmt);

    if (mysqli_num_rows($employee_result) == 0) {

        $error = "This IC Number does not exist in Employee Management.";

    } 
    else {

        $employee = mysqli_fetch_assoc($employee_result);

        $name = $employee['name'];

        $check_sql = "SELECT ic_no FROM roles WHERE ic_no = ?";

        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param(
            $check_stmt,
            "i",
            $ic_no
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);


        if (mysqli_num_rows($check_result) > 0) {

            $error = "IC Number already exists, try another IC Number.";

        }
        else {

            $sql = "INSERT INTO roles
                    (ic_no, name, role_name)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "iss",
                $ic_no,
                $name,
                $role_name
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);

            header("Location: roles.php");
            exit();
        }

        mysqli_stmt_close($check_stmt);
    }

    mysqli_stmt_close($employee_stmt);
}


    if (isset($_POST['update_role'])) {

        $ic_no = intval($_POST['ic_no']);
        $name = trim($_POST['name']);
        $role_name = $_POST['role_name'];

        $sql = "UPDATE roles
                SET name = ?, role_name = ?
                WHERE ic_no = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $name,
            $role_name,
            $ic_no
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);


    if ($role_name === 'Admin') {

        $delete_karyashala = " 
         DELETE FROM karyashala_records 
         WHERE ic_no = ? ";

        $stmt1 = mysqli_prepare(
            $conn,
            $delete_karyashala
        );

        mysqli_stmt_bind_param(
            $stmt1,
            "i",
            $ic_no
        );

        mysqli_stmt_execute($stmt1);

        mysqli_stmt_close($stmt1);

       $delete_module = "
    DELETE FROM module_attendance
    WHERE ic_no = ?
";

$stmt2 = mysqli_prepare(
    $conn,
    $delete_module
);

mysqli_stmt_bind_param(
    $stmt2,
    "i",
    $ic_no
);

mysqli_stmt_execute($stmt2);

mysqli_stmt_close($stmt2);
    }

    

    header("Location: roles.php");
    exit();
}
 
if (isset($_GET['delete'])) {

    $ic_no = intval($_GET['delete']);

    $delete_sql = "DELETE FROM roles WHERE ic_no = ?";

    $delete_stmt = mysqli_prepare($conn, $delete_sql);

    mysqli_stmt_bind_param(
        $delete_stmt,
        "i",
        $ic_no
    );

    mysqli_stmt_execute($delete_stmt);

    mysqli_stmt_close($delete_stmt);

    header("Location: roles.php");
    exit();
}
$result = mysqli_query(
    $conn,
    "SELECT * FROM roles ORDER BY ic_no"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Management</title>
    <link rel="stylesheet" href="../css/admins.css">
</head>
<body>

<header class="topbar">
    <div>
        <h2>Role Management</h2>
        <p>Hindi Karyashala Portal</p>
    </div>
    <a href="dashboard.php" class="back">Dashboard </a>
</header>


<div class="page">
    
<div class="page-header">
        <h1>Role Records</h1>
        <button class="add-btn" onclick="openAdd()"> + Add Role</button>
    </div>

    <div class="table-box">

        <table>
            <thead>
                <tr>
                    <th>IC No.</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>


            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>
                    <td><?php echo htmlspecialchars($row['ic_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']);?></td>
                    <td><?php echo htmlspecialchars($row['role_name']);?></td>
                    <td>
                        <button class="view-btn" onclick='viewRole(<?php echo json_encode($row); ?>)'>
                                View </button>
                        <button class="edit-btn" onclick='editRole(<?php echo json_encode($row); ?>)'>
                                Edit </button>
                        <a class="delete-btn" href="roles.php?delete=<?php echo $row['ic_no']; ?>"
                            onclick="return confirm('Do you want to delete this role?');"> Delete</a>
                    </td>
                </tr>

            <?php } ?>
            </tbody>

        </table>
    </div>

</div>


<div id="addModal" class="modal">
    <div class="modal-box">

        <span class="close" onclick="closeAdd()"> × </span>
        <h2>Assign Role</h2>

        <?php if ($error != "") { ?>

            <div class="error-message">
                <?php
                echo htmlspecialchars($error);
                ?>
            </div>

        <?php } ?>


        <form method="POST">

            <label>IC No.</label>
            <input type="number" name="ic_no" id="role_ic" placeholder="Enter IC No." required 
            oninput="getEmployeeName()">

            <label>Employee Name</label>
            <input type="text" id="employee_name" placeholder="Employee name will appear here" readonly>

            <label>Role</label>
            <select name="role_name" required>
                <option value="">Select Role </option>
                <option value="Admin"> Admin </option>
                <option value="Karyashala"> Karyashala </option>
            </select>

            <button type="submit" name="add_role"> Assign Role </button>

        </form>

    </div>
</div>


<div id="viewModal" class="modal">
    <div class="modal-box">
        <span class="close" onclick="closeView()"> × </span>
        <h2>Role Details</h2>

        <p>
            <b>IC No.:</b>
            <span id="v_ic"></span>
        </p>

        <p>
            <b>Name:</b>
            <span id="v_name"></span>
        </p>

        <p>
            <b>Role:</b>
            <span id="v_role"></span>
        </p>

    </div>
</div>


<div id="editModal" class="modal">
    <div class="modal-box">

        <span class="close" onclick="closeEdit()"> × </span>
       <h2>Edit Role</h2>


        <form method="POST">

            <input type="hidden" name="ic_no" id="e_ic">

            <label>Name</label>
            <input type="text" name="name" id="e_name" required>

            <label>Role</label>
            <select name="role_name" id="e_role" required>
                <option value="Admin">Admin </option>
                <option value="Karyashala"> Karyashala </option>
            </select>

            <button type="submit" name="update_role"> Update Role</button>

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
        document.getElementById("role_ic").value;
    let nameBox =
        document.getElementById("employee_name");

    if (ic === "") {
        nameBox.value = "";
        return;
    }


    fetch("get_employee.php?ic_no=" + ic)

        .then(response => response.text())
        .then(data => {

            if (data === "NOT_FOUND") {
                nameBox.value =
                    "IC No. not found";
            }
             else {
                nameBox.value = data;
            }

        })

        .catch(error => {
            nameBox.value = "";
        });
}

function viewRole(row) {

    document.getElementById("v_ic").innerText = row.ic_no;
    document.getElementById("v_name").innerText = row.name;
    document.getElementById("v_role").innerText = row.role_name;
    document.getElementById("viewModal")
        .style.display = "flex";

}

function closeView() {

     document.getElementById("viewModal")
        .style.display = "none";
}

function editRole(row) {

    document.getElementById("e_ic").value = row.ic_no;
    document.getElementById("e_name").value = row.name;
    document.getElementById("e_role").value = row.role_name;
    document.getElementById("editModal")
        .style.display = "flex";

}

function closeEdit() {
    document.getElementById("editModal")
        .style.display = "none";
}


<?php if ($error != "") { ?>

document.getElementById("addModal")
    .style.display = "flex";

<?php } ?>


</script>


</body>
</html>