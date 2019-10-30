<?php
include('server.php');
if (empty($_SESSION['mpic_mpic_name'])) {
    header('Location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include('partial/header.php'); ?>
    <title>View Shareholder - Metro Pacific Investment Corporation</title>
    <style>
        .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            height: 80px;
            width: 180px;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="images/MPIC_logo.png" alt="" class="img-responsive logo">
        <hr>
    </div>
    <div class="container" style="margin-top: 10px;">
        <div class="col-sm-12 form-legend">
            <a href="index">← Home</a><br>
            <a href="#" onclick="window.history.back()">← Go back</a>
        </div>
        <?php
        if (empty($_GET)) { } else {
            // view_id_corp
            $id = $_GET['sh_id'];
            $sql = "SELECT * FROM dbo.tbl_shareholder WHERE ID = '$id'";
            $stmt = sqlsrv_query($db, $sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
                <br>
                <?= "<h3>" . $row['first_name'] . ' ' . $row['last_name'] . "</h3>" ?>
                <div class="row">
                    <div class="col-md-12 col-xl-12 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Company Affiliation</div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Type of Shares</th>
                                            <th>Shares Owned</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                $arr = explode(",", $row['company_affiliation']);
                                                $arr1 = explode(",", $row['type_of_shares']);
                                                $arr2 = explode("|", $row['shares_owned']);
                                                // for($i = 0; $i < count($arr); $i++) {
                                                //     echo $arr[$i];
                                                // }
                                                if (!empty($arr)) {

                                                    for ($i = 0; $i < count($arr); $i++) {
                                                        $comp = $arr[$i];
                                                        $sql1 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(VARCHAR(MAX), corporation_name) LIKE '$comp'";
                                                        $stmt1 = sqlsrv_query($db, $sql1);
                                                        while ($r = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                                                            ?>
                                                    <tr>
                                                        <td><?= $arr[$i] ?></td>
                                                        <td><?= $arr1[$i]; ?></td>
                                                        <td><?= $arr2[$i]  ?></td>
                                                        <td><a href='view_corporation.php?corp_name=<?= $arr[$i] ?>' class="btn btn-warning"><i class="fa fa-eye"></i></a></td>
                                                    </tr>
                                        <?php       }
                                                    }
                                                    // Company
                                                    for ($i = 0; $i < count($arr); $i++) {
                                                        $comp = $arr[$i];
                                                        $sql1 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(VARCHAR(MAX), company_name) LIKE '$comp'";
                                                        $stmt1 = sqlsrv_query($db, $sql1);
                                                        while ($r = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                                                            ?>
                                                    <tr>
                                                        <td><?= $arr[$i] ?></td>
                                                        <td><?= $arr1[$i]; ?></td>
                                                        <td><?= $arr2[$i]  ?></td>
                                                        <td><a href='view_company.php?comp_name=<?= $arr[$i] ?>' class="btn btn-warning"><i class="fa fa-eye"></i></a></td>
                                                    </tr>
                                        <?php       }
                                                    }
                                                    // End of company
                                                } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
</body>

</html>