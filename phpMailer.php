<?php


require_once("PHPMailer/src/Exception.php");
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/SMTP.php');

// GMAIL AREA -------------------------

function phpMailerGMAIL()
{

  $conn_info = array("Database" => "test_db");
  $db = sqlsrv_connect('MPIC-BACKUP-02', $conn_info);

  $UserEmail = $_SESSION['forgot_password_email'];
  // $UserEmail = "ybjhong24@gmail.com";
  // Select Data =====
  $sql = "SELECT * FROM dbo.users_login WHERE CONVERT(VARCHAR(MAX), email) LIKE '%$UserEmail%'";
  $stmt = sqlsrv_query($db, $sql);
  $name = "";
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $name = $row['first_name'];
  }

  $email = "bertandmarie3@gmail.com";
  $pass = "09215564209";

  $mail = new  PHPMailer\PHPMailer\PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
  $mail->IsSMTP(); // telling the class to use SMTP
  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 587;   // set the SMTP port for the GMAIL server

  $mail->Username   = $email;  // GMAIL username
  $mail->Password   = $pass;            // GMAIL password

  $mail->AddAddress($UserEmail);
  $mail->SetFrom($email, 'MPIC - Forgot Password');
  $mail->Subject = 'MPIC - Forgot Password Code';
  //   $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically

  $body = '
      Hey <b>' . $name . '</b>,<br>
      <br>Your confirmation code is : <b>' . $_SESSION['forgot_password_code'] . '</b>
      <br><br>
      Thanks,<br>
      MPIC
      ';
  $mail->MsgHTML($body);
  // $mail->Send();
  if (!$mail->Send()) {
    echo "Error sending: " . $mail->ErrorInfo;
  } else {
    header("Location: forgot_password_code");
  }
}
