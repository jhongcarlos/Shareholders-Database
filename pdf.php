<?php
// corporation pdf
function fetch_data()
{
  $conn_info = array("Database" => "test_db");
  $db = sqlsrv_connect('MPIC-BACKUP-02', $conn_info);
  $corp_name = $_POST['corp_name'];
  $output = '';
  $output .= '
        <table align="center" border="1">
        <tr>
        <th width="15%"><b>ID</b></th>
        <th width="45%"><b>Company Name</b></th>
        <th width="15%"><b>Type of Share</b></th>
        <th width="25%"><b>Shares Owned</b></th>
        </tr>
        ';
  $companies = array();
  $share = "";
  $typeofshare = "";
  $sql1 = "SELECT * FROM dbo.tbl_corporation 
  WHERE CONVERT(VARCHAR(MAX), corporation_name) = '$corp_name' 
  AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
  $stmt1 = sqlsrv_query($db, $sql1);

  while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    // if (strpos($row['company_affiliation'], $corp_name) !== false) {
    $arr = explode(",", $row['type_of_share']);
    $arr2 = explode("|", $row['shares_owned']);
    $arr3 = explode("|", $row['company_affiliation']);

    $position = "";
    foreach ($arr3 as $key => $value) {
      if ($value == $corp_name) {
        $position = $key;
      }
      $companies[] = $value;
    }
    $share = $row['shares_owned'];
    $typeofshare = $row['type_of_share'];
    // }
  }
  // $output .= $corp_name;
  foreach ($companies as $k => $v) {
    $num = 0;
    $sql = "SELECT * FROM dbo.tbl_company 
  WHERE CONVERT(VARCHAR(MAX), company_name) 
  LIKE '%$v%' 
  AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
    $stmt = sqlsrv_query($db, $sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $arr = explode(",", $row['ID']);
      $arr1 = explode(",", $row['type_of_share']);
      $arr2 = explode("|", $row['shares_owned']);
      $arr3 = explode("|", $row['company_affiliation']);
      $arr4 = explode("|", $share);
      $arr5 = explode(",", $typeofshare);
      $position = "";
      foreach ($arr3 as $key => $value) {
        // if ($value == $_GET['corp_name']) {
        $position = $key;
        // echo $key;
        // }
      }
      $output .= '
        <tr>
          <td>' . $row['ID'] . '</td>
          <td>' . $row['company_name'] . '</td>
          <td>' . $arr5[$k] . '</td>
          <td>' . $arr4[$k] . '</td>
        </tr>';
    }
    $num += 1;
  }

  $output .= '
            
  </table>
  </div>
  ';
  return $output;
}
if (isset($_POST["pdf"])) {
  $corp_name = $_POST['corp_name'];
  require_once('tcpdf/tcpdf.php');
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetCreator(PDF_CREATOR);
  $obj_pdf->SetTitle($corp_name . ' - Report');
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $obj_pdf->SetDefaultMonospacedFont('helvetica');
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
  $obj_pdf->setPrintHeader(false);
  $obj_pdf->setPrintFooter(false);
  $obj_pdf->SetAutoPageBreak(TRUE, 10);
  $obj_pdf->SetFont('helvetica', '', 11);
  $obj_pdf->AddPage();
  $content = '';
  $content .= '  
              <div align="center">
              <h1>' . $corp_name . '</h1>
              ';
  $content .= fetch_data();
  // $content .= '</table>';  
  $obj_pdf->writeHTML($content);
  ob_end_clean();
  $obj_pdf->Output('file.pdf', 'I');
}

// Company PDF
function comp_fetch_data()
{
  $conn_info = array("Database" => "test_db");
  $db = sqlsrv_connect('MPIC-BACKUP-02', $conn_info);
  $comp_name = $_POST['comp_name'];
  $output = '';
  $sql = "SELECT * FROM dbo.tbl_shareholder WHERE CONVERT(VARCHAR(MAX), is_deleted) = '0'";
  $stmt = sqlsrv_query($db, $sql);

  $output .= '
        <table align="center" border="1">
        <tr>
        <th width="15%"><b>ID</b></th>
        <th width="45%"><b>Shareholder Name</b></th>
        <th width="15%"><b>Type of Share</b></th>
        <th width="25%"><b>Shares Owned</b></th>
        </tr>
        ';
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if (strpos($row['company_affiliation'], $comp_name) !== false) {
      $arr = explode(",", $row['type_of_shares']);
      $arr2 = explode("|", $row['shares_owned']);
      $arr3 = explode("|", $row['company_affiliation']);
      $position = "";
      foreach ($arr3 as $key => $value) {
        if ($value == $comp_name) {
          $position = $key;
        }
      }
      $output .= '
                <tr>
                <td>' . $row['ID'] . '</td> 
                <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
                <td>' . $arr[$position] . '</td>
                <td>' . $arr2[$position] . '</td>
                </tr>';
    }
  }
  // =======
  $sql1 = "SELECT * FROM dbo.tbl_corporation WHERE CONVERT(VARCHAR(MAX), company_affiliation) LIKE '%$comp_name%' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
  $stmt1 = sqlsrv_query($db, $sql1);
  while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $arr = explode(",", $row['type_of_share']);
    $arr2 = explode("|", $row['shares_owned']);
    $arr3 = explode("|", $row['company_affiliation']);
    $position = "";
    foreach ($arr3 as $key => $value) {
      if ($value == $comp_name) {
        $position = $key;
      }
    }
    $output .= '
                <tr>
                <td>' . $row['ID'] . '</td> 
                <td>' . $row['corporation_name'] . '</td>
                <td>' . $arr[$position] . '</td>
                <td>' . $arr2[$position] . '</td>
                </tr>';
  }
  // =======
  $sql2 = "SELECT * FROM dbo.tbl_company WHERE CONVERT(VARCHAR(MAX), company_affiliation) = '$comp_name' AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
  $stmt2 = sqlsrv_query($db, $sql2);
  while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $arr = explode(",", $row['type_of_share']);
    $arr2 = explode("|", $row['shares_owned']);
    $arr3 = explode("|", $row['company_affiliation']);
    $position = "";
    foreach ($arr3 as $key => $value) {
      if ($value == $comp_name) {
        $position = $key;
      }
    }
    $output .= '
                <tr>
                <td>' . $row['ID'] . '</td> 
                <td>' . $row['company_name'] . '</td>
                <td>' . $arr[$position] . '</td>
                <td>' . $arr2[$position] . '</td>
                </tr>';
  }
  // =======
  $output .= '
            
  </table>
  </div>
  ';
  return $output;
}
if (isset($_POST["pdf_comp"])) {
  $comp_name = $_POST['comp_name'];
  require_once('tcpdf/tcpdf.php');
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetCreator(PDF_CREATOR);
  $obj_pdf->SetTitle($comp_name . ' - Report');
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $obj_pdf->SetDefaultMonospacedFont('helvetica');
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
  $obj_pdf->setPrintHeader(false);
  $obj_pdf->setPrintFooter(false);
  $obj_pdf->SetAutoPageBreak(TRUE, 10);
  $obj_pdf->SetFont('helvetica', '', 11);
  $obj_pdf->AddPage();
  $content = '';
  $content .= '  
              <div align="center">
              <h1>' . $comp_name . '</h1>
              ';
  $content .= comp_fetch_data();
  // $content .= '</table>';  
  $obj_pdf->writeHTML($content);
  ob_end_clean();
  $obj_pdf->Output('file.pdf', 'I');
}

// individual pdf
function individual_fetch_data()
{
  $conn_info = array("Database" => "test_db");
  $db = sqlsrv_connect('MPIC-BACKUP-02', $conn_info);
  $ind_id = $_POST['ind_id'];
  $output = '';
  $output .= '
        <table align="center" border="1">
        <tr>
        <th width="15%"><b>ID</b></th>
        <th width="45%"><b>Company Name</b></th>
        <th width="15%"><b>Type of Share</b></th>
        <th width="25%"><b>Shares Owned</b></th>
        </tr>
        ';
  $companies = array();
  $sql1 = "SELECT * FROM dbo.tbl_shareholder 
  WHERE ID = '$ind_id' 
  AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
  $stmt1 = sqlsrv_query($db, $sql1);

  while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    // if (strpos($row['company_affiliation'], $corp_name) !== false) {
    $arr = explode(",", $row['type_of_share']);
    $arr2 = explode("|", $row['shares_owned']);
    $arr3 = explode("|", $row['company_affiliation']);

    $position = "";
    $share = "";
    $typeofshare = "";
    foreach ($arr3 as $key => $value) {
      // if ($value == $ind_id) {
      $position = $key;
      // }
      $companies[] = $value;
      $share = $row['shares_owned'];
      $typeofshare = $row['type_of_shares'];
    }
    // }
  }
  // $output .= $corp_name;
  $num = 0;
  foreach ($companies as $k => $v) {
    $sql = "SELECT * FROM dbo.tbl_company 
  WHERE CONVERT(VARCHAR(MAX), company_name) 
  LIKE '%$v%' 
  AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
    $stmt = sqlsrv_query($db, $sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $arr = explode(",", $row['ID']);
      $arr1 = explode(",", $row['type_of_share']);
      $arr2 = explode("|", $row['shares_owned']);
      $arr3 = explode("|", $row['company_affiliation']);
      $arr4 = explode("|", $share);
      $arr5 = explode(",", $typeofshare);
      $position = "";
      foreach ($arr3 as $key => $value) {
        // if ($value == $_GET['corp_name']) {
        $position = $key;
        // echo $key;
        // }
      }
      $output .= '
        <tr>
          <td>' . $row['ID'] . '</td>
          <td>' . $row['company_name'] . '</td>
          <td>' . $arr5[$num] . '</td>
          <td>' . $arr4[$num] . '</td>
        </tr>';
    }
    $num += 1;
  }

  $output .= '
            
  </table>
  </div>
  ';
  return $output;
}
if (isset($_POST["ind_pdf"])) {
  include('server.php');
  $ind_id = $_POST['ind_id'];
  $sql1 = "SELECT * FROM dbo.tbl_shareholder 
  WHERE ID = '$ind_id' 
  AND CONVERT(VARCHAR(MAX), is_deleted) = '0'";
  $stmt1 = sqlsrv_query($db, $sql1);

  while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    require_once('tcpdf/tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle($row['first_name'] . ' ' . $row['last_name'] . ' - Report');
    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $obj_pdf->SetDefaultMonospacedFont('helvetica');
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
    $obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $obj_pdf->SetFont('helvetica', '', 11);
    $obj_pdf->AddPage();
    $content = '';
    $content .= '  
              <div align="center">
              <h1>' . $row['first_name'] . ' ' . $row['last_name'] . '</h1>
              ';
    $content .= individual_fetch_data();
    // $content .= '</table>';  
    $obj_pdf->writeHTML($content);
    ob_end_clean();
    $obj_pdf->Output('file.pdf', 'I');
  }
}
// ============
function audit_trail_fetch_data()
{
  // SELECT * 
  // FROM dbo.tbl_audit_trail
  // WHERE update_time 
  // BETWEEN '2019-11-18 00:00:00' 
  // AND '2019-12-18 23:59:59'
  $output = '';
  $output .= '
        <table border="1">
        <tr>
        <th width="10%"><b> ID</b></th>
        <th width="20%"><b> Name</b></th>
        <th width="45%"><b> Description</b></th>
        <th width="25%"><b> Date/Time</b></th>
        </tr>
        ';
  return $output;
}

if (isset($_POST["at_pdf"])) {
  include('server.php');

  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];

  require_once('tcpdf/tcpdf.php');
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetCreator(PDF_CREATOR);
  $obj_pdf->SetTitle('Audit Trail');
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $obj_pdf->SetDefaultMonospacedFont('helvetica');
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
  $obj_pdf->setPrintHeader(false);
  $obj_pdf->setPrintFooter(false);
  $obj_pdf->SetAutoPageBreak(TRUE, 10);
  $obj_pdf->SetFont('helvetica', '', 11);
  $obj_pdf->AddPage();
  $content = '';
  $content .= '  
              <div>
              ';
  $content .= audit_trail_fetch_data();
  // ====

  if(empty($_POST['startDate']) AND empty($_POST['endDate'])){
    $sql = "SELECT * 
    FROM dbo.tbl_audit_trail ORDER BY ID DESC";
  }
  else{
    $sql = "SELECT * 
  FROM dbo.tbl_audit_trail
  WHERE update_time 
  BETWEEN '$startDate 00:00:00' 
  AND '$endDate 23:59:59' ORDER BY ID DESC";
  }

  $stmt = sqlsrv_query($db, $sql);

  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $Date = $row['update_time']->format('m-d-Y h:i A');
    $content .= '
    <tr>
    <td> ' . $row['ID'] . '</td>
    <td> ' . $row['user_name'] . '</td>
    <td> ' . $row['description'] . '</td>
    <td> ' . $Date . '</td>
</tr>
    ';
  }
  $content .= '
  </table>
  </div>
  ';

  // ====
  // $content .= '</table>';  
  $obj_pdf->writeHTML($content);
  ob_end_clean();
  $obj_pdf->Output('file.pdf', 'I');
}
