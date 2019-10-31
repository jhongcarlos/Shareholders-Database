<?php
include('server.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include('partial/header.php'); ?>
    <title>Edit Company - Metro Pacific Investment Corporation</title>
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
                    <h2>Edit Company</h2>
                    <?= $msg; ?>
                    <hr>
                </div>
                <div class="col-sm-12 form-column">
                    <form method="post" enctype="multipart/form-data">
                        <?php
                        $id = $_SESSION['edit_id_comp'];
                        $sql = "SELECT * FROM dbo.tbl_company WHERE ID = $id";
                        $stmt = sqlsrv_query($db, $sql);
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                            $company_affiliation = explode(",", $row['company_affiliation']);
                            $director_officer = explode(",", $row['director_officer']);
                            $do_position = explode(",", $row['do_position']);
                            $shares_owned = explode("|", $row['shares_owned']);
                            $type_of_share = explode(",", $row['type_of_share']);
                            $remarks = explode(",", $row['remarks']);

                            ?>
                            <div class="row">
                                <div class="col md-12 col-xs-12 col-xl-12 col-sm-12">
                                    <label for="f_name">Company Name</label>
                                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                    <input type="text" name="comp_name" class="form-control" required value="<?= $row['company_name'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                    <label for="f_name">SEC Number</label>
                                    <input type="text" name="sec_num" class="form-control" required value="<?= $row['sec_num'] ?>">
                                </div>
                                <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                    <label for="f_name">TIN Number</label>
                                    <input type="text" name="tin_num" class="form-control" required value="<?= $row['tin_num'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-12 col-xs-12 col-xl-12 col-sm-12">
                                    <label for="f_name">Total Number of Shares</label>
                                    <input type="text" name="total_shares" class="form-control" required value="<?= $row['total_shares'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-12 col-xs-12 col-xl-12 col-sm-12">
                                    <label for="f_name">Address</label>
                                    <textarea name="address" class="form-control" cols="5" rows="5"><?= $row['address'] ?></textarea>
                                </div>
                            </div>
                            <!-- Start of for loop -->
                            <?php
                                for ($i = 0; $i < count($company_affiliation); $i++) {
                                    if (empty($director_officer[$i])) {
                                        ?>
                                    <div class="row">
                                        <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                            <label for="f_name">Directors/Officers</label>
                                            <input type="text" name="dir_off[]" class="form-control" required value="">
                                        </div>
                                        <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                            <label for='f_name'>Position</label>
                                            <input type="text" name="do_position[]" class="form-control" required value="">
                                        </div>
                                    </div>
                                <?php
                                        } else {
                                            ?>
                                    <div class="row">
                                        <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                            <label for="f_name">Directors/Officers</label>
                                            <input type="text" name="dir_off[]" class="form-control" required value="<?= $director_officer[$i] ?>">
                                        </div>
                                        <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                            <label for='f_name'>Position</label>
                                            <input type="text" name="do_position[]" class="form-control" required value="<?= $do_position[$i] ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col md-12 col-xs-12 col-xl-12 col-sm-12">
                                        <label for='f_name'>Company Affiliation</label>
                                        <!-- <input type="text" autocomplete="off" name="aff_comp[]" class="form-control" required value="<?= $company_affiliation[$i] ?>"> -->
                                        <input class='form-control' value="<?= $company_affiliation[$i] ?>" list='sh_list_result' name='aff_comp[]' id='sh_list' onclick='comp_validation()'>
                                        <datalist id='sh_list_result'>
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
                                                    $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(NVARCHAR(MAX), is_deleted) = N'0' ORDER BY CONVERT(NVARCHAR(MAX), company_name) ASC";
                                                    $stmt2 = sqlsrv_query($db, $sql2);
                                                    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                                                        echo "<option>" . $row['company_name'] . "</option>";
                                                    }
                                                    echo "'";
                                                    ?>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                        <label for='f_name'>Type of Shares</label>
                                        <input type="text" name="type_of_shares[]" class="form-control" required value="<?= $type_of_share[$i] ?>">
                                    </div>
                                    <div class="col md-6 col-xs-6 col-xl-6 col-sm-6">
                                        <label for="f_name">Shares owned</label>
                                        <input type="text" name="shares_owned[]" class="form-control" required value="<?= $shares_owned[$i] ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col md-12 col-xs-12 col-xl-12 col-sm-12">
                                        <label for="f_name">Remarks</label>
                                        <textarea name="remarks[]" class="form-control" cols="5" rows="5"><?= $remarks[$i] ?></textarea>
                                    </div>
                                </div>
                                <hr>
                            <?php } ?>
                            <div class="form-group">
                                <div id="add_user"></div>
                                <input type="button" class="a_add btn btn-success" value="+" id="edit_corp_add" style="margin-top:3px" />
                            </div>
                            <button class="btn btn-primary" name="edit_company_submit">Submit</button>
                        <?php } ?>
                    </form>
                </div>
                <!-- END -->
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#edit_corp_add").click(function() {
                var lastField = $("#add_user div:last");
                var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 1;
                var fieldWrapper = $("<div class=\"row\" id=\"field" + intId + "\"/>");
                fieldWrapper.data("idx", intId);

                // Directors and Officers = dir_off
                var dir_off = $("<div class='col-md-5 col-xs-5 col-xl-5 col-sm-5'><label for='f_name'>Directors/Officers</label><input class='form-control' type='text' name='dir_off[]' class='form-control' /></div>");
                var do_position = $("<div class='col-md-5 col-xs-5 col-xl-5 col-sm-5'><label for='f_name'>Position</label><input class='form-control' type='text' name='do_position[]' class='form-control'/></div>");
                var aff_comp = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><label for='f_name'>Company Affiliation</label><input class='form-control' type='text' name='aff_comp[]' class='form-control'/></div>");
                var type_of_shares = $("<div class='col-md-5 col-xs-5 col-xl-5 col-sm-5'><label for='f_name'>Type of Shares</label><input class='form-control' type='text' name='type_of_shares[]' class='form-control'/></div>");
                var shares_owned = $("<div class='col-md-5 col-xs-5 col-xl-5 col-sm-5'><label for='f_name'>Shares owned</label><input class='form-control' type='text' name='shares_owned[]' class='form-control'/></div>");
                var remarks = $("<div class='col-md-10 col-xs-10 col-xl-10 col-sm-10'><label for='f_name'>Remarks</label><textarea name='remarks[]' class='form-control' cols='5' rows='5'></textarea></div>");
                var removeButton = $("<div class='col-md-2 col-xs-2 col-xl-2 col-sm-2'><button class='btn btn-danger' style='margin-left:2px;'>-</button></div>");
                removeButton.click(function() {
                    $(this).parent().remove();
                });
                fieldWrapper.append(dir_off);
                fieldWrapper.append(do_position);
                fieldWrapper.append(aff_comp);
                fieldWrapper.append(type_of_shares);
                fieldWrapper.append(shares_owned);
                fieldWrapper.append(remarks);
                fieldWrapper.append(removeButton);
                $("#add_user").append(fieldWrapper);
            });
        });
    </script>
</body>

</html>