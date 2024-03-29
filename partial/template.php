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
    <title>Document</title>
    <?php include('partial/header.php'); ?>
    <link rel="stylesheet" href="css/sidebar.css">
    
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Bootstrap Sidebar</h3>
                <a href="#" id="close">Close</a>
            </div>

            <ul class="list-unstyled components">
                <p>Dummy Heading</p>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Home</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li><a href="#">Home 1</a></li>
                        <li><a href="#">Home 2</a></li>
                        <li><a href="#">Home 3</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">About</a>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">Pages</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Portfolio</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li><a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to the article</a></li>
            </ul>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-default">
                <div class="container-fluid">

                    <div class="navbar-header">
                        <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                            <i class="glyphicon glyphicon-align-left"></i>
                            <span>Toggle Sidebar</span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#">Page</a></li>
                            <li><a href="#">Page</a></li>
                            <li><a href="#">Page</a></li>
                            <li><a href="#">Page</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container">
        <img src="images/MPIC_logo.png" alt="" class="img-responsive logo">
    </div>
    <div class="container-fluid"><br>
        <p>Hello, <?= $_SESSION['mpic_mpic_name']; ?></p>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#company">Company</a></li>
            <li><a data-toggle="tab" href="#corporation">Corporation</a></li>
            <li><a data-toggle="tab" href="#shareholder">Individual</a></li>
            <li style="float:right"><a href="audit_trail" class="btn"><i class="fa fa-list"> Audit Trail</i></a></li>
        </ul>
        <div class="tab-content">
            <div id="company" class="tab-pane fade in active">
                <!-- Content -->
                <div class="row">
                    <div class="col-md-6">
                        <h3>Company Information</h3>
                    </div>
                    <div class="col-md-6">
                        <h3 style="float:right"><a href="add_company" class="btn btn-success"><i class="fa fa-plus"> Add Company</i></a></h3>
                    </div>
                </div>
                <table id="tbl_company" class="table table-responsive table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Registration</th>
                            <th>Total Shares</th>
                            <th>Remarks</th>
                            <th>Last Update</th>
                            <th>Action</th>
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
            <div id="corporation" class="tab-pane fade">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Corporation Information</h3>
                    </div>
                    <div class="col-md-6">
                        <h3 style="float:right"><a href="add_corporation" class="btn btn-success"><i class="fa fa-plus"> Add Corporation</i></a></h3>
                    </div>
                </div>
                <table id="tbl_corp" class="table table-responsive table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Corporation Name</th>
                            <th>Registration</th>
                            <th>Type of Shares</th>
                            <th>Shares Owned</th>
                            <th>Remarks</th>
                            <th>Last Update</th>
                            <th>Action</th>
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
            <div id="shareholder" class="tab-pane fade">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Individual Information</h3>
                    </div>
                    <div class="col-md-6">
                        <h3 style="float:right"><a href="add_individual" class="btn btn-success"><i class="fa fa-plus"> Add Individual</i></a></h3>
                    </div>
                </div>
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
                            <th>Action</th>
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
                    <!-- <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Company Affiliation</th>
                            <th>Position</th>
                            <th>Stocks owned</th>
                            <th>Last update</th>
                            <th>Action</th>
                        </tr>
                    </tfoot> -->
                </table>
            </div>
        </div>
        <!-- Adding of field -->
        <!-- <form method="post">
            <div id="buildyourform"></div>
            <button name="test_submit">submit</button>
        </form>
        <input type="button" value="Add a field" class="add" id="add" /> -->

    </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
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
    <?php include('partial/index_footer.php'); ?>
</body>

</html>