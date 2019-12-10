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
    <title>View Corporation Details - Metro Pacific Investment Corporation</title>
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
    $corporation_name = $_GET['corporation_name'];
    $sql = "SELECT * FROM dbo.tbl_corporation WHERE corporation_name LIKE '$corporation_name'";
    $stmt = sqlsrv_query($db, $sql);
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<h3>' . $row['corporation_name'] . '</h3>';
        ?>
        <button class="btn btn-xs view_diroff_corp" id="<?= $row["ID"]; ?>">View Directors / Officers</button>
        <hr>
        <!-- Cards -->
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
    <h3>Company Affiliations</h3><br>
    <!-- Accordion -->
    <div class="row">
        <div class="col-md-10">
            <div class="panel-group" id="accordion1">
                <!-- start of while loop -->
                <?php
                $companies = array();
                $sql1 = "SELECT * FROM dbo.tbl_corporation 
                    WHERE CONVERT(VARCHAR(MAX), corporation_name) LIKE '%$corporation_name%' 
                    AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                $stmt1 = sqlsrv_query($db, $sql1);
                $share = "";
                $typeofshare = "";
                $remarks = "";
                while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {

                    $arr3 = explode("|", $row['company_affiliation']);
                    $position = 0;
                    foreach ($arr3 as $key => $value) {
                        if ($value == $_GET['corporation_name']) {
                            $position = $key;
                            // echo $key;
                        }
                        $companies[] = $value;
                    }
                    $share = $row['shares_owned'];
                    $typeofshare = $row['type_of_share'];
                    $remarks = $row['remarks'];
                }
                foreach ($companies as $k => $v) {
                    $num = 0;
                    $rem_1 = explode(',', $remarks);
                    $corp_name = "$_GET[corporation_name]";
                    $sql = "SELECT * FROM dbo.tbl_company 
                    WHERE CONVERT(VARCHAR(MAX), company_name) LIKE '%$v%' 
                    AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";

                    $stmt = sqlsrv_query($db, $sql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        $arr = explode(",", $row['ID']);
                        // $type_of_share = explode(",", $row['type_of_share']);
                        // $shares_owned = explode("|", $row['shares_owned']);
                        $arr3 = explode("|", $row['company_affiliation']);
                        $shares_owned = explode("|", $share);
                        $type_of_share = explode(",", $typeofshare);
                        $position = "";
                        foreach ($arr3 as $key => $value) {
                            $position = $key;
                        }
                        ?>
                        <!-- Display results here -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#acollapse<?= $row['ID'] ?>">
                                        <?= $row['company_name'] ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="acollapse<?= $row['ID'] ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <button class="btn view_attachment_corp" value="<?= $row['company_name'] ?>"><i class="fa fa-paperclip"></i> View Attachment(s)</button>
                                    <hr>
                                    <p>Shares owned: <b>₱<?= $shares_owned[$k] ?></b></p>
                                    <p>Type of share: <b><?= $type_of_share[$k] ?></b></p>
                                    <p>Remarks: <b><?= $rem_1[$k] ?></b></p>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
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
        $(document).on('click', '.view_diroff_corp', function() {
            var corp_id_corp = $(this).attr("id");
            var comp_nam = "<?php echo $_GET['corporation_name'] ?>";
            if (corp_id_corp != '') {
                $.ajax({
                    url: "server.php",
                    method: "POST",
                    data: {
                        corp_id_corp: corp_id_corp,
                        comp_nam: comp_nam
                    },
                    success: function(data) {
                        $('#corp_diroff').html(data);
                        $('#corp_diroff_modal').modal('show');
                    }
                });
            }
        });
        /** 
         * root company = company or shareholder who upload = root_comp
         * company name = company where root company or shareholder uploaded
         */
        $(document).on('click', '.view_attachment_corp', function() {
            var corp_corp_name = $(this).val();
            var corp_root_comp = "<?= $_GET['corporation_name'] ?>";
            if (corp_corp_name != '') {
                $.ajax({
                    url: "server.php",
                    method: "POST",
                    data: {
                        corp_corp_name: corp_corp_name,
                        corp_root_comp: corp_root_comp
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