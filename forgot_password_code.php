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
    <div style="display:none">
        <?php
        $count = 1;
        // if ($count == 1) {
        require_once('phpMailer.php');
        $email = $_POST['email'];
        $sql = "SELECT * FROM dbo.users_login WHERE email LIKE '$email'";
        $stmt = sqlsrv_query($db, $sql, array(), array("Scrollable" => "static"));

        if (sqlsrv_num_rows($stmt) == 1) {
            // $error_message = "Email is in DB, proceed to next step";
            $_SESSION['forgot_password_email'] = $email;
            $_SESSION['forgot_password_code'] = rand(10000, 99999);
            phpMailerGMAIL();
        } else {
            $error_message = "Email Address does not exist";
            header('Location: forgot_password');
        }
        // }
        ?>
    </div>
    <div class="cont">
        <div class="form"><br>
            <img src="images/MPIC_logo.png" alt="" class="logo"><br>
            <?php if (empty($wrong_code)) {
                $wrong_code = "";
            } ?>
            <p style="color: red;text-align:center;padding:5px"><?= $wrong_code ?></p>
            <h2 style="color:#456;text-align:left;font-weight:300;text-align:center;margin-bottom:15px">Please enter code sent by email</h2>
            <form method="post" action="server.php">
                <input type="text" class="user" name="fp_code" placeholder="#####" />
                <button class="login" name="btn_forgot_password_code">Submit</button>
                <center><a href="login">Login</a></center>
            </form>
        </div>
    </div>
</body>

</html>