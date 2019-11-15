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
    <title>Audit Trail - METRO PACIFIC INVESTMENT CORPORATION</title>
    <?php include('partial/header.php'); ?>
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
    </div><br>
    <div class="container">
        <a href="index">← Home</a>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Audit Trail</h4>
                    </div>
                    <div class="col-md-6">
                        <form action="pdf" method="POST" target="blank">
                            <button class="btn" style="float:right" name="at_pdf">Generate PDF</button><br><br>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <table id="tbl_audit_trail" class="table table-responsive table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM dbo.tbl_audit_trail ORDER BY ID DESC";
                        $stmt = sqlsrv_query($db, $sql);

                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= $row['user_name'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td><?= $row['update_time'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#tbl_audit_trail').DataTable({});
        });
    </script>
    <?php include('partial/index_footer.php'); ?>
</body>

</html>