<?php
include('../server.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="stylesheet" href="../css/test_css.css"> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://balkangraph.com/js/latest/OrgChart.js"></script>

    <title><?php if (!empty($_GET['cat'])) {
                echo $_GET['cat'] . ' - ';
            } ?>Graphical Report</title>
</head>

<body>

    <div id="people" style="height:100%;width:100%"></div>
    <script>
        $(document).ready(function(e) {
            OrgChart.slinkTemplates.affiliations = Object.assign({}, OrgChart.slinkTemplates.orange);
            OrgChart.slinkTemplates.affiliations.link = '<path  marker-start="url(#arrowSlinkOrange)" marker-end="url(#dotSlinkOrange)" stroke-linejoin="round" stroke="#000" stroke-width="2" fill="none" d="{d}" />';
            setInterval(function() {
                $('a[title="GetOrgChart jquery plugin"]').hide();
                // $('.get-btn').hide();
            }, 1);

        });
        var chart = new OrgChart(document.getElementById("people"), {
            // mouseScrool: OrgChart.action.none,
            // template: "ula",
            <?php
            if (empty($_GET)) {
                echo '
                tags: {
                    "Category": {
                        template: "ula"
                    },
                    "Category1": {
                        template: "polina"
                    }
                },
                ';
            } elseif (empty($_GET['type'])) {
                echo '
                tags: {
                    "Category": {
                        template: "ula"
                    },
                    "Category1": {
                        template: "polina"
                    }
                },
                ';
            } else {

                if ($_GET['type'] == "External") {
                    // ====
                    echo ' 
            template: "ula",
            tags: {
                "Category": {
                    template: "ana"
                },
                "Category1": {
                    template: "polina"
                }
            },
            orientation: OrgChart.orientation.left,';
                } elseif ($_GET['type'] == "Internal") {
                    echo ' 
            tags: {
                "Category": {
                    template: "ula"
                },
                "Category1": {
                    template: "polina"
                }
            },';
                }
            }
            // ====
            ?>
            slinks: [
                <?php
                $sql = "";
                $stmt = "";
                if (empty($_GET)) {
                    $sql = "SELECT * FROM dbo.tbl_company 
                    WHERE is_deleted LIKE '0'
                    OR CONVERT(NVARCHAR(MAX), company_name) = N'METRO PACIFIC INVESTMENTS CORPORATION'";
                    $stmt = sqlsrv_query($db, $sql);
                } elseif (empty($_GET['type'])) {
                    $sql = "SELECT * FROM dbo.tbl_company 
                    WHERE CONVERT(NVARCHAR(MAX), internal_external) = N'Internal'
                    AND is_deleted LIKE '0'
                    OR CONVERT(NVARCHAR(MAX), company_name) = N'METRO PACIFIC INVESTMENTS CORPORATION'";
                    $stmt = sqlsrv_query($db, $sql);
                } else {
                    $cat = $_GET['cat'];
                    $sql = "SELECT * FROM dbo.tbl_company 
                    WHERE CONVERT(NVARCHAR(MAX), category) = N'$cat' 
                    AND is_deleted LIKE '0'
                    OR CONVERT(NVARCHAR(MAX), company_name) = N'METRO PACIFIC INVESTMENTS CORPORATION'";
                    $stmt = sqlsrv_query($db, $sql);
                }
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $arr = explode("|", $row['company_affiliation']);
                    foreach ($arr as $k => $v) {
                        if ($v == "") {
                            $v = 0;
                        } else {
                            // Loop starts here
                            $sql2 = "SELECT * FROM dbo.tbl_company 
                            WHERE CONVERT(NVARCHAR(MAX), company_name) = N'$v'
                            AND is_deleted LIKE '0'";
                            $stmt2 = sqlsrv_query($db, $sql2);
                            if ($r = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) { ?> {
                                    from: <?= $row['ID'] ?>,
                                    to: <?= $r['ID'] ?>,
                                    template: 'affiliations',
                                    label: '<?= "from:" . $row['ID'] . "to:" . $r['ID'] ?>',
                                },
                <?php

                            }
                        }
                    }
                }
                ?>
            ],
            menu: {
                pdf: {
                    text: "Export PDF"
                },
                png: {
                    text: "Export PNG"
                },
                svg: {
                    text: "Export SVG"
                },
                csv: {
                    text: "Export CSV"
                }
            },
            nodeBinding: {
                field_0: "name",
                field_1: "TotalShare"
            },
            nodes: [{
                    id: -1,
                    pid: null,
                    tags: ["Category"],
                    name: '<?php
                            if (empty($_GET)) {
                                echo "All";
                            } else {
                                echo $_GET['cat'];
                            }
                            ?>'
                },
                <?php
                $sql = "";
                $stmt = "";
                if (empty($_GET)) {
                    $sql = "SELECT * FROM dbo.tbl_company WHERE is_deleted LIKE '0'";
                    $stmt = sqlsrv_query($db, $sql);
                } else {
                    $cat = $_GET['cat'];
                    $type = "";
                    if (!empty($_GET['type'])) {
                        $type = $_GET['type'];
                        $sql = "SELECT * FROM dbo.tbl_company 
                        WHERE CONVERT(NVARCHAR(MAX), category) = N'$cat' 
                        AND is_deleted LIKE '0' 
                        AND CONVERT(NVARCHAR(MAX), internal_external) = N'$type'
                        OR CONVERT(NVARCHAR(MAX), company_name) = N'METRO PACIFIC INVESTMENTS CORPORATION'";
                        $stmt = sqlsrv_query($db, $sql);
                    } else {
                        $sql = "SELECT * FROM dbo.tbl_company 
                        WHERE CONVERT(NVARCHAR(MAX), category) = N'$cat' 
                        AND is_deleted LIKE '0'
                        OR CONVERT(NVARCHAR(MAX), company_name) = N'METRO PACIFIC INVESTMENTS CORPORATION'";
                        $stmt = sqlsrv_query($db, $sql);
                    }
                }
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $par_id = "";
                    if ($row['parent_id'] == 0) {
                        $par_id = "null";
                    } else {
                        $par_id = $row['parent_id'];
                    }
                    ?> {
                        id: <?= $row['ID'] ?>,
                        pid: <?= $row['parent_id'] ?>,
                        <?php
                            if($row['internal_external'] == "External"){
                                echo 'tags: ["Category1"],';
                            }
                        ?>
                        name: '<?= $row['company_name'] ?>',
                        TotalShare: '<?= $row['total_shares'] ?>',
                        Address: '<?= $row['address'] ?>',
                    },
                <?php
                }
                ?>

            ]
        });
    </script>
</body>

</html>