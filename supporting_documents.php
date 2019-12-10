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
    <h3>Upload Supporting Documents</h3><br>
    <?= $attachment_res ?>
    <div class="containter">
        <form action="" method="post" enctype="multipart/form-data" style="width:800px">
            <div class="form-group">
                <label for="attachment">Upload to this company:</label>
                <input type='text' name='company_name' class='form-control' required list='comp_list'>
                <datalist id='comp_list'>
                    <?php
                    $sql = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), corporation_name) ASC";
                    $stmt = sqlsrv_query($db, $sql);
                    echo "'";
                    $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
                    $stmt2 = sqlsrv_query($db, $sql2);
                    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['company_name'] . "</option>";
                    }
                    echo "'";
                    ?>
                </datalist>
            </div>
            <div class="form-group">
                <label for="attachment">Uploaded by:</label>
                <input type='text' name='root_company' class='form-control' required list='comp_list1'>
                <datalist id='comp_list1'>
                    <?php
                    // corporations
                    $sql = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), corporation_name) ASC";
                    $stmt = sqlsrv_query($db, $sql);
                    // Individuals
                    $sql1 = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), ID) ASC";
                    $stmt1 = sqlsrv_query($db, $sql1);
                    // Comapanies
                    $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
                    $stmt2 = sqlsrv_query($db, $sql2);
                    echo "'";
                    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['company_name'] . "</option>";
                    }
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['corporation_name'] . "</option>";
                    }
                    while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['first_name'] . ' ' . $row['last_name'] . "</option>";
                    }

                    
                    echo "'";
                    ?>
                </datalist>
            </div>
            <div class="form-group">
                <label for="attachment">Attach Supporting documents</label>
                <input type='file' name='attachment[]' class='form-control' required>
            </div>
            <div class="form-group">
                <label for="attachment">Remarks</label>
                <textarea name='remarks[]' class='form-control' rows="5"></textarea>
            </div>
            <div class="form-group">
                <div id="div_attachments"></div>
                <input type="button" class="add_attachments btn btn-success" value="+ Attach more" id="add_attachments" style="margin-top:3px" />
            </div>
            <button name="btn_upload_attachment" class="btn btn-primary" style="float:right">Submit</button>
            <hr>
        </form>
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
            $("#add_attachments").click(function() {
                var lastField = $("#div_attachments div:last");
                var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                fieldWrapper.data("idx", intId);
                var removeButton = $("<div class='col-md-1 col-xs-1 col-xl-1 col-sm-1'><button class='btn btn-danger' style='margin-left:2px;'>-</button></div>");
                var attachments = $("<div class='col-md-11 col-xs-11 col-xl-11 col-sm-11'><label for='attachment'>Attach Supporting documents</label><input type='file' name='attachment[]' class='form-control' required></div>");
                var remarks = $("<div class='col-md-11 col-xs-11 col-xl-11 col-sm-11'><label for='remarks'>Remarks</label><textarea name='remarks[]' class='form-control' rows='5'></textarea></div>");
                removeButton.click(function() {
                    $(this).parent().remove();
                });
                fieldWrapper.append(attachments);
                fieldWrapper.append(remarks);
                fieldWrapper.append(removeButton);
                $("#div_attachments").append(fieldWrapper);
            });
        });
    </script>
</body>

</html>