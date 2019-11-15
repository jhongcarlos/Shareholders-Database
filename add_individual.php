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
    <title>Add Individual - Metro Pacific Investment Corporation</title>
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
    </style>
</head>

<body>
    <div class="container">
        <img src="images/MPIC_logo.png" alt="" class="img-responsive logo">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 well">
                <div class="col-sm-12 form-legend">
                    <a href="index">← Home</a>
                    <h2>Add a Individual</h2>
                </div>
                <div class="col-sm-12 form-column">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for="f_name">Given Name *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="l_name">Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="l_name">M.I</label>
                                    <input type="text" name="middle_name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Company Affiliation</label>
                            <div id="affiliates"></div>
                            <input type="button" class="add btn btn-success" value="+ Add Company Affiliation" id="add" style="margin-top:3px" />
                        </div>
                        <button class="btn btn-primary" name="sh_submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $("#add").click(function() {
                    var lastField = $("#affiliates div:last");
                    var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                    var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                    fieldWrapper.data("idx", intId);
                    var aff_comp = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input placeholder='Company Name' class='form-control' list='sh_list_result' name='aff_comp[]' id='sh_list'><datalist id='sh_list_result'>" +
                        <?php
                        $sql = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), company_name) ASC";
                        $stmt = sqlsrv_query($db, $sql);
                        echo "'";
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            echo "<option>" . $row['company_name'] . "</option>";
                        }
                        $sql1 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), corporation_name) ASC";
                        $stmt1 = sqlsrv_query($db, $sql1);
                        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                            echo "<option>" . $row['corporation_name'] . "</option>";
                        }
                        echo "'";
                        ?> +
                        "</datalist></div><div class='col-md-1 col-xs-1 col-xl-1 col-sm-1'><a id='add_sh_show' style='display:none' class='btn btn-success'>+</a></div>'");
                    var held_position = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='held_position[]' class='form-control' required placeholder='Held Position'/></div>");
                    var internal_external = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select name='int_ext[]' class='form-control'><option value='0'>- Internal / External -</option><option>Internal</option><option>External</option></select></div>");
                    var removeButton = $("<div class='col-md-2 col-xs-2 col-xl-2 col-sm-2'><button class='btn btn-danger' style='margin-left:2px;'>-</button></div>");
                    var shares_owned = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='shares_owned[]' class='form-control' required placeholder='Shares Owned'/></div>");
                    var type_of_shares = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select name='type_of_shares[]' class='form-control'><option value='0'>- Type of Share -</option><option>Preferred</option><option>Common</option></select></div>");
                    var stocks_certificate = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><span>Stock Certificate</span><input type='file' name='stock_cert[]' class='form-control' required placeholder='Stock Certificate'/></div>");
                    var remarks = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><textarea name='remarks[]' cols='15' rows='5' class='form-control' placeholder='Remarks'></textarea><hr></div>");
                    removeButton.click(function() {
                        $(this).parent().remove();
                    });
                    fieldWrapper.append(aff_comp);
                    fieldWrapper.append(internal_external);
                    fieldWrapper.append(held_position);
                    fieldWrapper.append(shares_owned);
                    fieldWrapper.append(type_of_shares);
                    fieldWrapper.append(stocks_certificate);
                    fieldWrapper.append(remarks);
                    fieldWrapper.append(removeButton);
                    $("#affiliates").append(fieldWrapper);
                });
            });
        </script>
</body>

</html>