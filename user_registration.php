<?php
include('server.php');
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
</head>

<body style="background:#456">
    <div class="cont">
        <div class="form"><br>
            <img src="images/MPIC_logo.png" alt="" class="logo"><br>
            <?php if (empty($error_message)) {
                $error_message = "";
            } ?>
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
                    <select name="role" class="form-control">
                        <option>Administrator</option>
                        <option>Super User</option>
                        <option>Site Admin</option>
                        <option>Viewer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="will_handle">Will handle:</label>
                    <select name="will_handle" class="form-control">
                        <?php
                        $sql = "SELECT * FROM dbo.tbl_corporation";
                        $stmt = sqlsrv_query($db, $sql);

                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                            <option><?= $row['corporation_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button class="login" name="btn_register">Login</button>
            </form>
        </div>
    </div>
</body>

</html>