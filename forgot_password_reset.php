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
    <title>Reset Password - Metro Pacific Investments Corporation</title>
</head>

<body>
    <div class="cont">
        <div class="form"><br>
            <img src="images/MPIC_logo.png" alt="" class="logo"><br>
            <?php if (empty($wrong_code)) {
                $wrong_code = "";
            } ?>
            <h2 style="color:#456;text-align:left;font-weight:300;text-align:center;margin-bottom:15px">Enter your new password</h2>
            <form method="post" action="server.php">
                <input type="password" minlength="6" class="user" name="new_password" placeholder="********" />
                <button class="login" name="btn_forgot_password_reset">Reset</button>
                <center><a href="login">Login</a></center>
            </form>
        </div>
    </div>
</body>

</html>