<?php
include('server.php');
if (empty($_SESSION['mpic_mpic_name'])) {
  header('Location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php include('partial/header.php'); ?>
  <title>View Corporation - Metro Pacific Investment Corporation</title>
  <style>
    .logo {
      display: block;
      margin-left: auto;
      margin-right: auto;
      height: 80px;
      width: 180px;
      margin-top: 50px;
    }
  </style>
</head>

<body>
  <div class="container">
    <img src="images/MPIC_logo.png" alt="" class="img-responsive logo">
    <hr>
  </div>
  <div class="container" style="margin-top: 10px;">
    <div class="col-sm-12 form-legend">
      <a href="index.php">← Home</a>
    </div>
    <?php
    // view_id_corp
    $id = $_SESSION['view_id_corp'];
    $corp_name = "$_GET[corp_name]";
    $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(VARCHAR(MAX), company_affiliation) LIKE '%$corp_name%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
    $stmt = sqlsrv_query($db, $sql);

    ?>
    <br><br>
    <div class="row">
      <div class="col-md-6 col-xl-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading">Company Information</div>
          <div class="panel-body">
            <table class="table table-hover">
              <tbody>
                <?php
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                  ?>
                  <tr>
                    <td><b>Shareholder Name:</b></td>
                    <td><?= $row['first_name'] . " " . $row['first_name']; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading">Company Affiliation</div>
          <div class="panel-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Company Name</th>
                </tr>

              </thead>
              <tbody>
                <?php
                $sql = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(VARCHAR(MAX), corporation_name) LIKE '%$corp_name' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
                $stmt = sqlsrv_query($db, $sql);

                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                  $arr = explode(",", $row['company_affiliation']);
                  foreach ($arr as $val) {
                    echo "<tr><td><a href='view_corporation.php?corp_name=$val'>" . $val . "</a></td></tr>";
                  }
                  ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>