<?php
include('server.php');
if (empty($_SESSION['mpic_mpic_name'])) {
    header('Location:login');
}
// get the percentage

// $num1 = str_replace(',', '', "1,000,000");
// $num2 = str_replace(',', '', "191,152");
// $percent = $num2 / $num1;
// echo $percent_f = number_format($percent * 100,2) . '%';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - Metro Pacific Investment Corporation</title>
    <?php include('partial/header.php'); ?>
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            height: 70px;
            width: 180px;
            /* margin-top: px; */
        }
    </style>
</head>

<body>

    <?php include("partial/sidebar.php"); ?>
    <!-- <div class="container-fluid"><br> -->
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#company">Company</a></li>
        <li><a data-toggle="tab" href="#corporation">Corporation</a></li>
        <li><a data-toggle="tab" href="#shareholder">Individual</a></li>
    </ul>
    <div class="tab-content">

        <div id="company" class="tab-pane fade in active">
            <!-- Content -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Company Information</h3>
                        </div>
                        <div class="col-md-6">
                            <?php if ($_SESSION['mpic_mpic_role'] == "Viewer" || $_SESSION['mpic_mpic_role'] == "Site Admin") { } else { ?>
                                <h5 style="float:right"><a href="add_company" class="btn btn-primary"><i class="fa fa-plus"> Add Company</i></a></h5>
                            <?php } ?>
                        </div>
                    </div>
                    <?= $comp_del_res ?>
                </div>
                <div class="panel-body">
                    <table id="tbl_company" class="table table-responsive table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Registration</th>
                                <th>Total Shares</th>
                                <th>Last Update</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
                            $stmt = sqlsrv_query($db, $sql);

                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $row['ID'] ?></td>
                                    <td><?= $row['company_name'] ?></td>
                                    <td><span><b>SEC Number: </b></span><?= $row['sec_num'] ?><br><span><b>TIN Number: </b></span><?= $row['tin_num'] ?></td>
                                    <td><?= str_replace('|', '<br />', $row['total_shares']) ?></td>
                                    <td><?= $row['last_update'] ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?= $row['ID']; ?>">
                                            <input type="hidden" name="comp_name" value="<?= $row['company_name']; ?>">
                                            <button class="btn btn-primary" name="comp_view" title="View"><i class="fa fa-eye"></i></button>
                                            <?php
                                                if ($_SESSION['mpic_mpic_role'] == "Viewer") { } elseif ($_SESSION['mpic_mpic_company'] == $row['company_name'] && $_SESSION['mpic_mpic_role'] == "Site Admin") {
                                                    ?>
                                                <button class="btn btn-warning" name="comp_edit" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger" onclick="return confirmation()" name="comp_delete" title="Delete"><i class="fa fa-trash"></i></button>
                                            <?php } elseif ($_SESSION['mpic_mpic_role'] == "Super User" || $_SESSION['mpic_mpic_role'] == "Administrator") {
                                                    ?>
                                                <button class="btn btn-warning" name="comp_edit" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger" onclick="return confirmation()" name="comp_delete" title="Delete"><i class="fa fa-trash"></i></button>
                                            <?php } ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="corporation" class="tab-pane fade">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Corporation Information</h3>
                        </div>
                        <div class="col-md-6">
                            <?php if ($_SESSION['mpic_mpic_role'] == "Viewer" || $_SESSION['mpic_mpic_role'] == "Site Admin") { } else { ?>
                                <h5 style="float:right"><a href="add_corporation" class="btn btn-primary"><i class="fa fa-plus"> Add Corporation</i></a></h5>
                            <?php } ?>
                        </div>
                    </div>
                    <?= $corp_del_res; ?>
                </div>
                <div class="panel-body">
                    <table id="tbl_corp" style="width:100%" class="table table-responsive table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Corporation Name</th>
                                <th>Registration</th>
                                <th>Type of Shares</th>
                                <th>Shares Owned</th>
                                <th>Last Update</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
                            $stmt = sqlsrv_query($db, $sql);

                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $row['ID'] ?></td>
                                    <td><?= $row['corporation_name'] ?></td>
                                    <td><span><b>SEC Number: </b></span><?= $row['sec_num'] ?><br><span><b>TIN Number: </b></span><?= $row['tin_num'] ?></td>
                                    <td><?= str_replace(',', '<br />', $row['type_of_share']) ?></td>
                                    <td class='sss'><?= str_replace('|', '<br>', $row['shares_owned']) ?></td>
                                    <td><?= $row['last_update'] ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?= $row['ID']; ?>">
                                            <input type="hidden" name="corp_name" value="<?= $row['corporation_name']; ?>">
                                            <button class="btn btn-primary" name="c_view" title="View"><i class="fa fa-eye"></i></button>
                                            <?php
                                                if ($_SESSION['mpic_mpic_role'] == "Viewer" || $_SESSION['mpic_mpic_role'] == "Site Admin") { } else {
                                                    ?>
                                                <button class="btn btn-warning" name="c_edit" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger" onclick="return confirmation()" name="c_delete" title="Delete"><i class="fa fa-trash"></i></button>
                                            <?php } ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="shareholder" class="tab-pane fade">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Individual Information</h3>
                        </div>
                        <div class="col-md-6">
                            <?php if ($_SESSION['mpic_mpic_role'] == "Viewer" || $_SESSION['mpic_mpic_role'] == "Site Admin") { } else { ?>
                                <h5 style="float:right"><a href="add_individual" class="btn btn-primary"><i class="fa fa-plus"> Add Individual</i></a></h5>
                            <?php } ?>
                        </div>
                    </div>
                    <?= $ind_del_res; ?>
                </div>
                <div class="panel-body">
                    <table id="tbl_shareholder" style="width:100%" class="table table-responsive table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Held Position</th>
                                <th>Shares Owned</th>
                                <th>Type of Shares</th>
                                <th>Last update</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
                            $stmt = sqlsrv_query($db, $sql);

                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $row['ID'] ?></td>
                                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                    <td><?= str_replace(',', '<br />', $row['held_position']) ?></td>
                                    <td><?= str_replace('|', '<br />', $row['shares_owned']) ?></td>
                                    <td><?= str_replace(',', '<br />', $row['type_of_shares']) ?></td>
                                    <td><?= $row['last_update'] ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                            <input type="hidden" name="ind_name" value="<?= $row['first_name'] . ' ' . $row['last_name'] ?>">
                                            <button class="btn btn-primary" name="sh_view" title="View"><i class="fa fa-eye"></i></button>
                                            <?php
                                                if ($_SESSION['mpic_mpic_role'] == "Viewer" || $_SESSION['mpic_mpic_role'] == "Site Admin") { } else {
                                                    ?>
                                                <button class="btn btn-warning" name="sh_edit" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger" onclick="return confirmation()" name="sh_delete" title="Delete"><i class="fa fa-trash"></i></button>
                                            <?php } ?>

                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <?php include('partial/index_footer.php'); ?>
    <script>
        function confirmation() {
            if (!confirm("Are you sure you want to delete?")) {
                return false;
            }
        }
        $(document).ready(function() {
            $(".sorting").removeAttr("style");
            $("#sidebarCollapse").on("click", function() {
                $("#sidebar").toggleClass("active");
                $(this).toggleClass("active");
            });
            $("#close").on("click", function() {
                $("#sidebar").addClass("active");
            });
        });
        $('#tbl_company').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ]
        });
        $('#tbl_shareholder').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ]
        });
        $('#tbl_corp').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ]
        });
    </script>
</body>

</html>