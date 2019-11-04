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
    <title>View Company - Metro Pacific Investment Corporation</title>
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

            $id = $_SESSION['view_id_comp'];
            $comp_name = "$_GET[comp_name]";
            $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(VARCHAR(MAX), company_affiliation) LIKE '%$comp_name%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
            $stmt = sqlsrv_query($db, $sql);
        }
        ?>
        <br>
        <div class="row">
            <div class="container">
                <?php
                if (empty($_GET)) { } else { ?>
                    <h3><?= $_GET['comp_name'];
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
                                    <input type="hidden" name="comp_name" value="<?= $_GET['comp_name'] ?>">
                                    <button class="btn" name="pdf_comp" style="float:right">Generate PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover" id="tbl_comp">
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
                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                        $arr = explode(",", $row['ID']);
                                        $arr1 = explode(",", $row['type_of_shares']);
                                        $arr2 = explode("|", $row['shares_owned']);
                                        $arr3 = explode(",", $row['company_affiliation']);
                                        $position = "";
                                        foreach ($arr3 as $key => $value) {
                                            if ($value == $_GET['comp_name']) {
                                                $position = $key;
                                            }
                                        }


                                        // for ($i = 0; $i < count($arr); $i++) {
                                        ?>
                                        <tr>
                                            <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                            <td>Individual</td>
                                            <td><?= $arr1[$position] ?></td>
                                            <td><?= $arr2[$position] ?></td>
                                            <td><a href="view_shareholder.php?sh_id=<?= $row['ID'] ?>" class="btn btn-warning"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    <?php
                                        }
                                        ?>
                                    <?php
                                        $sql1 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(VARCHAR(MAX), company_affiliation) LIKE '%$comp_name%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                                        $stmt1 = sqlsrv_query($db, $sql1);
                                        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                                            $arr = explode(",", $row['ID']);
                                            $arr1 = explode(",", $row['type_of_share']);
                                            $arr2 = explode("|", $row['shares_owned']);
                                            $arr3 = explode(",", $row['company_affiliation']);
                                            $position = "";
                                            foreach ($arr3 as $key => $value) {
                                                if ($value == $_GET['comp_name']) {
                                                    $position = $key;
                                                }
                                            } ?>
                                        <tr>
                                            <td><?= $row['corporation_name'] ?></td>
                                            <td>Corporation</td>
                                            <td><?= $arr1[$position] ?></td>
                                            <td><?= $arr2[$position] ?></td>
                                            <td><a href='view_corporation.php?corp_name=<?= $row['corporation_name'] ?>' class="btn btn-warning"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    <?php
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
            $('#tbl_comp').DataTable({
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