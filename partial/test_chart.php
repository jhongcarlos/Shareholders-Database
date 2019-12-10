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
    <style id="myStyles">
        [link-id] path {
            stroke: #FFF;
            display: none;
        }

        .field_1 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            text-transform: uppercase;
            fill: #fff;
            word-wrap: break-word;
        }

        .field_0 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            text-transform: uppercase;
            fill: #000;
        }

        text {
            font-weight: bold;
        }

        rect {
            fill: gray;
        }

        [node-id='1029'] rect {
            fill: #456;
        }

        [node-id='-1'] rect {
            fill: #fff;
        }
    </style>
</head>

<body>

    <div id="companies" style="height:100%;width:100%"></div>
    <script>
        $(document).ready(function(e) {
            OrgChart.slinkTemplates.affiliations = Object.assign({}, OrgChart.slinkTemplates.orange);
            OrgChart.slinkTemplates.affiliations.link = '<path  marker-start="url(#dotSlinkOrange)" marker-end="url(#arrowSlinkOrange)" stroke-linejoin="round" stroke="#000" stroke-width="1" fill="none" d="{d}" />';
            OrgChart.templates.ana.field_0 = '<text class="field_0"  style="font-size: 20px;" fill="#ffffff" x="125" y="30" text-anchor="middle">{val}</text>';
            OrgChart.templates.ana.field_1 = '<text class="field_1" style="font-size: 12px;" fill="#ffffff" x="125" y="50" text-anchor="middle">{val}</text>';
            OrgChart.templates.ana.html = '<foreignobject class="node" style="font-size: 19px;color:#fff;text-align:center;font-family: Verdana, Geneva, Tahoma, sans-serif;text-transform: uppercase;" x="25" y="25" width="200" height="100" text-anchor="middle">{val}</foreignobject>';
            OrgChart.slinkTemplates.affiliations.labelPosition = 'start';
            setInterval(function() {
                $('a[title="GetOrgChart jquery plugin"]').hide();
                // $('.get-btn').hide();
            }, 1);
        });
        OrgChart.CLINK_CURVE = 0.5;
        var chart = new OrgChart(document.getElementById("companies"), {
            // mouseScrool: OrgChart.action.none,
            // template: "ula",
            orientation: OrgChart.orientation.left,
            // levelSeparation: 50,
            // layout: OrgChart.treeLeftOffset,
            // enableSearch: false,

            tags: {
                "Category": {
                    template: "ula"
                },
                "Category1": {
                    template: "polina"
                }
            },
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
                $ttest = 0;
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $arr = explode("|", $row['company_affiliation']);
                    $shares_owned = explode("|", $row['shares_owned']);
                    foreach ($arr as $k => $v) {
                        if ($v == "") {
                            $v = 0;
                        } else {
                            // Loop starts here
                            $sql2 = "SELECT * FROM dbo.tbl_company 
                            WHERE CONVERT(NVARCHAR(MAX), company_name) = N'$v'
                            AND is_deleted LIKE '0'";
                            $stmt2 = sqlsrv_query($db, $sql2);
                            if ($r = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                                $total_shares = $r['total_shares'];
                                ?> {
                                    from: <?= $row['ID'] ?>,
                                    to: <?= $r['ID'] ?>,
                                    template: 'affiliations',
                                    // template: 'affiliations',
                                    label: '<?php
                                                            $num1 = str_replace(',', '', $total_shares);
                                                            $num2 = str_replace(',', '', $shares_owned[$k]);
                                                            $percent = (int) $num2 / (int) $num1;
                                                            echo $percent_f = number_format($percent * 100, 2) . '%';
                                                            ?>',
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
                }
            },
            nodeBinding: {
                field_0: "description",
                field_1: "name",
                html: "html",
            },
            nodes: [{
                    id: -1,
                    pid: null,
                    tags: ["Category"],
                    description: '<?php
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
                            if ($row['internal_external'] == "External") {
                                echo 'tags: ["Category1"],';
                            }
                            ?>
                        html: '<div><?= $row['company_name'] ?></div>',
                        Address: '<?= $row['address'] ?>',
                    },
                <?php
                }
                ?>

            ]
        });
        chart.on('exportstart', function(sender, args) {
            args.content += document.getElementById('myStyles').outerHTML;
        });
    </script>
</body>

</html>