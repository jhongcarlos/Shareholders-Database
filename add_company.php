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
            <div class="col-sm-8 col-sm-offset-2 well">
                <div class="col-sm-12 form-legend">
                    <a href="index">← Home</a>
                    <h2>Add a Company</h2>
                    <?= $add_comp_res; ?>
                </div>
                <div class="col-sm-12 form-column">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="f_name">Company Name *</label>
                            <input type="text" name="co_name" class="form-control" required>
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
                            <label for="f_name">Total number of shares *</label>
                            <input type="text" name="total_num_shares" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="f_name">Category *</label>
                            <select name="category" class="form-control" required>
                                <option>-- Select Category --</option>
                                <option>Power</option>
                                <option>Water</option>
                                <option>Rail</option>
                                <option>Logistics</option>
                                <option>Tollways</option>
                                <option>Hospital</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="f_name">Internal / External *</label>
                            <select name="int_ext" class="form-control" required>
                                <option>-- Select --</option>
                                <option>Internal</option>
                                <option>External</option>
                            </select>
                        </div>
                        <label for="f_name">Biggest share *</label>
                        <input class='form-control' list='comp_list_res' name='parent_id' id='comp_list'>
                        <datalist id='comp_list_res'>
                            <?php
                            $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), company_name) ASC";
                            $stmt2 = sqlsrv_query($db, $sql2);
                            while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                                echo "<option value='" . $row['ID'] . "'>" . $row['company_name'] . "</option>";
                            }
                            ?>
                        </datalist>
                        <div class="form-group">
                            <label for="address">Address *</label>
                            <textarea name="address" required class="form-control" required cols="15" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Registration</label>
                            <div id="registration"></div>
                            <input type="button" class="add btn btn-success" value="+ Add Registration" id="add" style="margin-top:3px" />
                        </div>
                        <div class="form-group">
                            <label>Company Affiliations</label>
                            <div id="affiliate"></div>
                            <input type="button" class="a_add btn btn-success" value="+ Add company affiliations" id="a_add" style="margin-top:3px" />
                        </div>
                        <!-- <div class="form-group">
                            <label for="stocks_owned">Stocks Owned</label>
                            <input type="text" name="stocks_owned" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="stock_cert">Stock Certificate</label>
                            <input type="file" name="stock_cert" class="form-control">
                        </div> -->
                        <button class="btn btn-primary" style="float:right" name="comp_submit">Submit</button>
                        <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add Shareholder</button> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- <a class="float" data-toggle="modal" data-target="#add_data_Modal">
        <i class="fa fa-plus my-float"></i>
    </a> -->
    <div id="add_data_Modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Corporation / Shareholder</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#corporation">Corporation</a></li>
                        <li><a data-toggle="tab" href="#shareholder">Individual</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="corporation" class="tab-pane fade in active">
                            <h3>Add Corporation</h3>
                            <form method="post" enctype="multipart/form-data" id="insert_form_corp">
                                <label for="f_name">Corporation Name *</label>
                                <input type="text" name="c_name" class="form-control" required>
                                <br />
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
                                <br />
                                <label for="address">Address *</label>
                                <textarea name="address" class="form-control" required cols="15" rows="5"></textarea>
                                <br />
                                <label>Registration</label>
                                <div id="registration_modal"></div>
                                <input type="button" class="registration_modal_add btn btn-success" value="+ Add Registration" id="registration_modal_add" style="margin-top:3px" />
                                <br />
                                <label>Company Affiliation</label>
                                <div id="comp_affiliate_modal"></div>
                                <input type="button" class="comp_affiliate_modal_add btn btn-success" value="+ Add Company Affiliation" id="comp_affiliate_modal_add" style="margin-top:3px" />
                                <br />
                                <input type="submit" name="insert_corp_modal" id="insert_corp_modal" value="Insert" class="btn btn-primary" style="float:right" /><br><br>

                            </form>
                        </div>
                        <div id="shareholder" class="tab-pane fade">
                            <h3>Add Shareholder</h3>
                            <form method="post" enctype="multipart/form-data" id="insert_form_sh">
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
                                <br />
                                <label>Company Affiliation</label>
                                <div id="affiliates_corp_modal"></div>
                                <input type="button" class="add btn btn-success" value="+ Add Company Affiliation" id="affiliates_corp_modal_add" style="margin-top:3px" />
                                <br><br>
                                <input type="submit" name="insert_sh_modal" id="insert_sh_modal" value="Insert" class="btn btn-primary" style="float:right" /><br><br>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php include('partial/index_footer.php'); ?>
    <script>
        $(document).ready(function() {
            $('#insert_form_corp').on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: "insert_corp.php",
                    method: "POST",
                    data: $('#insert_form_corp').serialize(),
                    beforeSend: function() {
                        $('#insert_corp_modal').val("Inserting");
                    },
                    success: function(data) {
                        $('#insert_form_corp')[0].reset();
                        $('#insert_corp_modal').val("Insert");
                        $('#add_data_Modal').modal('hide');
                        $('#sh_list_result').html(data);
                    }
                });
            });
            // ============
            $('#insert_form_sh').on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: "insert_sh.php",
                    method: "POST",
                    data: $('#insert_form_sh').serialize(),
                    beforeSend: function() {
                        $('#insert_sh_modal').val("Inserting");
                    },
                    success: function(data) {
                        $('#insert_form_sh')[0].reset();
                        $('#insert_sh_modal').val("Insert");
                        $('#add_data_Modal').modal('hide');
                        $('#sh_list_result').html(data);
                    }
                });
            });
            // =============
            $("#affiliates_corp_modal_add").click(function() {
                var lastField = $("#affiliates_corp_modal div:last");
                var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                fieldWrapper.data("idx", intId);
                var aff_com = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input placeholder='Company Name' class='form-control' list='sh_list_result' name='aff_comp[]' id='sh_list' onclick='comp_validation()'><datalist id='sh_list_result'>" +
                    <?php
                    echo "'";
                    $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), company_name) ASC";
                    $stmt2 = sqlsrv_query($db, $sql2);
                    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['company_name'] . "</option>";
                    }
                    echo "'";
                    ?> +
                    "</datalist></div><div class='col-md-1 col-xs-1 col-xl-1 col-sm-1'><a id='add_sh_show' style='display:none' class='btn btn-success'>+</a></div>'");
                var type_of_shares = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select name='type_of_shares[]' class='form-control'><option value='0'>- Type of Share -</option><option>Preferred</option><option>Common</option></select></div>");
                var shares_owned = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='shares_owned[]' class='form-control' placeholder='Shares Owned'/></div>");
                var remarks = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><textarea name='remarks[]' cols='15' rows='5' class='form-control' placeholder='Remarks'></textarea><hr></div>");
                var stocks_certificate = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><span>Attachment</span><input type='file' name='stock_cert[]' class='form-control' placeholder='Stock Certificate'/></div>");
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
                $("#affiliates_corp_modal").append(fieldWrapper);
            });
            // =========
            $("#registration_modal_add").click(function() {
                var lastField = $("#registration_modal div:last");
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
                $("#registration_modal").append(fieldWrapper);

            });
            // =========
            $("#comp_affiliate_modal_add").click(function() {
                var lastField = $("#comp_affiliate_modal div:last");
                var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                fieldWrapper.data("idx", intId);
                var aff_comp = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input placeholder='Company Name' class='form-control' list='sh_list_result' name='aff_comp[]' id='sh_list' onclick='comp_validation()'><datalist id='sh_list_result'>" +
                    <?php
                    echo "'";
                    $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), company_name) ASC";
                    $stmt2 = sqlsrv_query($db, $sql2);
                    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['company_name'] . "</option>";
                    }
                    echo "'";
                    ?> +
                    "</datalist></div><div class='col-md-1 col-xs-1 col-xl-1 col-sm-1'><a id='add_sh_show' style='display:none' class='btn btn-success'>+</a></div>'");
                var held_position = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='held_position[]' class='form-control' required placeholder='Held Position'/></div>");
                var internal_external = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select name='int_ext[]' class='form-control'><option value='0'>- Internal / External -</option><option>Internal</option><option>External</option></select></div>");
                var removeButton = $("<div class='col-md-2 col-xs-2 col-xl-2 col-sm-2'><button class='btn btn-danger' style='margin-left:2px;'>-</button></div>");
                var shares_owned = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' name='shares_owned[]' class='form-control' required placeholder='Shares Owned'/></div>");
                var type_of_shares = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select name='type_of_shares[]' class='form-control'><option value='0'>- Type of Share -</option><option>Preferred</option><option>Common</option></select></div>");
                var stocks_certificate = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><span>Attachment</span><input type='file' name='stock_cert[]' class='form-control' required placeholder='Stock Certificate'/></div>");
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
                $("#comp_affiliate_modal").append(fieldWrapper);
            });
            // =========
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
                var lastField = $("#affiliate div:last");
                var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                fieldWrapper.data("idx", intId);
                var aff_com = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input placeholder='Company Name' class='form-control' list='sh_list_result' name='aff_comp[]' id='sh_list' onclick='comp_validation()'><datalist id='sh_list_result'>" +
                    <?php
                    $sql = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), corporation_name) ASC";
                    $stmt = sqlsrv_query($db, $sql);
                    echo "'";
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['corporation_name'] . "</option>";
                    }
                    $sql1 = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), first_name) ASC";
                    $stmt1 = sqlsrv_query($db, $sql1);
                    while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['first_name'] . ' ' . $row['last_name'] . "</option>";
                    }
                    $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0'";
                    $stmt2 = sqlsrv_query($db, $sql2);
                    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['company_name'] . "</option>";
                    }
                    echo "'";
                    ?> +
                    "</datalist></div><div class='col-md-1 col-xs-1 col-xl-1 col-sm-1'><a id='add_sh_show' style='display:none' class='btn btn-success'>+</a></div>'");
                var type_of_shares = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><select name='type_of_shares[]' class='form-control'><option value='0'>- Type of Share -</option><option>Preferred</option><option>Common</option></select></div>");
                var shares_owned = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><input type='text' autocomplete='off' name='shares_owned[]' class='form-control' placeholder='Shares Owned'/></div>");
                var remarks = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><textarea name='remarks[]' cols='15' rows='5' class='form-control' placeholder='Remarks'></textarea><hr></div>");
                var stocks_certificate = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><span>Attachment</span><input type='file' name='stock_cert[]' class='form-control' placeholder='Stock Certificate' /></div>");
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
                $("#affiliate").append(fieldWrapper);
            });
        });

        function comp_validation() {
            // $(document).ready(function() {
            //     $('input[name^="aff_comp"]').each(function() {
            //         var toggleMap = (function() {
            //             var input = $('#sh_list');
            //             var option = $('#sh_list_result option');
            //             input.on('input', function() {
            //                 var val = this.value;
            //                 if ($(option).filter(function() {
            //                         return this.value === val;
            //                     }).length) {
            //                         $(".float").stop().animate({height : "100px"});
            //                 } else {
            //                     $(".float").animate({height : "100px"});
            //                 }
            //             });
            //         }());
            //     });

            // });

        }
    </script>
</body>

</html>