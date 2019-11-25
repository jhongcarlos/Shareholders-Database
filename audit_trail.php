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
    <script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js"></script>
    <link href="https://cdn.syncfusion.com/ej2/material.css" rel="stylesheet">
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
                        <!-- <button class="btn" style="float:right" name="at_pdf">Generate PDF</button><br><br> -->
                        <button class="btn btn-primary" style="float:right" type="button" data-toggle="modal" data-target="#modal_audit_trail">
                            Generate PDF</button>
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
                            $Date = $row['update_time']->format('m-d-Y h:i A');
                            ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= $row['user_name'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td><?= $Date ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Logistics -->
    <div class="modal fade" id="modal_audit_trail" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Duration</h4>
                </div>
                <div class="modal-body">
                    <form action="pdf" method="post" target="blank">
                        <label for="from">From</label>
                            <input type="text" id="from" name="startDate" class="form-control">

                        <label for="to">To</label>
                            <input type="text" id="to" name="endDate" class="form-control">
                            <br><br>
                        <button class="btn btn-primary" name="at_pdf">Generate PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('partial/index_footer.php'); ?>
    <script>
        var datepickerfrom = new ej.calendars.DatePicker({ width: "255px" });
        datepickerfrom.appendTo('#from');
        var datepickerto = new ej.calendars.DatePicker({ width: "255px" });
        datepickerto.appendTo('#to');

        $(document).ready(function() {
            if ($('html').hasClass('no-touch')) {
                var $input, $btn;
                $(".date-wrapper").each(function(index) {
                    $input = $(this).find('input');
                    $btn = $(this).find('.glyphicon');
                    $input.attr('type', 'text');
                    var pickerStart = new Pikaday({
                        field: $input[0],
                        trigger: $btn[0],
                        container: $(this)[0],
                        format: 'DD/MM/YYYY',
                        firstDay: 1
                    });
                    $btn.show();
                });
            } else {
                $('.date-wrapper input').attr('type', 'date');
                $('.calendar-btn').hide();
            }
            $('#tbl_audit_trail').DataTable({});
        });
    </script>
</body>

</html>