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
    <link rel="stylesheet" href="css/style.css">
    <?php include('partial/header.php'); ?>
    <title>Register - Metro Pacific Investments Corporation</title>
    <script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js"></script>
    <link href="https://cdn.syncfusion.com/ej2/material.css" rel="stylesheet">
</head>

<body style="background:#456">
    <div class="cont">
        <div class="form"><br>
            <img src="images/MPIC_logo.png" alt="" class="logo"><br>
            <?php if (empty($error_message)) {
                $error_message = "";
            } ?>
            <a href="index">← Home</a>
            <h2 style="color:#456;text-align:left">Register</h2>
            <p style="color: red;text-align:center;padding:5px"><?= $error_message ?></p>
            <form method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col md-5 col-xs-4 col-xl-5 col-sm-5">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control">
                        </div>
                        <div class="col md-5 col-xs-4 col-xl-5 col-sm-5">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control">
                        </div>
                        <div class="col md-2 col-xs-4 col-xl-2 col-sm-2">
                            <label for="middle_name">M.I</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" class="form-control" id="role">
                        <option>Administrator</option>
                        <option>Site Admin</option>
                        <option>Viewer</option>
                    </select>
                </div>
                <div class="form-group" id="whandle" style="display:none">
                    <label for="will_handle">Will handle:</label>
                    <input placeholder='Category Name' class='form-control' list='comp_list_result' name='will_handle' id='comp_list'>
                    <datalist id='comp_list_result'>
                        <option>Power</option>
                        <option>Water</option>
                        <option>Rail</option>
                        <option>Logistics</option>
                        <option>Tollways</option>
                        <option>Hospital</option>
                        <option>Others</option>
                    </datalist>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="duration">Valid until:</label>
                    <input type="text" id="duration" name="duration" class="form-control">
                </div>
                <button class="login" name="btn_register">Register</button>
            </form>
        </div>
    </div>
    <?php
    include('partial/index_footer.php');
    ?>
    <script>
        var duration = new ej.calendars.DatePicker({
            width: "100%"
        });
        duration.appendTo('#duration');
        $(document).ready(function() {
            if ($('html').hasClass('no-touch')) {
                var $input, $btn;
                $(".date-wrapper").each(function(index) {
                    $input = $(this).find('input');
                    $btn = $(this).find('.glyphicon');
                    $input.attr('type', 'text');
                    var pickerStart = new Pikaday({
                        field: $input[0],
                        trigger: $btn[0],
                        container: $(this)[0],
                        format: 'DD/MM/YYYY',
                        firstDay: 1
                    });
                    $btn.show();
                });
            } else {
                $('.date-wrapper input').attr('type', 'date');
                $('.calendar-btn').hide();
            }
            $("#role").change(function() {
                if ($(this).find("option:selected").val() == "Site Admin") {
                    $("#whandle").show();
                } else {
                    $("#whandle").hide();
                }
            });
        });
    </script>
</body>

</html>