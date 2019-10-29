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
    <title>Log-in - Metro Pacific Investments Corporation</title>
</head>

<body>
    <div class="cont">
        <div class="form"><br>
            <img src="images/MPIC_logo.png" alt="" class="logo"><br>
            <?php if (empty($error_message)) {
                $error_message = "";
            } ?>
            <p style="color: red;text-align:center;padding:5px"><?= $error_message ?></p>
            <form method="post">
                <input type="text" class="user" name="email" placeholder="Email Address" />
                <input type="password" class="pass" name="password" placeholder="Password" />
                <button class="login" name="btn_login">Login</button>
                <center><a href="forgot_password">Forgot password?</a></center>
            </form>
        </div>
    </div>
</body>

</html>