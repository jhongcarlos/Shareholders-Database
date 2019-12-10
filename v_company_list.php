<?php
include('server.php');
if ($_SESSION['mpic_mpic_role'] == "Super User" || $_SESSION['mpic_mpic_role'] == "Administrator") { } else {
    header('Location:index');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include('partial/header.php'); ?>
    <title>Add Company - Metro Pacific Investment Corporation</title>
    <style>
        .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            height: 80px;
            width: 180px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #0C9;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
        }

        .my-float {
            margin-top: 22px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="images/MPIC_logo.png" alt="" class="img-responsive logo">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 well">
                <div class="col-sm-12 form-legend">
                    <a href="index">← Home</a>
                    <h2>Company List</h2>
                    <table id="tbl_company" class="table table-responsive table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Registration</th>
                                <th>Total Shares</th>
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
                                    </td>
                                    <td><?= str_replace('|', '<br />', $row['total_shares']) ?></td>
                                    <td><?= $row['last_update'] ?></td>
                                    <td>
                                        <a href="v_company?company_name=<?= $row['company_name'] ?>"><button class="btn btn-primary" title="View"><i class="fa fa-eye"></i></button></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include('partial/index_footer.php'); ?>
    <script>
        $('#tbl_company').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ]
        });
    </script>
</body>

</html>