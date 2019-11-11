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

    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="images/MPIC_logo.png" alt="" class="img-responsive logo">
            </div>

            <ul class="list-unstyled components">
                <p>Hello, <?= $_SESSION['mpic_mpic_name']; ?></p>
                <li>
                    <a href="home">Home</a>
                </li>
                <li>
                    <a href="add_company"><i class="fa fa-plus"> Add Company</i></a>
                </li>
                <li>
                    <a href="add_corporation"><i class="fa fa-plus"> Add Corporation</i></a>
                </li>
                <li>
                    <a href="add_individual"><i class="fa fa-plus"> Add Individual</i></a>
                </li>
                <li>
                    <a href="user_registration"><i class="fa fa-users"> Register Users</i></a>
                </li>
                <li>
                    <a href="audit_trail"><i class="fa fa-list"> Audit Trail</i></a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false"><i class="fa fa-area-chart"></i> Chart</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li><a href="#"><i class="fa fa-bolt"></i> Power</a></li>
                        <li><a href="#"><i class="fa fa-tint"></i> Water</a></li>
                        <li><a href="#"><i class="fa fa-train"></i> Rail</a></li>
                        <li><a target="blank" href="partial/test_chart?cat=Tollways"><i class="fa fa-road"></i> Tollways</a></li>
                        <li><a target="blank" href="partial/test_chart?cat=Logistics"><i class="fa fa-truck"></i> Logistics</a></li>
                        <li><a href="#"><i class="fa fa-hospital-o"></i> Hospital</a></li>
                        <li><a href="#">Others</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li><a href="login" class="btn btn-danger">Log out</a></li>
            </ul>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <button type="button" id="sidebarCollapse" class="btn btn-primary navbar-btn">
                <i class="glyphicon glyphicon-align-left"></i>
                <span>Toggle Sidebar</span>
            </button>

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
                                    <h5 style="float:right"><a href="add_company" class="btn btn-success"><i class="fa fa-plus"> Add Company</i></a></h5>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table id="tbl_company" class="table table-responsive table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Company Name</th>
                                        <th>Registration</th>
                                        <th>Total Shares</th>
                                        <th>Remarks</th>
                                        <th>Last Update</th>
                                        <th width="15%">Action</th>
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
                                            <td><?= str_replace(',', '<br />', $row['remarks']) ?></td>
                                            <td><?= $row['last_update'] ?></td>
                                            <td>
                                                <form method="POST">
                                                    <input type="hidden" name="id" value="<?= $row['ID']; ?>">
                                                    <input type="hidden" name="comp_name" value="<?= $row['company_name']; ?>">
                                                    <button class="btn btn-primary" name="comp_view" title="View"><i class="fa fa-eye"></i></button>
                                                    <button class="btn btn-warning" name="comp_edit" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <button class="btn btn-danger" name="comp_delete" title="Delete"><i class="fa fa-trash"></i></button>
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
                                    <h5 style="float:right"><a href="add_corporation" class="btn btn-success"><i class="fa fa-plus"> Add Corporation</i></a></h5>
                                </div>
                            </div>
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
                                        <th>Remarks</th>
                                        <th>Last Update</th>
                                        <th width="15%">Action</th>
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
                                            <td><?= str_replace(',', '<br />', $row['remarks']) ?></td>
                                            <td><?= $row['last_update'] ?></td>
                                            <td>
                                                <form method="POST">
                                                    <input type="hidden" name="id" value="<?= $row['ID']; ?>">
                                                    <input type="hidden" name="corp_name" value="<?= $row['corporation_name']; ?>">
                                                    <button class="btn btn-primary" name="c_view" title="View"><i class="fa fa-eye"></i></button>
                                                    <button class="btn btn-warning" name="c_edit" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <button class="btn btn-danger" name="c_delete" title="Delete"><i class="fa fa-trash"></i></button>
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
                                    <h5 style="float:right"><a href="add_individual" class="btn btn-success"><i class="fa fa-plus"> Add Individual</i></a></h5>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table id="tbl_shareholder" style="width:100%" class="table table-responsive table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Internal / External</th>
                                        <th>Held Position</th>
                                        <th>Shares Owned</th>
                                        <th>Type of Shares</th>
                                        <th>Remarks</th>
                                        <th>Last update</th>
                                        <th width="15%">Action</th>
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
                                            <td><?= str_replace(',', '<br />', $row['internal_external']) ?></td>
                                            <td><?= str_replace(',', '<br />', $row['held_position']) ?></td>
                                            <td><?= str_replace('|', '<br />', $row['shares_owned']) ?></td>
                                            <td><?= str_replace(',', '<br />', $row['type_of_shares']) ?></td>
                                            <td><?= str_replace(',', '<br />', $row['remarks']) ?></td>
                                            <td><?= $row['last_update'] ?></td>
                                            <td>
                                                <form method="POST">
                                                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                                    <button class="btn btn-primary" name="sh_view" title="View"><i class="fa fa-eye"></i></button>
                                                    <button class="btn btn-warning" name="sh_edit" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <button class="btn btn-danger" name="sh_delete" title="Delete"><i class="fa fa-trash"></i></button>
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