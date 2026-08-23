<?php
session_start();
require_once "db.php";

$error = "";

if (isset($_POST['login'])) {

    $ic_no = trim($_POST['ic_no']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM employees WHERE ic_no = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $ic_no);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $employee = mysqli_fetch_assoc($result);

        if ($password === $employee['password']) {

            $role_sql = "SELECT role_name FROM roles WHERE ic_no = ?";
            $role_stmt = mysqli_prepare($conn, $role_sql);

            mysqli_stmt_bind_param($role_stmt, "i", $ic_no);
            mysqli_stmt_execute($role_stmt);

            $role_result = mysqli_stmt_get_result($role_stmt);

            if (mysqli_num_rows($role_result) == 1) {

                $role_data = mysqli_fetch_assoc($role_result);
                $role = $role_data['role_name'];

                $_SESSION['ic_no'] = $employee['ic_no'];
                $_SESSION['name'] = $employee['name'];
                $_SESSION['role'] = $role;

                if ($role === "Admin") {
                    header("Location: admin/dashboard.php");
                    exit();
                }

                if ($role === "Karyashala") {
                    header("Location: karyashala/dashboard.php");
                    exit();
                }

            } 
            else {
                $error = "Role is not assigned.";
            }

        } 
        else {
            $error = "Invalid IC No. or Password.";
        }

    }
    else {
        $error = "Invalid IC No. or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEAL Login</title>
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>

<div class="login-container">
    <div class="login-box">

        <h2>Defence Electronics Application Laboratory</h2>
        <p class="subtitle">DEAL, DRDO Dehradun</p>

        <img src="deal_logo.jpg" alt="DEAL DRDO Logo" class="deal-logo">
         <h3 class="portal-title">Hindi Karyashala Portal</h3><br><br>

         <?php if ($error != "") { ?>

            <p style="color:red; margin-bottom:15px;">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php } ?>

       
        <form action="" method="POST">
            <div class="input-group">
                <label for="ic_no">IC No.</label>
                <input type="number" id="ic_no" name="ic_no" placeholder="Enter IC No." required >
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password"  name="password" placeholder="Enter Password" required >       
            </div>

            <button type="submit" name="login">Login </button>

        </form>

    </div>

</div>

</body>
</html>