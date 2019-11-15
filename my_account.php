<?php include("server.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php include("partial/header.php"); ?>
    <link rel="stylesheet" href="css/sidebar.css">
</head>

<body>

    <?php include("partial/sidebar.php"); ?>
    <!-- Content -->

    

    <!-- Content -->
    </div>
    </div>

    <!-- Footer -->
    <?php include("partial/index_footer.php"); ?>
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
    </script>
</body>

</html>