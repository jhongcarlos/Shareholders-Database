<?php
session_start();
date_default_timezone_set('Asia/Manila');
$datetime = date('m/d/Y h:i a', time());
// Connect to MSSQL DB
$conn_info = array("Database" => "test_db");
$db = sqlsrv_connect('MPIC-BACKUP-02', $conn_info);
// if($db){
//     echo "Connected";
// }
// else{
//     echo "No";
//     die(print_r(sqlsrv_errors(),true));
// }

// Insert Data =====
// $sql = "INSERT INTO dbo.users_login VALUES('1','John Harold','Carlos','jhcarlos','admin','carlosjohnharold@outlook.com','admin','10-17-2019')";
// $stmt = sqlsrv_query( $db, $sql);
// if( $stmt === false ) {
//     die( print_r( sqlsrv_errors(), true));
// }

// Select Data =====
// $sql = "SELECT * FROM dbo.test_tbl";
// $stmt = sqlsrv_query($db, $sql);

// while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
//     echo $row['column1'].", ".$row['column2']."<br />";
// }

// Delete Data
// $query = "DELETE FROM dbo.users_login WHERE id='0'";
// $stmt = sqlsrv_query($db, $query);

// Update Data
// $query = "UPDATE dbo.test_tbl SET column1 = 'Value' WHERE id = 'some id'";
// $stmt = sqlsrv_query($db, $query);

if (isset($_POST['btn_login'])) {
    $error_message = "";
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM dbo.users_login WHERE email LIKE '$email' AND password LIKE '$password'";
    $stmt = sqlsrv_query($db, $sql, array(), array("Scrollable" => "static"));

    if (sqlsrv_num_rows($stmt) == 1) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $_SESSION['mpic_mpic_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $_SESSION['mpic_mpic_role'] = $row['role'];
            $_SESSION['mpic_mpic_id'] = $row['ID'];
        }
        header("Location: index");
    } else {
        $error_message = "Invalid email or password";
    }
}
if (isset($_POST['test_submit'])) {
    foreach ($_POST['test'] as $key => $value) {
        echo "<script>alert('" . $value . "')</script>";
    }
}
if (isset($_POST['sh_submit'])) {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];

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
        echo "<script>alert('Success!')</script>";
        $user_name = $_SESSION['mpic_mpic_name'];
        $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Added Individual - $first_name $last_name','$datetime')");
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
        echo "<script>alert('Success!')</script>";
        $user_name = $_SESSION['mpic_mpic_name'];
        $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Added Individual - $first_name $last_name','$datetime')");
    }
}
if (isset($_POST['corp_submit'])) {

    $c_name = $_POST['c_name'];
    $sec_num = $_POST['sec_num'];
    $tin_num = $_POST['tin_num'];
    $address = $_POST['address'];

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
            echo "<script>alert('Sucess')</script>";
            $user_name = $_SESSION['mpic_mpic_name'];
            $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Added Coporation - $c_name','$datetime')");
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
            echo "<script>alert('Sucess')</script>";
            $user_name = $_SESSION['mpic_mpic_name'];
            $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Added Corporation - $c_name','$datetime')");
        }
    }
}
if (isset($_POST['c_delete'])) {
    $id = $_POST['id'];
    $query = "UPDATE dbo.tbl_corporation SET is_deleted = '1' WHERE ID = '$id'";
    $stmt = sqlsrv_query($db, $query);
    if ($stmt) {
        echo "<script>alert('Deleted')</script>";
        $user_name = $_SESSION['mpic_mpic_name'];
            $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Deleted Corporation - $id','$datetime')");
    }
}
if (isset($_POST['sh_delete'])) {
    $id = $_POST['id'];
    $query = "UPDATE dbo.tbl_shareholder SET is_deleted = '1' WHERE ID = '$id'";
    $stmt = sqlsrv_query($db, $query);
    if ($stmt) {
        echo "<script>alert('Deleted')</script>";
        $user_name = $_SESSION['mpic_mpic_name'];
            $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Deleted Individual - $id','$datetime')");
    }
}
if (isset($_POST['c_view'])) {
    $_SESSION['view_id_corp'] = $_POST['id'];
    $_SESSION['corp_name'] = $_POST['corp_name'];
    header('Location:view_corporation?corp_name=' . $_POST['corp_name']);
}
if (isset($_POST['sh_view'])) {
    // $_SESSION['view_id_sh'] = $_POST['id'];
    header('Location:view_shareholder?sh_id=' . $_POST['id']);
}
if (isset($_POST['sh_edit'])) {
    $_SESSION['edit_id_sh'] = $_POST['id'];
    header('Location:edit_individual');
}
$msg = "";
if (isset($_POST['edit_individual_submit'])) {

    // constants
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];

    $aff_comp = $_POST['aff_comp'];
    $internal_external = $_POST['internal_external'];
    $held_position = $_POST['held_position'];
    $shares_owned = $_POST['shares_owned'];
    $type_of_shares = $_POST['type_of_shares'];

    // $stock_cert = $_FILES['stocks_certificate']['name'];
    // $stock_cert_tmp = $_FILES['stocks_certificate']['tmp_name'];
    $remarks = $_POST['remarks'];
    // handlers
    $aff_comp_h = ""; # x
    $internal_external_h = ""; # x
    $held_position_h = ""; # x
    $shares_owned_h = ""; # x
    $type_of_shares_h = ""; # x
    // $stocks_cert_h = ""; # x
    $remarks_h = ""; # x

    // foreach ($stock_cert as $key => $value) {
    //     $target = "images/" . basename($value);
    //     if ($stocks_cert_h) $stocks_cert_h .= ',';
    //     $stocks_cert_h .= $value;
    //     foreach ($stock_cert_tmp as $k => $v) {
    //         if (move_uploaded_file($v, $target)) {
    //             $msg = "Success";
    //         }
    //     }
    // }
    foreach ($aff_comp as $key => $value) {
        if ($aff_comp_h) $aff_comp_h .= ',';
        $aff_comp_h .= $value;
    }
    foreach ($internal_external as $key => $value) {
        if ($internal_external_h) $internal_external_h .= ',';
        $internal_external_h .= $value;
    }
    foreach ($held_position as $key => $value) {
        if ($held_position_h) $held_position_h .= ',';
        $held_position_h .= $value;
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
    $query = "UPDATE dbo.tbl_shareholder 
    SET 
    first_name = '$first_name',
    middle_name = '$middle_name',
    last_name = '$last_name',
    company_affiliation = '$aff_comp_h',
    internal_external = '$internal_external_h',
    held_position = '$held_position_h',
    shares_owned = '$shares_owned_h',
    type_of_shares = '$type_of_shares_h',
    remarks = '$remarks_h',
    last_update = '$datetime' 
    WHERE ID = $id";
    $stmt = sqlsrv_query($db, $query);
    if ($stmt) {
        $msg = "
        <div class='alert alert-success'>
         Data has been updated
        </div>
        ";
        $user_name = $_SESSION['mpic_mpic_name'];
        $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Edited Individual - $first_name $last_name','$datetime')");
    }
}
if (isset($_POST['c_edit'])) {
    $_SESSION['edit_id_c'] = $_POST['id'];
    header('Location:edit_corporation');
}
if (isset($_POST['edit_corporation_submit'])) {

    // constants
    $id = $_POST['id'];
    $corp_name = $_POST['corp_name'];
    $sec_num = $_POST['sec_num'];
    $tin_num = $_POST['tin_num'];
    $address = $_POST['address'];

    // array variables
    $dir_off = $_POST['dir_off'];
    $do_position = $_POST['do_position'];
    $aff_comp = $_POST['aff_comp'];
    $type_of_shares = $_POST['type_of_shares'];
    $shares_owned = $_POST['shares_owned'];
    $remarks = $_POST['remarks'];

    // handlers
    $dir_off_h = "";
    $do_position_h = "";
    $aff_comp_h = "";
    $type_of_shares_h = "";
    $shares_owned_h = "";
    $remarks_h = "";

    foreach ($dir_off as $key => $value) {
        if ($dir_off_h) $dir_off_h .= ',';
        $dir_off_h .= $value;
    }
    foreach ($do_position as $key => $value) {
        if ($do_position_h) $do_position_h .= ',';
        $do_position_h .= $value;
    }
    foreach ($aff_comp as $key => $value) {
        if ($aff_comp_h) $aff_comp_h .= ',';
        $aff_comp_h .= $value;
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
    $query = "UPDATE dbo.tbl_corporation 
    SET 
    corporation_name = '$corp_name',
    sec_num = '$sec_num',
    tin_num = '$tin_num',
    address = '$address',
    company_affiliation = '$aff_comp_h',
    director_officer = '$dir_off_h',
    do_position = '$do_position_h',
    shares_owned = '$shares_owned_h',
    type_of_share = '$type_of_shares_h',
    remarks = '$remarks_h',
    last_update = '$datetime'
    WHERE ID = $id";
    $stmt = sqlsrv_query($db, $query);
    if ($stmt) {
        $msg = "
        <div class='alert alert-success'>
         Data has been updated
        </div>
        ";
        $user_name = $_SESSION['mpic_mpic_name'];
        $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Edited Corporation - $corp_name','$datetime')");
    }
}
if (isset($_POST['btn_register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $will_handle = $_POST['will_handle'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM dbo.users_login WHERE CONVERT(NVARCHAR(MAX), email) = '$email'";
    $stmt = sqlsrv_query($db, $sql);

    $email_exist = "";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        if ($row['email'] == $email) {
            $email_exist = "1";
        }
    }
    if ($email_exist == "1") {
        $error_message = "
                <div class='alert alert-danger'>
                    Email already exist
                </div>
                ";
    } else {
        $sql1 = "INSERT INTO dbo.users_login 
            VALUES('$first_name',
            '$last_name',
            '$middle_name',
            '$role',
            '$email',
            '$password',
            '$will_handle',
            '$datetime')";

        $stmt1 = sqlsrv_query($db, $sql1);
        if ($stmt1) {
            $error_message = "
                <div class='alert alert-success'>
                    Account Registered
                </div>
                ";
        }
    }
}
if (isset($_POST['btn_forgot_password_code'])) {
    $fp_code = $_POST['fp_code'];
    $code_given = $_SESSION['forgot_password_code'];

    if ($fp_code == $code_given) {
        header('forgot_password_reset');
    } else {
        $wrong_code = "Incorrect Code";
    }
}
if (isset($_POST['btn_forgot_password_reset'])) {
    $new_password = md5($_POST['new_password']);
    $email = $_SESSION['forgot_password_email'];

    $sql = "UPDATE dbo.users_login 
    SET password = '$new_password' 
    WHERE CONVERT(NVARCHAR(MAX), email) = '$email'";
    $stmt = sqlsrv_query($db, $sql);

    if ($stmt) {
        echo "<script>alert('Your password has been reset')</script>";
        header('Location:login');
    }
}
if (isset($_POST['comp_submit'])) {

    $c_name = $_POST['co_name'];
    $sec_num = $_POST['sec_num'];
    $tin_num = $_POST['tin_num'];
    $category = $_POST['category'];
    $int_ext = $_POST['int_ext'];
    $parent_id = $_POST['parent_id'];
    $address = $_POST['address'];
    $total_num_shares = $_POST['total_num_shares'];

    if (empty($_POST['aff_comp'])) {
        // Query here
        $sql = "INSERT INTO dbo.tbl_company(company_name,
        sec_num,tin_num,total_shares,category,internal_external,[address],last_update,parent_id,is_deleted)
                VALUES('$c_name',
                '$sec_num',
                '$tin_num',
                '$total_num_shares',
                '$category',
                '$int_ext',
                '$address',
                '$datetime',
                '$parent_id',
                '0')";
        $stmt = sqlsrv_query($db, $sql);
        if ($stmt) {
            echo "<script>alert('Company Added')</script>";
            $user_name = $_SESSION['mpic_mpic_name'];
            $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Added Company - $c_name','$datetime')");
        }
    } else {

        $dir_off = $_POST['dir_off'];
        $do_position = $_POST['do_position'];

        $aff_comp = $_POST['aff_comp'];
        $type_of_shares = $_POST['type_of_shares'];
        $shares_owned = $_POST['shares_owned'];

        $stock_cert = $_FILES['stock_cert']['name'];
        $stock_cert_tmp = $_FILES['stock_cert']['tmp_name'];
        $remarks = $_POST['remarks'];

        // Array to string handlers
        $dir_off_h = ""; // x
        $do_position_h = ""; // x
        $aff_com_h = ""; // x
        $type_of_shares_h = ""; // x
        $shares_owned_h = ""; // x
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
        $sql = "INSERT INTO dbo.tbl_company 
                VALUES('$c_name',
                '$sec_num',
                '$tin_num',
                '$total_num_shares',
                '$category',
                '$int_ext',
                '$address',
                '$aff_com_h',
                '$dir_off_h',
                '$do_position_h',
                '$shares_owned_h',
                '$type_of_shares_h',
                '$stocks_cert_h',
                '$remarks_h',
                '$datetime',
                '$parent_id',
                '0')";
        $stmt = sqlsrv_query($db, $sql);
        if ($stmt) {
            echo "<script>alert('Company Added')</script>";
            $user_name = $_SESSION['mpic_mpic_name'];
            $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Added Company - $c_name','$datetime')");
        }
    }
}
// Company Buttons on index
if (isset($_POST['comp_delete'])) {
    $id = $_POST['id'];
    $query = "UPDATE dbo.tbl_company SET is_deleted = '1' WHERE ID = '$id'";
    $stmt = sqlsrv_query($db, $query);
    if ($stmt) {
        echo "<script>alert('Deleted')</script>";
        $user_name = $_SESSION['mpic_mpic_name'];
            $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Deleted Company - $id','$datetime')");
    }
}
if (isset($_POST['comp_edit'])) {
    $_SESSION['edit_id_comp'] = $_POST['id'];
    header('Location:edit_company');
}
if (isset($_POST['comp_view'])) {
    $_SESSION['view_id_comp'] = $_POST['id'];
    $_SESSION['comp_name'] = $_POST['comp_name'];
    header('Location:view_company?comp_name=' . $_POST['comp_name']);
}
if (isset($_POST['edit_company_submit'])) {
    // constants
    $id = $_POST['id'];
    $comp_name = $_POST['comp_name'];
    $sec_num = $_POST['sec_num'];
    $tin_num = $_POST['tin_num'];
    $address = $_POST['address'];
    $total_shares = $_POST['total_shares'];

    // array variables
    $dir_off = $_POST['dir_off'];
    $do_position = $_POST['do_position'];
    $aff_comp = $_POST['aff_comp'];
    $type_of_shares = $_POST['type_of_shares'];
    $shares_owned = $_POST['shares_owned'];
    $remarks = $_POST['remarks'];

    // handlers
    $dir_off_h = "";
    $do_position_h = "";
    $aff_comp_h = "";
    $type_of_shares_h = "";
    $shares_owned_h = "";
    $remarks_h = "";

    foreach ($dir_off as $key => $value) {
        if ($dir_off_h) $dir_off_h .= ',';
        $dir_off_h .= $value;
    }
    foreach ($do_position as $key => $value) {
        if ($do_position_h) $do_position_h .= ',';
        $do_position_h .= $value;
    }
    foreach ($aff_comp as $key => $value) {
        if ($aff_comp_h) $aff_comp_h .= ',';
        $aff_comp_h .= $value;
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
    $query = "UPDATE dbo.tbl_company 
    SET 
    company_name = '$comp_name',
    sec_num = '$sec_num',
    tin_num = '$tin_num',
    total_shares = '$total_shares',
    address = '$address',
    company_affiliation = '$aff_comp_h',
    director_officer = '$dir_off_h',
    do_position = '$do_position_h',
    shares_owned = '$shares_owned_h',
    type_of_share = '$type_of_shares_h',
    remarks = '$remarks_h',
    last_update = '$datetime'
    WHERE ID = $id";
    $stmt = sqlsrv_query($db, $query);
    if ($stmt) {
        $msg = "
        <div class='alert alert-success'>
         Data has been updated
        </div>
        ";
        $user_name = $_SESSION['mpic_mpic_name'];
        $stmt1 = sqlsrv_query($db, "INSERT INTO dbo.tbl_audit_trail VALUES('$user_name','Edited Company - $c_name','$datetime')");
    }
}
