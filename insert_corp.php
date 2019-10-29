<?php
include('server.php');
$c_name = $_POST['c_name'];
$sec_num = $_POST['sec_num'];
$tin_num = $_POST['tin_num'];
$address = $_POST['address'];

$sql2 = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), first_name) ASC";
$stmt2 = sqlsrv_query($db, $sql2);
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    echo "<option>" . $row['first_name'] . ' ' . $row['last_name'] . "</option>";
}

if (empty($_POST['aff_comp'])) {
    // Query here
    $sql = "INSERT INTO dbo.tbl_corporation(corporation_name,
    sec_num,tin_num,[address],last_update,parent_id,is_deleted)
            VALUES('$c_name',
            '$sec_num',
            '$tin_num',
            '$address',
            '$datetime',
            '1',
            '0')";
    $stmt = sqlsrv_query($db, $sql);
    if ($stmt) {
        // echo "<script>alert('Sucess')</script>";
        $sql1 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
        $stmt1 = sqlsrv_query($db, $sql1);
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            echo "<option>" . $row['corporation_name'] . "</option>";
        }
    }
} else {

    $dir_off = $_POST['dir_off'];
    $do_position = $_POST['do_position'];

    $aff_comp = $_POST['aff_comp'];
    $type_of_shares = $_POST['type_of_shares'];
    $shares_owned = $_POST['shares_owned'];

    $int_ext = $_POST['int_ext'];
    $stock_cert = $_FILES['stock_cert']['name'];
    $stock_cert_tmp = $_FILES['stock_cert']['tmp_name'];
    $remarks = $_POST['remarks'];

    // Array to string handlers
    $dir_off_h = ""; // x
    $do_position_h = ""; // x
    $aff_com_h = ""; // x
    $type_of_shares_h = ""; // x
    $shares_owned_h = ""; // x
    $int_ext_h = "";
    $stocks_cert_h = ""; //foreach($dir_off as $key => $value) {
    //     if ($dir_off_h) $dir_off_h .= ',';
    //     $dir_off_h .= $value;
    $remarks_h = ""; // x

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
    foreach ($dir_off as $key => $value) {
        if ($dir_off_h) $dir_off_h .= ',';
        $dir_off_h .= $value;
    }
    foreach ($int_ext as $key => $value) {
        if ($int_ext_h) $int_ext_h .= ',';
        $int_ext_h .= $value;
    }
    foreach ($do_position as $key => $value) {
        if ($do_position_h) $do_position_h .= ',';
        $do_position_h .= $value;
    }
    foreach ($aff_comp as $key => $value) {
        if ($aff_com_h) $aff_com_h .= ',';
        $aff_com_h .= $value;
    }
    foreach ($type_of_shares as $key => $value) {
        if ($type_of_shares_h) $type_of_shares_h .= ',';
        $type_of_shares_h .= $value;
    }
    foreach ($shares_owned as $key => $value) {
        if ($shares_owned_h) $shares_owned_h .= '|';
        $shares_owned_h .= $value;
    }
    foreach ($remarks as $key => $value) {
        if ($remarks_h) $remarks_h .= ',';
        $remarks_h .= $value;
    }
    // echo "<script>alert('".$reg_type_h."')</script>";
    // echo "<script>alert('".$reg_num_h."')</script>";
    // echo "<script>alert('".$aff_comp_h."')</script>";
    // echo "<script>alert('".$held_position_h."')</script>";
    // echo "<script>alert('".$stocks_owned_h."')</script>";
    $arr = explode(",", $stocks_cert_h);
    $last = $arr[0];
    // echo "<img src='images/".$stocks_cert_h."' >";

    // Query here
    $sql = "INSERT INTO dbo.tbl_corporation 
            VALUES('$c_name',
            '$sec_num',
            '$tin_num',
            '$address',
            '$aff_com_h',
            '$dir_off_h',
            '$do_position_h',
            '$shares_owned_h',
            '$type_of_shares_h',
            '$int_ext_h',
            '$stocks_cert_h',
            '$remarks_h',
            '$datetime',
            '1',
            '0')";
    $stmt = sqlsrv_query($db, $sql);
    if ($stmt) {
        // echo "<script>alert('Sucess')</script>";
        $sql1 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
        $stmt1 = sqlsrv_query($db, $sql1);
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            echo "<option>" . $row['corporation_name'] . "</option>";
        }
    }
}
