<?php
include('server.php');
if (empty($_SESSION['mpic_mpic_name'])) {
    header('Location:login');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include('partial/header.php'); ?>
    <title>View Corporation - Metro Pacific Investment Corporation</title>
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
            // $id = $_SESSION['view_id_corp'];
            $corp_name = "$_GET[corp_name]";
            $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(VARCHAR(MAX), company_affiliation) LIKE '$corp_name' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
            $stmt = sqlsrv_query($db, $sql);
        }
        ?>
        <br>
        <div class="row">
            <div class="container">
                <?php
                if (empty($_GET)) { } else { ?>
                    <h3><?= $_GET['corp_name'];
                        } ?></h3>
            </div>
            <div class="col-md-12 col-xl-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-xl-6 col-sm-6 col-xs-6">
                                <h5>Company Affiliation</h5>
                            </div>
                            <div class="col-md-6 col-xl-6 col-sm-6 col-xs-6">
                                <form action="pdf" method="POST" target="_blank">
                                    <input type="hidden" name="corp_name" value="<?= $_GET['corp_name'] ?>">
                                    <button class="btn" name="pdf" style="float:right">Generate PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover" id="tbl_corp">
                            <thead>
                                <th>Shareholder Name</th>
                                <th>Type</th>
                                <th>Type of Share</th>
                                <th>Shares Owned</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($_GET)) { } else {
                                    $companies = array();
                                    $sql1 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(VARCHAR(MAX), corporation_name) LIKE '%$corp_name%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                                    $stmt1 = sqlsrv_query($db, $sql1);
                                    $share = "";
                                    $typeofshare = "";
                                    while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {

                                        $arr3 = explode("|", $row['company_affiliation']);
                                        $position = 0;
                                        foreach ($arr3 as $key => $value) {
                                            if ($value == $_GET['corp_name']) {
                                                $position = $key;
                                                // echo $key;
                                            }
                                            $companies[] = $value;
                                        }
                                        $share = $row['shares_owned'];
                                        $typeofshare = $row['type_of_share'];
                                    }
                                    foreach ($companies as $k => $v) {
                                        $num = 0;
                                        $corp_name = "$_GET[corp_name]";
                                        $sql = "SELECT * FROM dbo.tbl_company WHERE CONVERT(VARCHAR(MAX), company_name) LIKE '%$v%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                                        $stmt = sqlsrv_query($db, $sql);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            $arr = explode(",", $row['ID']);
                                            $arr1 = explode(",", $row['type_of_share']);
                                            $arr2 = explode("|", $row['shares_owned']);
                                            $arr3 = explode("|", $row['company_affiliation']);
                                            $arr4 = explode("|", $share);
                                            $arr5 = explode(",", $typeofshare);
                                            $position = "";
                                            foreach ($arr3 as $key => $value) {
                                                $position = $key;
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $row['company_name'] ?></td>
                                                <td>Company</td>
                                                <td><?= $arr5[$k] ?></td>
                                                <td><?= $arr4[$k] ?></td>
                                                <td><a href='view_company.php?comp_name=<?= $row['company_name'] ?>' class="btn btn-warning"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                <?php
                                        }
                                        $num += 1;
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#tbl_corp').DataTable({
                // dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ]
            });
        });
    </script>
    <?php include('partial/index_footer.php'); ?>
</body>

</html>