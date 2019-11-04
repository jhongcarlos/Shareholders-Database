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
            $companies = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $companies[] = $row['company_affiliation'];
                ?>
                <br>
            <?= "<h3>" . $row['first_name'] . ' ' . $row['last_name'] . "</h3>";
                } ?>
            <div class="row">
                <div class="col-md-12 col-xl-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Company Affiliation</div>
                        <div class="panel-body">
                            <table class="table table-hover" id="tbl_ind">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Type</th>
                                        <th>Type of Shares</th>
                                        <th>Shares Owned</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($companies as $k => $v) {
                                            $sql = "SELECT * FROM dbo.tbl_company WHERE CONVERT(VARCHAR(MAX), company_name) LIKE '%$v%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                                            $stmt = sqlsrv_query($db, $sql);
                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                $arr = explode(",", $row['ID']);
                                                $arr1 = explode(",", $row['type_of_share']);
                                                $arr2 = explode("|", $row['shares_owned']);
                                                $arr3 = explode(",", $row['company_affiliation']);
                                                $position = "";
                                                foreach ($arr3 as $key => $value) {
                                                    $position = $key;
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= $row['company_name'] ?></td>
                                                    <td>Company</td>
                                                    <td><?= $arr1[$position] ?></td>
                                                    <td><?= $arr2[$position] ?></td>
                                                    <td><a href='view_company.php?comp_name=<?= $row['company_name'] ?>' class="btn btn-warning"><i class="fa fa-eye"></i></a></td>
                                                </tr>
                                    <?php
                                            }
                                        }
                                }
                                // End of company
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            ?>
    </div>
    <script>
        $(document).ready(function() {
            $('#tbl_ind').DataTable({
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