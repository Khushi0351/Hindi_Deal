<?php

require_once "../db.php";

if (isset($_GET['ic_no'])) {

    $ic_no = intval($_GET['ic_no']);

    $sql = "SELECT e.name
            FROM employees e
            INNER JOIN roles r ON e.ic_no = r.ic_no
            WHERE e.ic_no = ?
            AND r.role_name = 'Karyashala'";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $ic_no
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        echo htmlspecialchars($row['name']);

    } else {

        echo "NOT_FOUND";
    }

    mysqli_stmt_close($stmt);
}

?>