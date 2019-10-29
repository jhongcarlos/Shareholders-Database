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
    <title>Forgot Password - Metro Pacific Investments Corporation</title>
</head>

<body>
    <div class="cont">
        <div class="form"><br>
            <img src="images/MPIC_logo.png" alt="" class="logo"><br>
            <?php if (empty($error_message)) {
                $error_message = "";
            } ?>
            <p style="color: red;text-align:center;padding:5px"><?= $error_message ?></p>
            <h2 style="color:#456;text-align:left;font-weight:300;text-align:center;margin-bottom:15px">Forgot Password</h2>
            <form method="post" action="forgot_password_code">
                <input type="text" class="user" name="email" placeholder="Email Address" />
                <button class="login" name="btn_forgot_password">Request Code</button>
                
                <center><a href="login">Login</a></center>
            </form>
        </div>
    </div>
</body>

</html>