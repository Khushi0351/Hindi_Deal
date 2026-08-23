
<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION['ic_no']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['delete'])) {

    $ic_no = intval($_GET['delete']);

    /* DELETE ROLE RECORD */

    $role_sql = "DELETE FROM roles WHERE ic_no = ?";
    $role_stmt = mysqli_prepare($conn, $role_sql);

    mysqli_stmt_bind_param(
        $role_stmt,
        "i",
        $ic_no
    );

    mysqli_stmt_execute($role_stmt);
    mysqli_stmt_close($role_stmt);


    $karyashala_sql = "DELETE FROM karyashala_records WHERE ic_no = ?";
    $karyashala_stmt = mysqli_prepare(
        $conn,
        $karyashala_sql
    );

    mysqli_stmt_bind_param(
        $karyashala_stmt,
        "i",
        $ic_no
    );

    mysqli_stmt_execute($karyashala_stmt);
    mysqli_stmt_close($karyashala_stmt);


    $employee_sql = "DELETE FROM employees WHERE ic_no = ?";
    $employee_stmt = mysqli_prepare(
        $conn,
        $employee_sql
    );

    mysqli_stmt_bind_param(
        $employee_stmt,
        "i",
        $ic_no
    );

    mysqli_stmt_execute($employee_stmt);
    mysqli_stmt_close($employee_stmt);


    header("Location: employees.php");
    exit();
}


if (isset($_POST['add_employee'])) {

    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $emp_group = trim($_POST['emp_group']);
    $password = trim($_POST['password']);

    $sql = "INSERT INTO employees
            (name, designation, emp_group, password)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $name,
        $designation,
        $emp_group,
        $password
    );

    mysqli_stmt_execute($stmt);

    header("Location: employees.php");
    exit();
}


if (isset($_POST['update_employee'])) {

    $ic_no = intval($_POST['ic_no']);
    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $emp_group = trim($_POST['emp_group']);
    $password = trim($_POST['password']);

    $sql = "UPDATE employees
            SET name = ?, designation = ?, emp_group = ?, password = ?
            WHERE ic_no = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $name,
        $designation,
        $emp_group,
        $password,
        $ic_no
    );

    mysqli_stmt_execute($stmt);

    header("Location: employees.php");
    exit();
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM employees ORDER BY ic_no"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employment Management</title>
    <link rel="stylesheet" href="../css/admins.css">
</head>

<body>

<header class="topbar">

    <div>
        <h2>Employment Management</h2>
        <p>Hindi Karyashala Portal</p>
    </div>
    <a href="dashboard.php" class="back">Dashboard</a>

</header>

<div class="page">

    <div class="page-header">
        <h1>Employee Records</h1>
        <button class="add-btn" onclick="openAdd()">+ Add Employee</button>
    </div>

    <div class="table-box">

        <table>
            <thead>
                <tr>
                    <th>IC No.</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Group</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>
                    <td><?php echo $row['ic_no']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['designation']); ?></td>
                    <td><?php echo htmlspecialchars($row['emp_group']); ?></td>
                    <td>••••••</td>

                    <td>
                        <button class="view-btn" onclick='viewEmployee(<?php echo json_encode($row); ?>)'>
                            View</button>
                        <button class="edit-btn"onclick='editEmployee(<?php echo json_encode($row); ?> )'>
                            Edit</button>
                        <a class="delete-btn"
                           href="employees.php?delete=<?php echo $row['ic_no']; ?>"
                           onclick="return confirm('Do you want to delete this employee?');">
                           Delete
                        </a>
                    </td>
                </tr>

            <?php } ?>

            </tbody>
        </table>

    </div>

</div>


<div id="addModal" class="modal">
    <div class="modal-box">

        <span class="close" onclick="closeAdd()">×</span>
        <h2>Add Employee</h2>

        <form method="POST">

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Designation</label>
            <select name="designation" id="add_designation" required>
                <option value="Select">Select </option>
                <option value="Scientist A">Scientist A</option>
                <option value="Scientist B">Scientist B</option>
                <option value="Scientist C">Scientist C</option>
                <option value="Scientist D">Scientist D</option>    
                <option value="Scientist E">Scientist E</option>
            </select>

            <label>Group</label>
            <input type="text" name="emp_group" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="add_employee">Save Employee</button>

        </form>

    </div>
</div>



<div id="viewModal" class="modal">
    <div class="modal-box">

        <span class="close" onclick="closeView()">×</span>
        <h2>Employee Details</h2>

        <p><b>IC No.:</b> <span id="v_ic"></span></p>
        <p><b>Name:</b> <span id="v_name"></span></p>
        <p><b>Designation:</b> <span id="v_designation"></span></p>
        <p><b>Group:</b> <span id="v_group"></span></p>

    </div>
</div>


<div id="editModal" class="modal">
    <div class="modal-box">

        <span class="close" onclick="closeEdit()">×</span>
        <h2>Edit Employee</h2>

        <form method="POST">

            <input type="hidden" name="ic_no" id="e_ic">

            <label>Name</label>
            <input type="text" name="name" id="e_name" required>

            <label>Designation</label>
            <select name="designation" id="e_designation" required>
                <option value="">Select </option>
                <option value="Scientist A">Scientist A</option>
                <option value="Scientist B">Scientist B</option>
                <option value="Scientist C">Scientist C</option>
                <option value="Scientist D">Scientist D</option>    
                <option value="Scientist E">Scientist E</option>
            </select>

            <label>Group</label>
            <input type="text" name="emp_group" id="e_group" required>

            <label>Password</label>
            <input type="password" name="password" id="e_password" required>

            <button type="submit" name="update_employee">Update Employee</button>

        </form>

    </div>
</div>


<script>

function openAdd() {
    document.getElementById("addModal").style.display = "flex";
}

function closeAdd() {
    document.getElementById("addModal").style.display = "none";
}

function viewEmployee(row) {

    document.getElementById("v_ic").innerText = row.ic_no;
    document.getElementById("v_name").innerText = row.name;
    document.getElementById("v_designation").innerText = row.designation;
    document.getElementById("v_group").innerText = row.emp_group;

    document.getElementById("viewModal").style.display = "flex";
}

function closeView() {
    document.getElementById("viewModal").style.display = "none";
}

function editEmployee(row) {

    document.getElementById("e_ic").value = row.ic_no;
    document.getElementById("e_name").value = row.name;
    document.getElementById("e_designation").value = row.designation;
    document.getElementById("e_group").value = row.emp_group;
    document.getElementById("e_password").value = row.password;

    document.getElementById("editModal").style.display = "flex";
}

function closeEdit() {
    document.getElementById("editModal").style.display = "none";
}

</script>

</body>
</html>