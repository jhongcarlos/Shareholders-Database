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
    <title>Step 2 - Metro Pacific Investment Corporation</title>
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
                </div><br><br>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#corporation">Corporation</a></li>
                    <li><a data-toggle="tab" href="#shareholder">Individual</a></li>
                </ul>
                <div class="tab-content">
                    <div id="corporation" class="tab-pane fade in active">
                        <h3>Add Corporation</h3>
                        <div class="col-sm-12 form-column">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="f_name">Corporation Name *</label>
                                    <input type="text" name="c_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6 col-sm-6 col-xl-6">
                                            <label for="sec_num">SEC Number *</label>
                                            <input type="text" name="sec_num" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 col-xs-6 col-sm-6 col-xl-6">
                                            <label for="tin_num">TIN Number *</label>
                                            <input type="text" name="tin_num" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address *</label>
                                    <textarea name="address" class="form-control" required cols="15" rows="5"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Registration</label>
                                    <div id="registration"></div>
                                    <input type="button" class="add btn btn-success" value="+" id="add" style="margin-top:3px" />
                                </div>
                                <div class="form-group">
                                    <label>Company Affiliation</label>
                                    <div id="corp_affiliate"></div>
                                    <input type="button" class="a_add btn btn-success" value="+" id="a_add" style="margin-top:3px" />
                                </div>
                                <!-- <div class="form-group">
                            <label for="stocks_owned">Stocks Owned</label>
                            <input type="text" name="stocks_owned" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="stock_cert">Stock Certificate</label>
                            <input type="file" name="stock_cert" class="form-control">
                        </div> -->
                                <button class="btn btn-primary" style="float:right" name="corp_submit">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div id="shareholder" class="tab-pane fade">
                        <h3>Add Shareholder</h3>
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
                                    <input type="button" class="add btn btn-success" value="+ Add Company Affiliation" id="shareholder_add" style="margin-top:3px" />
                                </div>
                                <button class="btn btn-primary" style="float:right" name="sh_submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#shareholder_add").click(function() {
                var lastField = $("#affiliates div:last");
                var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                fieldWrapper.data("idx", intId);
                var aff_comp = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select class='form-control' name='aff_comp[]' required>" +
                    <?php
                    $sql = "SELECT * FROM dbo.tbl_corporation";
                    $stmt = sqlsrv_query($db, $sql);
                    echo "'";
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['corporation_name'] . "</option>";
                    }
                    echo "'";
                    ?> +
                    "</select></div>");
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
            $("#add").click(function() {
                    var lastField = $("#registration div:last");
                    var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                    var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                    fieldWrapper.data("idx", intId);

                    // Directors and Officers = dir_off
                    var dir_off = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='dir_off[]' class='form-control' placeholder='Directors / Officers'/></div>");
                    var removeButton = $("<div class='col-md-2 col-xs-2 col-xl-2 col-sm-2'><button class='btn btn-danger' style='margin-left:2px;'>-</button></div>");
                    var do_position = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='do_position[]' class='form-control' placeholder='Position'/><hr></div>");
                    removeButton.click(function() {
                        $(this).parent().remove();
                    });
                    fieldWrapper.append(dir_off);
                    fieldWrapper.append(do_position);
                    fieldWrapper.append(removeButton);
                    $("#registration").append(fieldWrapper);

                });
                $("#a_add").click(function() {
                    var lastField = $("#corp_affiliate div:last");
                    var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                    var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                    fieldWrapper.data("idx", intId);
                    var aff_com = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select class='form-control' name='aff_comp[]'>" +
                        <?php
                        $sql = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
                        $stmt = sqlsrv_query($db, $sql);
                        echo "'";
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            echo "<option>" . $row['corporation_name'] . "</option>";
                        }
                        echo "'";
                        ?>

                        +
                        "</select></div>'");
                    var type_of_shares = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select name='type_of_shares[]' class='form-control'><option value='0'>- Type of Share -</option><option>Preferred</option><option>Common</option></select></div>");
                    var shares_owned = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='shares_owned[]' class='form-control' placeholder='Shares Owned'/></div>");
                    var remarks = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><textarea name='remarks[]' cols='15' rows='5' class='form-control' placeholder='Remarks'></textarea><hr></div>");
                    var stocks_certificate = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><span>Stock Certificate</span><input type='file' name='stock_cert[]' class='form-control' placeholder='Stock Certificate'/></div>");
                    var removeButton = $("<div class='col-md-2 col-xs-2 col-xl-2 col-sm-2'><button class='btn btn-danger' style='margin-left:2px;'>-</button></div>");
                    removeButton.click(function() {
                        $(this).parent().remove();
                    });
                    fieldWrapper.append(aff_com);
                    fieldWrapper.append(type_of_shares);
                    fieldWrapper.append(shares_owned);
                    fieldWrapper.append(stocks_certificate);
                    fieldWrapper.append(remarks);
                    fieldWrapper.append(removeButton);
                    $("#corp_affiliate").append(fieldWrapper);
                });
        });
    </script>
</body>

</html>