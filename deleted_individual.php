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
    <title>Deleted Individual - METRO PACIFIC INVESTMENT CORPORATION</title>
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
                <h4>Deleted Individual</h4>
                <?= $result_msg_ind; ?>
            </div>
            <!-- <form action="pdf.php" method="POST" target="blank">
            <button class="btn" style="float:right" name="at_pdf">Generate PDF</button><br><br>
        </form> -->
            <div class="panel-body">
                <table id="tbl_ind" class="table table-responsive table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Individual Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'1' ORDER BY ID DESC";
                        $stmt = sqlsrv_query($db, $sql);

                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                        <input type="hidden" name="ind_name" value="<?= $row['first_name'] . ' ' . $row['last_name'] ?>">
                                        <button class="btn btn-warning" onclick="return confirmation()" name="restore_ind" title="Restore"><i class="fa fa-undo"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include('partial/index_footer.php'); ?>
    <script>
        function confirmation() {
            if (!confirm("Are you sure you want to restore?")) {
                return false;
            }
        }
        $(document).ready(function() {
            $('#tbl_ind').DataTable({});
        });
    </script>
</body>

</html>