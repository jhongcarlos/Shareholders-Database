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
    $id = $_GET['id'];
    $sql = "SELECT * FROM dbo.tbl_shareholder WHERE ID LIKE '$id'";
    $stmt = sqlsrv_query($db, $sql);
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<h3>' . $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . '</h3>';
    }
    ?>
    <hr>
    <h3>Company Affiliations</h3><br>
    <!-- Accordion -->
    <div class="row">
        <div class="col-md-10">
            <div class="panel-group" id="accordion1">
                <!-- start of while loop -->
                <?php
                $id = $_GET['id'];
                $sql = "SELECT * FROM dbo.tbl_shareholder WHERE ID = '$id'";
                $stmt = sqlsrv_query($db, $sql);
                $companies = array();
                $share = "";
                $typeofshare = "";
                $remarks = "";
                $held_position_h = "";
                $intext_h = "";
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $companies[] = $row['company_affiliation'];
                    $share = $row['shares_owned'];
                    $typeofshare = $row['type_of_shares'];
                    $remarks = $row['remarks'];
                    $held_position_h = $row['held_position'];
                    $intext_h = $row['internal_external'];
                }
                foreach ($companies as $k => $v) {
                    $comp = explode("|", $v);
                    $num = 0;
                    $rem_1 = explode(',', $remarks);
                    foreach ($comp as $value) {
                        $sql = "SELECT * FROM dbo.tbl_company 
                        WHERE CONVERT(VARCHAR(MAX), company_name) 
                        LIKE '%$value%' 
                        AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                        $stmt = sqlsrv_query($db, $sql);

                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            $arr = explode(",", $row['ID']);
                            $arr1 = explode(",", $row['type_of_share']);
                            $arr2 = explode("|", $row['shares_owned']);
                            $arr3 = explode("|", $row['company_affiliation']);
                            $held_position = explode(",", $held_position_h);
                            $intext = explode(",", $intext_h);
                            $share_owned = explode("|", $share);
                            $typeofshare1 = explode(",", $typeofshare);
                            $position = "";
                            foreach ($arr3 as $key => $value) {
                                $position = $key;
                            }
                            ?>
                            <!-- Display results here -->
                            <div class="panel panel-primary" style="width:800px">
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
                                        <p>Held Position: <b><?= $held_position[$num] ?></b></p>
                                        <p>Shares owned: <b>₱<?= $share_owned[$num] ?></b></p>
                                        <p>Internal / External: <b><?= $intext[$num] ?></b></p>
                                        <p>Type of share: <b><?= $typeofshare1[$num] ?></b></p>
                                        <p>Remarks: <b><?= $rem_1[$num] ?></b></p>
                                    </div>
                                </div>
                            </div>
                <?php
                        }
                        $num += 1;
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
        // $(document).on('click', '.view_diroff_corp', function() {
        //     var corp_id_corp = $(this).attr("id");
        //     var comp_nam = "<?php echo $_GET['id'] ?>";
        //     if (corp_id_corp != '') {
        //         $.ajax({
        //             url: "server.php",
        //             method: "POST",
        //             data: {
        //                 corp_id_corp: corp_id_corp,
        //                 comp_nam: comp_nam
        //             },
        //             success: function(data) {
        //                 $('#corp_diroff').html(data);
        //                 $('#corp_diroff_modal').modal('show');
        //             }
        //         });
        //     }
        // });
        /** 
         * root company = company or shareholder who upload = root_comp
         * company name = company where root company or shareholder uploaded
         */
        // $(document).on('click', '.view_attachment_corp', function() {
        //     var corp_corp_name = $(this).val();
        //     var corp_root_comp = "<?= $_GET['id'] ?>";
        //     if (corp_corp_name != '') {
        //         $.ajax({
        //             url: "server.php",
        //             method: "POST",
        //             data: {
        //                 corp_corp_name: corp_corp_name,
        //                 corp_root_comp: corp_root_comp
        //             },
        //             success: function(data) {
        //                 $('#corp_attachments_body').html(data);
        //                 $('#attachments_corp').modal('show');
        //             }
        //         });
        //     }
        // });
    </script>
</body>

</html>