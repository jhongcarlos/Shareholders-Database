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
    <title>View Company Details - Metro Pacific Investment Corporation</title>
    <?php include('partial/header.php'); ?>
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        .logo {
            display: block;
            height: 70px;
            width: 180px;
            /* margin-top: px; */
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            margin-bottom: 10px;
            word-break: break-all;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <?php include("partial/sidebar.php"); ?>
    <?php
    $company_name = $_GET['company_name'];
    $sql = "SELECT * FROM dbo.tbl_company WHERE company_name LIKE '$company_name'";
    $stmt = sqlsrv_query($db, $sql);
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<h3 id="comp_name_h">' . $row['company_name'] . '</h3>';
        ?>
        <button class="btn btn-xs view_diroff_comp" id="<?= $row["ID"]; ?>">View Directors / Officers</button>
        <hr>
        <!-- Cards -->
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="container">
                        <h4><b>₱<?= $row['total_shares']; ?></b></h4>
                        <p>Total shares</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="container">
                        <h4><b><?= $row['category'] ?></b></h4>
                        <p>Category</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="container">
                        <h4><b><?= $row['sec_num'] ?></b></h4>
                        <p>SEC - Registered</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="container">
                        <h4><b><?= $row['tin_num'] ?></b></h4>
                        <p>TIN - Registered</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="container">
                        <h4><b><?= $row['address'] ?></b></h4>
                        <p>Address</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="container">
                    <p>Updated as of <?= $row['last_update'] ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- /Cards -->
    <h3>Shareholders</h3><br>
    <!-- Accordion -->
    <div class="row">
        <div class="col-md-5">
            <h4>Individual Shareholder</h4>
            <div class="panel-group" id="accordions">
                <!-- start of while loop -->
                <?php
                $comp_name = $_GET['company_name'];
                $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(VARCHAR(MAX), company_affiliation) LIKE '%$comp_name%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                $stmt = sqlsrv_query($db, $sql);

                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $arr = explode(",", $row['ID']);
                    $type_of_share = explode(",", $row['type_of_shares']);
                    $shares_owned = explode("|", $row['shares_owned']);
                    $intext = explode(",", $row['internal_external']);
                    $held_position = explode(",", $row['held_position']);
                    $remarks = explode(",", $row['remarks']);
                    $arr3 = explode("|", $row['company_affiliation']);
                    $position = "";
                    $intext_position = "";
                    foreach ($arr3 as $key => $value) {
                        if ($value == $_GET['company_name']) {
                            $position = $key;
                        }
                    }
                    foreach ($intext as $key => $value) {
                        $intext_position = $key;
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $row['ID'] ?>">
                                    <?= $row['first_name'] . " " . $row['last_name']; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?= $row['ID'] ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <button class="btn view_attachment_comp" value="<?= $row['first_name'] . " " . $row['last_name']; ?>" id="<?= $row["ID"]; ?>"><i class="fa fa-paperclip"></i> View Attachment(s)</button>
                                <hr>
                                <p>Shares owned: <b>₱<?php if ($shares_owned[$position] == "") {
                                                                echo "0";
                                                            } else {
                                                                echo $shares_owned[$position];
                                                            } ?></b></p>
                                <p>Type of share: <b><?= $type_of_share[$position] ?></b></p>
                                <p>Internal / External: <b><?= $intext[$intext_position] ?></b></p>
                                <p>Held Position: <b><?= $held_position[$position] ?></b></p>
                                <p>Remarks: <b><?= $remarks[$position] ?></b></p>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <!-- end of while loop -->
            </div>
        </div>
        <div class="col-md-5">
            <h4>Corporation Shareholder</h4>
            <div class="panel-group" id="accordion1">
                <!-- start of while loop -->
                <?php
                $sql1 = "SELECT * FROM dbo.tbl_corporation 
                WHERE CONVERT(VARCHAR(MAX), company_affiliation) 
                LIKE '%$comp_name%' 
                AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                $stmt1 = sqlsrv_query($db, $sql1);
                while ($r = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {

                    $remarks = explode(",", $r['remarks']);
                    $type_of_share = explode(",", $r['type_of_share']);
                    $shares_owned = explode("|", $r['shares_owned']);
                    $intext = explode(",", $r['internal_external']);
                    $arr3 = explode("|", $r['company_affiliation']);
                    $position1 = "";

                    foreach ($arr3 as $key => $value) {
                        if ($value == $_GET['company_name']) {
                            $position1 = $key;
                        }
                    }
                    foreach ($intext as $key => $value) {
                        $intext_position = $key;
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion1" href="#acollapse<?= $r['ID'] ?>">
                                    <?= $r['corporation_name']; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="acollapse<?= $r['ID'] ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <button class="btn view_attachment_corp" value="<?= $r['corporation_name']; ?>"><i class="fa fa-paperclip"></i> View Attachment(s)</button>
                                <hr>
                                <p>Shares owned: <b>₱<?php if ($shares_owned[$position1] == "") {
                                                                echo "0";
                                                            } else {
                                                                echo $shares_owned[$position1];
                                                            } ?></b></p>
                                <p>Type of share: <b><?= $type_of_share[$position1] ?></b></p>
                                <p>Internal / External: <b><?= $intext[$intext_position] ?></b></p>
                                <p>Remarks: <b><?= $remarks[$position1] ?></b></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- end of while loop -->
            </div>
        </div>
    </div>
    <!-- /Accordion -->
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
        });
        $(document).on('click', '.view_diroff_comp', function() {
            var comp_id = $(this).attr("id");
            var comp_nam = "<?php echo $_GET['company_name'] ?>";
            if (comp_id != '') {
                $.ajax({
                    url: "server.php",
                    method: "POST",
                    data: {
                        comp_id: comp_id,
                        comp_nam: comp_nam
                    },
                    success: function(data) {
                        $('#comp_diroff').html(data);
                        $('#diroff').modal('show');
                    }
                });
            }
        });
        /** 
         * root company = company or shareholder who upload = root_comp
         * company name = company where root company or shareholder uploaded
         */
        $(document).on('click', '.view_attachment_comp', function() {
            var root_comp = $(this).val();
            var comp_name = "<?= $_GET['company_name'] ?>";
            if (comp_name != '') {
                $.ajax({
                    url: "server.php",
                    method: "POST",
                    data: {
                        comp_name: comp_name,
                        root_comp: root_comp
                    },
                    success: function(data) {
                        $('#comp_attachments_body').html(data);
                        $('#attachments_comp').modal('show');
                    }
                });
            }
        });
        $(document).on('click', '.view_attachment_corp', function() {
            var root_corp = $(this).val();
            var ind_comp_name = "<?= $_GET['company_name'] ?>";
            if (ind_comp_name != '') {
                $.ajax({
                    url: "server.php",
                    method: "POST",
                    data: {
                        root_corp: root_corp,
                        ind_comp_name: ind_comp_name
                    },
                    success: function(data) {
                        $('#corp_attachments_body').html(data);
                        $('#attachments_corp').modal('show');
                    }
                });
            }
        });
    </script>
</body>

</html>