<?php
include('server.php');
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$middle_name = $_POST['middle_name'];

$sql2 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), corporation_name) ASC";
$stmt2 = sqlsrv_query($db, $sql2);
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    echo "<option>" . $row['corporation_name'] . "</option>";
}
if (empty($_POST['aff_comp'])) {
    $sql = "INSERT INTO dbo.tbl_shareholder(first_name,
    middle_name, 
    last_name,
    last_update,
    is_deleted)
        VALUES('$first_name',
        '$middle_name',
        '$last_name',
        '$datetime',
        '0')";
    $stmt = sqlsrv_query($db, $sql);
    if ($stmt) {
        $sql1 = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), first_name) ASC";
        $stmt1 = sqlsrv_query($db, $sql1);
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            echo "<option>" . $row['first_name'] . ' ' . $row['last_name'] . "</option>";
        }
    }
} else {
    $aff_comp = $_POST['aff_comp'];
    $held_position = $_POST['held_position'];
    $int_ext = $_POST['int_ext'];
    $shares_owned = $_POST['shares_owned'];
    $type_of_shares = $_POST['type_of_shares'];
    $remarks = $_POST['remarks'];

    $stock_cert = $_FILES['stock_cert']['name'];
    $stock_cert_tmp = $_FILES['stock_cert']['tmp_name'];

    // handlers
    $aff_comp_h = "";
    $held_position_h = "";
    $int_ext_h = "";
    $shares_owned_h = "";
    $type_of_shares_h = "";
    $stocks_cert_h = "";
    $remarks_h = "";

    $msg = "";

    foreach ($stock_cert as $key => $value) {
        $target = "images/" . basename($value);
        if ($stocks_cert_h) $stocks_cert_h .= ',';
        $stocks_cert_h .= $value;
        foreach ($stock_cert_tmp as $k => $v) {
            if (move_uploaded_file($v, $target)) {
                $msg = "Success";
            }
        }
    }
    foreach ($aff_comp as $key => $value) {
        if ($aff_comp_h) $aff_comp_h .= ',';
        $aff_comp_h .= $value;
    }
    foreach ($held_position as $key => $value) {
        if ($held_position_h) $held_position_h .= ',';
        $held_position_h .= $value;
    }
    foreach ($int_ext as $key => $value) {
        if ($int_ext_h) $int_ext_h .= ',';
        $int_ext_h .= $value;
    }
    foreach ($shares_owned as $key => $value) {
        if ($shares_owned_h) $shares_owned_h .= '|';
        $shares_owned_h .= $value;
    }
    foreach ($type_of_shares as $key => $value) {
        if ($type_of_shares_h) $type_of_shares_h .= ',';
        $type_of_shares_h .= $value;
    }
    foreach ($remarks as $key => $value) {
        if ($remarks_h) $remarks_h .= ',';
        $remarks_h .= $value;
    }
    // echo "<script>alert('".$aff_comp_h."')</script>";
    // echo "<script>alert('".$held_position_h."')</script>";
    // echo "<script>alert('".$stocks_owned_h."')</script>";
    // echo "<script>alert('".$stocks_cert_h."')</script>";
    // echo "<script>alert('".$msg."')</script>";
    // $arr = explode(",", $stocks_cert_h);
    // $last = $arr[0];
    // echo "<img src='images/".$stocks_cert_h."' >";

    $sql = "INSERT INTO dbo.tbl_shareholder 
        VALUES('$first_name',
        '$middle_name',
        '$last_name',
        '$aff_comp_h',
        '$int_ext_h',
        '$held_position_h',
        '$shares_owned_h',
        '$type_of_shares_h',
        '$stocks_cert_h',
        '$remarks_h',
        '$datetime',
        '0')";
    $stmt = sqlsrv_query($db, $sql);
    if ($stmt) {
        $sql1 = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), first_name) ASC";
        $stmt1 = sqlsrv_query($db, $sql1);
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            echo "<option>" . $row['first_name'] . ' ' . $row['last_name'] . "</option>";
        }
    }
}
